<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CalendarEventController extends Controller
{
    public function calendar()
    {
        return view('admin.calendar.index');
    }

    public function events()
    {
        $events = CalendarEvent::select('id', 'title', 'start_date as start', 'end_date as end', 'description')->get();
        return response()->json($events);
    }

    public function index()
    {
        $events = CalendarEvent::all();
        if (request()->ajax()) {
            return Datatables::of($events)
                ->addIndexColumn()
                ->addColumn(
                    'start_date',
                    function ($event) {
                        return date('Y-m-d H:i A', strtotime($event->start_date));
                    }
                )->addColumn('end_date', function ($event) {
                    return date('Y-m-d H:i A', strtotime($event->end_date));
                })
                ->addColumn('actions', function ($event) {
                    $editUrl = route('admin.events.edit', $event->id);
                    $deleteUrl = route('admin.events.delete', $event->id);
                    return compact('editUrl', 'deleteUrl');
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('admin.events.index');
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
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

    public function edit($id)
    {
        $event = CalendarEvent::where('id', $id)->first();
        if (!$event) {
            return response()->json(['status' => 'error', 'message' => 'Event not found'], 404);
        }
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
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
        if (!$event) {
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

    public function destroy($id)
    {
        $event = CalendarEvent::where('id', $id)->first();
        if (!$event) {
            return response()->json(['status' => 'error', 'message' => 'Event not found'], 404);
        }
        $event->delete();
        session()->flash('success', 'Event deleted successfully');
        return response()->json(['status' => 'success', 'message' => 'Event deleted successfully'], 200);
    }
}
