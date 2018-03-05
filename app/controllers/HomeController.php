<?php

class HomeController extends BaseController {

    public function getWelcome()
    {
        $eventsQuery = EventDay::visible()
            ->hasDate()
            ->orderBy('date');

        if ( ! Input::get('all')) {
            $eventsQuery = $eventsQuery
                ->where('date', '>=', DB::raw('NOW() - INTERVAL 1 DAY'));
        }

        $events = $eventsQuery->get();

        $calendars = [];

        if ( ! $events->isEmpty()) {
            $start_month = Carbon::createFromFormat('Y-m-d', $events->first()->date)->firstOfMonth();
            $end_month = Carbon::createFromFormat('Y-m-d', $events->last()->date)->firstOfMonth();


            for ($current_month = $start_month; $current_month->lte($end_month); $current_month->addMonth()) {
                $events_calendar = [];

                foreach ($events as $event) {
                    $datetime = Carbon::createFromFormat('Y-m-d', $event->date);
                    if ($datetime->between($current_month, $current_month->copy()->endOfMonth())) {
                        $events_calendar[$datetime->day] = '#event-' . $event->id;
                    }
                }

                $calendars[] = [
                    'year' => $current_month->year,
                    'month' => $current_month->month,
                    'events' => $events_calendar,
                ];
            }
        }
        $eventsQuery = EventDay::visible()
        ->hasDate()
        ->orderBy('date');

        if ( ! Input::get('all')) {
            $eventsQuery = $eventsQuery
                ->where('date', '>=', DB::raw('NOW() - INTERVAL 1 DAY'));
        }

        $events = $eventsQuery->get();

        $calendars = [];

        if ( ! $events->isEmpty()) {
            $start_month = Carbon::createFromFormat('Y-m-d', $events->first()->date)->firstOfMonth();
            $end_month = Carbon::createFromFormat('Y-m-d', $events->last()->date)->firstOfMonth();


            for ($current_month = $start_month; $current_month->lte($end_month); $current_month->addMonth()) {
                $events_calendar = [];

                foreach ($events as $event) {
                    $datetime = Carbon::createFromFormat('Y-m-d', $event->date);
                    if ($datetime->between($current_month, $current_month->copy()->endOfMonth())) {
                        $events_calendar[$datetime->day] = '#event-' . $event->id;
                    }
                }

                $calendars[] = [
                    'year' => $current_month->year,
                    'month' => $current_month->month,
                    'events' => $events_calendar,
                ];
            }
        }

        
        
        return View::make('pages.welcome')->with(compact('events', 'calendars'));
    }

    public function getPhotos()
    {
        //echo "<p>Some testing....</p>";
        //app('App\Controllers\CronController')->getInstagram();
        //CronController::getInstagram();
        $photos = InstagramPhoto::visible()
            ->latest()
            ->take(12)
            ->get();

        return Response::json($photos);
    }

    public function getTest(){
        echo "<h1>This is a secret new function.</h1>";
        return View::make('pages.welcome');
    }
    

}
