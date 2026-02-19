<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CalendarController extends Controller
{
    public function calendar()
    {
        return view('admin.calendar.index');
    }

    public function events()
    {
        $events = CalendarEvent::select('title', 'start_date as start', 'end_date as end', 'description')->get();
        return response()->json($events);
    }

    public function storeEvent(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        CalendarEvent::create([
            'title' => $request->input('title'),
            'start_date' => $request->input('startDate'),
            'end_date' => $request->input('endDate'),
            'description' => $request->input('description'),
        ]);

        session()->flash('success', 'Event created successfully');
        return response()->json(['status' => 'success', 'message' => 'Event created successfully'], 201);
    }

    public function editEvent($id){
        $event = CalendarEvent::where('id', $id)->first();
        if(!$event){
            return response()->json(['status' => 'error', 'message' => 'Event not found'], 404);
        }
        return response()->json($event);
    }

    public function updateEvent(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $event = CalendarEvent::where('id', $id)->first();
        if(!$event){
            return response()->json(['status' => 'error', 'message' => 'Event not found'], 404);
        }

        $event->update([
            'title' => $request->input('title'),
            'start_date' => $request->input('startDate'),
            'end_date' => $request->input('endDate'),
            'description' => $request->input('description'),
        ]);

        session()->flash('success', 'Event updated successfully');
        return response()->json(['status' => 'success', 'message' => 'Event updated successfully'], 200);
    }
}
