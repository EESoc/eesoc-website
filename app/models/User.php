<?php

use Illuminate\Auth\UserInterface;
use Robbo\Presenter\PresentableInterface;

class User extends Eloquent implements UserInterface, PresentableInterface {
    private $imperial_college_user;

    private $unclaimed_lockers_count;

    private $dinner_tickets_count;
    private $unclaimed_dinner_tickets_count;

    public static function boot()
    {
        parent::boot();

        // Set a unique email token for each user
        static::saving(function($user) {
            if (empty($user->email_token)) {
                do {
                    $user->email_token = str_random(40);
                } while (static::where('id', '<>', $user->id)->where('email_token', '=', $user->email_token)->first());
            }
        });
    }

    /*
    Relations
     */

    public function studentGroup()
    {
        return $this->belongsTo('StudentGroup');
    }

    public function lockers()
    {
        return $this->hasMany('Locker', 'owner_id');
    }

    public function sales()
    {
        return $this->hasMany('Sale');
    }

    public function instagramPhoto()
    {
        return $this->hasMany('InstagramPhoto');
    }

    public function signIns()
    {
        return $this->hasMany('UserSignIn');
    }

    public function subscriptions()
    {
        return $this->belongsToMany('Newsletter', 'user_subscriptions');
    }

    public function books()
    {
        return $this->hasMany('Book');
    }
	
	public function events()
    {
        return $this->belongsToMany('EventDay', 'event_user', 'user_id', 'event_id')->withTimestamps();
    }

  /*  public function applications()
    {
        return $this->hasMany('Application');
    }
  */

    public function dinnerSales()
    {
        return $this->hasMany('DinnerSale');
    }

    public function DinnerGroupMember()
    {
        return $this->hasOne('DinnerGroupMember');
    }

        /*
    Scopes
     */

    public function scopeAdmin($query)
    {
        return $query->where('is_admin', '=', true);
    }

    public function scopeNonAdmin($query)
    {
        return $query->where('is_admin', '=', false);
    }

    public function scopeMember($query)
    {
        return $query->where('is_member', '=', true);
    }

    public function scopeNonMember($query)
    {
        return $query->where('is_member', '=', false);
    }

    public function scopeAlphabetically($query)
    {
        return $this->scopeAlphabeticallyUsing($query, 'username');
    }

    public function scopeAlphabeticallyUsing($query, $column)
    {
        return $query->orderBy($column);
    }

    public function scopeSearching($query, $searching)
    {
        return $query->where(function($query) use ($searching) {
            $query->where('username', 'like', "%{$searching}%")
                ->orWhere('email',    'like', "%{$searching}%")
                ->orWhere('name',     'like', "%{$searching}%");
        });
    }

    public function scopeAdminsFirst($query)
    {
        return $query->orderBy('is_admin', 'DESC');
    }

    public function scopeInGroup($query, $group, $getRelated = TRUE)
    {
        if (!is_array($group));
            $group = [$group];

        $groupIds = [];

        if ($getRelated)
        {
            foreach ($group as $groupInstance)
            {
                if ($groupInstance instanceof StudentGroup)
                    $groupObj = $groupInstance;
                else
                    $groupObj = StudentGroup::findOrFail($groupInstance);

                $groupIds += $groupObj->getRelatedGroupIdsAttribute();
            }
        }
        else
        {
            foreach ($group as $groupInstance)
            {
                if ($groupInstance instanceOf StudentGroup)
                    $groupIds[] = $groupInstance->id;
                else
                    $groupIds = $groupInstance;
            }
        }

        return $query->whereIn('student_group_id', $groupIds);
    }

    public function scopeHasStudentGroup($query)
    {
        return $query->whereNotNull('student_group_id');
    }

    public function scopeHasImage($query)
    {
        return $query->whereNotNull('image_blob');
    }

    public function scopeHasUnclaimedDinnerTickets($query)
    {
        return $query
            ->whereIn('id', function($query) {
                return $query
                    ->select('user_id')
                    ->from('dinner_sales');
            })
            ->whereNotIn('id', function($query) {
                return $query
                    ->select('ticket_purchaser_id')
                    ->from('dinner_group_members')
                    ->whereNotNull('ticket_purchaser_id');
            })
            ->orderBy('name');
    }

    public function scopeFamilyParent($query, $familyId)
    {
        return $query->where('parent_of_family_id', '=', $familyId)->orderBy('username');
    }

    public function scopeFamilyChild($query, $familyId)
    {
        return $query->where('child_of_family_id', '=', $familyId)->orderBy('username');
    }

    /*
    Factories
     */

    /**
     * Find or create a User using data from LDAP.
     * @param  string $username Imperial College username.
     * @return User
     */
    public static function findOrCreateWithLDAP($username)
    {
        $username = strtolower($username);

        $imperialCollegeUser = new ImperialCollegeUser($username);
        if ( ! $imperialCollegeUser->exist()) {
            return null;
        }

        // Find or create new User
        $user = static::where('username', '=', $username)->first();
        if ( ! $user) {
            $user = new static;
            $user->username = $username;
        }

        $user->setImperialCollegeUser($imperialCollegeUser);
        $user->synchronizeWithLDAP();

        return $user;
    }

    /**
     * Revoke all users' status.
     * @return mixed
     */
    public static function resetMemberships()
    {
        $user = new static;
        return $user->update(array('is_member' => false));
    }

    /**
     * Revoke all users' Mums and Dads status.
     * @return mixed
     */
    public static function resetFamilies()
    {
        $user = new static;
        return $user->update(array('child_of_family_id' => null, 'parent_of_family_id' => null));
    }

    /**
     * Return user statistics.
     * @return array
     */
    public static function statistics()
    {
        return array(
            'everybody_count'   => static::count(),
            'admins_count'      => static::admin()->count(),
            'non_admins_count'  => static::nonAdmin()->count(),
            'members_count'     => static::member()->count(),
            'non_members_count' => static::nonMember()->count(),
        );
    }

    public function getAuthIdentifier()
    {
        return $this->username;
    }

    public function getAuthPassword()
    {
        return null;
    }

    public function setImperialCollegeUser($imperial_college_user)
    {
        $this->imperial_college_user = $imperial_college_user;
    }

    public function getImperialCollegeUser()
    {
        if (!isset($this->imperial_college_user)) {
            $this->imperial_college_user = new ImperialCollegeUser($this->username);
        }

        return $this->imperial_college_user;
    }

    public function checkPassword($password)
    {
        return $this->getImperialCollegeUser()->checkPassword($password);
    }

    public function synchronizeWithLDAP()
    {
        $this->username = $this->getImperialCollegeUser()->username;
        $this->name     = $this->getImperialCollegeUser()->name;
        $this->email    = $this->getImperialCollegeUser()->email;
        $this->extras   = implode("\n", $this->getImperialCollegeUser()->info);
        return $this->save();
    }

    public function recordSignIn()
    {
        // Log sign in
        $sign_in = new UserSignIn;
        $sign_in->user()->associate($this);
        $sign_in->ip_address      = $_SERVER['REMOTE_ADDR'];
        $sign_in->http_user_agent = $_SERVER['HTTP_USER_AGENT'];
        $sign_in->save();

        // Update this user's sign in
        $this->last_sign_in_at = new DateTime;

        // First time signing in
        if (!$this->first_sign_in_at) {
            $this->first_sign_in_at = $this->last_sign_in_at;
        }

        $this->sign_in_count++;

        return $this->save();
    }

    /**
     * Return a created presenter.
     * @return Robbo\Presenter\Presenter
     */
    public function getPresenter()
    {
        return new UserPresenter($this);
    }

    /**
     * Return has image attribute.
     * @return boolean
     */
    public function getHasImageAttribute()
    {
        return !empty($this->image_blob);
    }

    /**
     * Return has email attribute.
     * @return boolean
     */
    public function getHasEmailAttribute()
    {
        return !empty($this->email);
    }

    /**
     * Check if filter exists.
     * @param  string $filter
     * @return boolean
     */
    public function canFilterBy($filter)
    {
        return isset($this->filter_to_function_map[$filter]);
    }

    /**
     * Apply filter.
     * @param  string $filter Filter to be applied.
     *                        Checks the filter_to_function_map for validity.
     * @return mixed
     */
    public function filterBy($filter)
    {
        if (isset($this->filter_to_function_map[$filter])) {
            return $this->{$filter}();
        } else {
            return $this;
        }
    }

    /*
    Locker methods
     */

    /**
     * Get the number of unclaimed lockers.
     * @return integer
     */
    public function getUnclaimedLockersCountAttribute()
    {
        if ($this->unclaimed_lockers_count === null) {
            $bought = $this->sales()->locker()->sum('quantity');
            $owned = $this->lockers()->where('status', '=', "taken")->count();

            $this->unclaimed_lockers_count = $bought - $owned;
        }

        return $this->unclaimed_lockers_count;
    }

    /**
     * Return has unclaimed lockers count.
     * @return integer
     */
    public function getHasUnclaimedLockersAttribute()
    {
        return $this->getUnclaimedLockersCountAttribute() > 0;
    }

    /*
     * Dinner methods
     */

    public function getDinnerTicketsCountAttribute()
    {
        if ($this->dinner_tickets_count === null) {
            $this->dinner_tickets_count = $this->dinnerSales()->sum('quantity');
        }

        return $this->dinner_tickets_count;
    }

    /**
     * Get the number of unclaimed dinner tickets.
     * @return integer
     */
    public function getUnclaimedDinnerTicketsCountAttribute()
    {
        if ($this->unclaimed_dinner_tickets_count === null) {
            $bought = $this->getDinnerTicketsCountAttribute();
            $claimed = DinnerGroupMember::purchasedBy($this)->count();

            $this->unclaimed_dinner_tickets_count = $bought - $claimed;
        }

        return $this->unclaimed_dinner_tickets_count;
    }

    /**
     * Return has unclaimed dinner tickets count.
     * @return integer
     */
    public function getHasUnclaimedDinnerTicketsAttribute()
    {
        return $this->getUnclaimedDinnerTicketsCountAttribute() > 0;
    }
}
