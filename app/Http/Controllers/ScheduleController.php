<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'logDays' => 'required',

        ]);
        $schedule = Schedule::where('application',$request->apps);
        if ($schedule) {
            $data = [
                'isDaily' => $request->isDaily,
                'hrs' => $request->hrs,
                'isWeekly'   => $request->isWeekly,
                'week_day' => $request->week_day,
                'isMonthly' => $request->isMonthly,
                'month_date'   => $request->month_date,
                'logDays'   => $request->logDays,
            ];
            $schedule->update($data);
        } else {
            Schedule::create([
                'application' => $request->apps,
                'isDaily' => $request->isDaily,
                'hrs' => $request->hrs,
                'isWeekly'   => $request->isWeekly,
                'week_day' => $request->week_day,
                'isMonthly' => $request->isMonthly,
                'month_date'   => $request->month_date,
                'logDays'   => $request->logDays,
            ]);
        }
    }
    public function getSchedule()
    {
        return Schedule::all();
    }
}
