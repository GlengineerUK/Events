<?php

namespace App\Http\Controllers;

use App\Event;
use App\Shift;
use App\Utilities\HasManyUpdater;
use App\WorkerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $events = Event::with('shifts')->get();
        return view('event.index')->with(['events' => $events]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $event = new Event;
        $workerTypes = WorkerType::all();
        return view('event.createOrEdit')->with([
            'workerTypes' => $workerTypes,
            'event' => $event,
            'target' => '/',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function store(Request $request)
    {

        $data = $request->input();
        $data = $this->removeUnusedWorkerGroups($data);

        DB::transaction(function() use ($data) {
            $event = new Event($data);
            $event->save();

            foreach($data['shift'] as $shiftData)
            {

                $shift = $event->shifts()->create($shiftData);

                foreach($shiftData['worker_types'] as $workerType)
                {

                    $shift->workerTypes()->attach($workerType['id'], ['quantity' => $workerType['quantity']]);
                }
            }
        });

        $event = Event::create($data);


        return redirect('/')->with('success', 'Event created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Event $event)
    {
        $workerTypes = WorkerType::all();
        return view('event.createOrEdit')->with([
            'workerTypes' => $workerTypes,
            'event' => $event->load('shifts', 'shifts.workerTypes'),
            'target' => '/' . $event->id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $data = $request->input();

        $data = $this->removeUnusedWorkerGroups($data);
        DB::transaction(function() use ($data, $event) {
            $event->update($data);
            if ($data['shift']){
                (new HasManyUpdater($event, Shift::class))->update($data['shift'], true);
            }
        });
        return redirect('/')->with('success', 'Event updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect('/')->with('success', 'Event deleted.');
    }

    /**
     * @param $data
     * @return mixed
     */
    public function removeUnusedWorkerGroups($data)
    {
        foreach ($data['shift'] as $shift) {
            $data['shift'][$shift['id']]['worker_types'] = array_filter($shift['worker_types'], function ($item) {
                return isset($item['id']);
            });
        }
        return $data;
    }
}
