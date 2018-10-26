<?php

class NewsletterEmail extends Eloquent {

    protected $fillable = ['subject', 'preheader', 'from_name', 'from_email', 'reply_to_email', 'body'];

    public function __construct()
    {
        parent::__construct();

        $this->state = 'draft';
    }

    public function newsletters()
    {
        return $this->belongsToMany('Newsletter', 'newsletters_newsletter_emails');
    }

    public function queuedEmails()
    {
        return $this->hasMany('NewsletterEmailQueue');
    }

    public function getSentCountAttribute()
    {
        return $this->queuedEmails()->sent()->count();
    }

    public function getSendQueueLengthAttribute()
    {
        return $this->queuedEmails()->count();
    }

    public function getIsDraftAttribute()
    {
        return $this->state === 'draft';
    }

    public function getIsSendingAttribute()
    {
        return $this->state === 'sending';
    }

    public function getIsCompletedAttribute()
    {
        return $this->state === 'completed';
    }

    public function getCanSaveAttribute()
    {
        return $this->state === 'draft';
    }

    public function getCanSendAttribute()
    {
        return $this->state === 'draft';
    }

    public function getCanPauseAttribute()
    {
        return $this->state === 'sending';
    }

    public function getSentEmailPercentageAttribute()
    {
        $total = $this->send_queue_length;

        if ($total == 0) {
            return 0;
        }

        $sent = $this->sent_count;

        return ceil($sent / $total * 100);
    }

    public function getHtmlBodyAttribute()
    {
        return View::make('email_layouts.newsletter')
            ->with('body', $this->body)
            ->render();
    }

    /**
     * Builds an email queue
     */
    public function buildEmailQueue()
    {
        set_time_limit(0);

        $inserts = [];

        foreach ($this->newsletters as $newsletter) {
            // Process student groups
            $student_group_ids = $newsletter->student_groups->lists('id');
            if ( ! empty($student_group_ids)) {
				//TO DO fix
                /*$users = User::where('is_member','=',1)->where(function ($query) use ($student_group_ids) {
						return $query->whereIn('student_group_id', $student_group_ids)
							  ->orWhere('student_group_id', 'IS', "NULL");
					})->whereNotIn('id', function($query) {
                        // Remove duplicate
                        return $query
                            ->select('user_id')
                            ->from('newsletter_email_queue')
                            ->where('newsletter_email_id', '=', $this->id);
                    })
                    ->get();*/
					$users = User::where('is_member','=',1)->whereIn('student_group_id', $student_group_ids)
					->whereNotIn('id', function($query) {
                        // Remove duplicate
                        return $query
                            ->select('user_id')
                            ->from('newsletter_email_queue')
                            ->where('newsletter_email_id', '=', $this->id);
                    })
                    ->get();
                foreach ($users as $user) {
                    $inserts[$user->email] = [
                        'newsletter_email_id' => $this->id,
                        'tracker_token'       => str_random(20),
                        'to_email'            => $user->email,
                        'user_id'             => $user->id,
                    ];
                }
            }
            
            //special case for subscribable lists
            // in this case each user is linked to a category
            if($newsletter->is_subscribable){
                $subscriber_ids = $newsletter->subscribers->lists('id');

                if ( ! empty($subscriber_ids)) {
                    $users = User::where('is_member','=',1)->whereIn('id', $subscriber_ids)
					->whereNotIn('id', function($query) {
                        // Remove duplicate
                        return $query
                            ->select('user_id')
                            ->from('newsletter_email_queue')
                            ->where('newsletter_email_id', '=', $this->id);
                    })
                    ->get();

                    //$output = "Emails: <br>";
                    foreach ($users as $user) {
                        $inserts[$user->email] = [
                            'newsletter_email_id' => $this->id,
                            'tracker_token'       => str_random(20),
                            'to_email'            => $user->email,
                            'user_id'             => $user->id,
                        ];

                        //$output .= $user->email . " <br>";
                    }
                    //return $output;
                }
               
            }
    
        }

        if ( ! empty($inserts)) {
            DB::table('newsletter_email_queue')->insert(array_values($inserts));
            return count($inserts);
        }

        return 0; //if no email to send, count == 0
    }

    /**
     * Send batch emails
     */
    public function sendBatch($size = 10, &$errors = [])
    {
        if (App::environment() === 'local') {
            // Mailcatcher
            $internal_transport = Swift_SmtpTransport::newInstance('localhost', 1025);

            $external_transport = Swift_SmtpTransport::newInstance('localhost', 1025);
        } else {
            $internal_transport = Swift_MailTransport::newInstance();

            $external_transport = Swift_SmtpTransport::newInstance('smtp.zoho.com', 465, 'ssl')
                ->setUsername(Config::get('zoho_smtp.username'))
                ->setPassword(Config::get('zoho_smtp.password'));
        }

        $internal_mailer = Swift_Mailer::newInstance($internal_transport);
        $external_mailer = Swift_Mailer::newInstance($external_transport);

        $message = $this->buildMessage();

        $recipients = $this
            ->queuedEmails()
            ->pendingSend()
            ->take($size)
            ->get();

        $sent_emails = [];

        if ($recipients->isEmpty()) { // Finished sending out emails
            $this->state = 'completed';
            $this->save();

            return $sent_emails;
        }

        foreach ($recipients as $recipient) {
            try
            {
                $message->setTo($recipient->to_email);

                // Process tracking pixel
                $tracking_pixel_html = '<img src="' . $recipient->tracking_pixel_url . '" width="1" height="1" />';
                $message->setBody(str_replace('<tracking_pixel>', $tracking_pixel_html, $message->getBody()));

                if (substr($recipient->to_email, -15) === '@imperial.ac.uk') {
                    // Internal emails
                    $mailer = $internal_mailer;

                    //$message->setFrom([$this->from_email => $this->from_name]);
                    $message->setFrom(["eesoc@imperial.ac.uk" => $this->from_name]);
                } else {
                    $mailer = $external_mailer;

//                    $message->setFrom(['no-reply@eesoc.com' => $this->from_name]);
$message->setFrom(["eesoc@imperial.ac.uk" => $this->from_name]);  
  }

                if ($mailer->send($message)) {
                    // Mark email queue as sent
                    $recipient->sent = true;
                    $recipient->save();

                    $sent_emails[] = $recipient->to_email;
                }
            } catch (\Swift_SwiftException $e) {
                $recipient->failed = true;
                $recipient->save();

                $errors[] = $e;
            }
        }

        return $sent_emails;
    }

    /**
     * Sends a test email to a user
     * @param  User   $user
     * @return integer
     */
    public function sendTestToUser(User $user)
    {
        if (App::environment() === 'local') {
            // Mailcatcher
            $transport = Swift_SmtpTransport::newInstance('localhost', 1025);
        } else {
            $transport = Swift_MailTransport::newInstance();
        }

        $mailer = Swift_Mailer::newInstance($transport);

        $message = $this->buildMessage();

        $message->setTo($user->email);
        $message->setFrom([$this->from_email => $this->from_name]);

        $message->setBody(str_replace('<tracking_pixel>', '', $message->getBody()));

        return $mailer->send($message);
    }

    /**
     * Sends a test email to a user
     * @param  User   $user
     * @return integer
     */
     public function sendTestToPresident()
     {
         if (App::environment() === 'local') {
             // Mailcatcher
             $transport = Swift_SmtpTransport::newInstance('localhost', 1025);
         } else {
             $transport = Swift_MailTransport::newInstance();
         }
 
         $mailer = Swift_Mailer::newInstance($transport);
 
         $message = $this->buildMessage();
 
         $message->setTo("eesoc.president@imperial.ac.uk");    // $user->email
         $message->setFrom(["eesoc.webmaster@imperial.ac.uk" => "EESoc Webmaster"]); //$this->from_email
 
         $message->setBody(str_replace('<tracking_pixel>', '', $message->getBody()));
 
         return $mailer->send($message);
     }

    /**
     * Build Swift_Message instance with all newsletter email data.
     * @return Swift_Message
     */
    private function buildMessage()
    {
        // Setup message
        $message = Swift_Message::newInstance();

        if ($this->reply_to_email) {
            $message->setReplyTo($this->reply_to_email);
        }

        $message->setSubject($this->subject);

        $message->setBody($this->html_body, 'text/html');

        if ($this->preheader) {
            $message->addPart($this->preheader, 'text/plain');
        }

        return $message;
    }

    public function getDates()
    {
        return array('created_at', 'updated_at', 'last_updated_at');
    }

    public function refreshLastUpdatedBy(User $user)
    {
        $this->last_updated_at = time();
        $this->last_updated_by = $user->username;
        $this->save();
    }

}
