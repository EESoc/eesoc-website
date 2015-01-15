<?php

class EventsController extends BaseController {

    public function getIndex()
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

        return View::make('events.index')
            ->with(compact('events', 'calendars'));
    }

}
