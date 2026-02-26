<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CalendarEventController extends Controller
{
    public function calendar()
    {
        return view('vendor.calendar.index');
    }

    public function events(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $start = Carbon::parse($request->start)->startOfDay();
        $end   = Carbon::parse($request->end)->endOfDay();

        $events = CalendarEvent::where('vendor_id', auth()->id())
            ->where('end_date', '>=', $start)
            ->where('start_date', '<=', $end)
            ->orderBy('start_date')
            ->get();

        // Define your event type → color mapping here
        // Use light/pastel background colors that work well with dark text
        $typeColors = [
            'meeting'     => '#d9f0fa',   // light blue
            'holiday'     => '#fad9d9',   // light red / pink
            'deadline'    => '#f9f0d9',   // light orange / yellow
            'reminder'    => '#d9fad9',   // light green
            'personal'    => '#e6d9fa',   // light purple
            'birthday'    => '#fad9e6',   // light pink
            'other'       => '#d9e6fa',   // light blue
            'default'     => '#f0f0f0',   // very light gray
        ];

        $events = $events->map(function ($event) use ($typeColors) {
            $type = $event->event_type ?? 'default';
            $color = $typeColors[$type] ?? $typeColors['default'];

            return [
                'id'          => $event->id,
                'title'       => $event->title,
                'event_type'  => $event->event_type,
                'start'       => $event->start_date, 
                'end'         => $event->end_date,
                'description' => $event->description,
                'backgroundColor' => $color,   // ← FullCalendar v5/v6 uses this
                'textColor'       => '#1f2937', // dark gray for good contrast (or '#000')
            ];
        });

        return response()->json($events);
    }

    public function index()
    {
        $events = CalendarEvent::where('vendor_id', auth()->id())->orderBy('id', 'desc')->get();
        if (request()->ajax()) {
            return Datatables::of($events)
                ->addIndexColumn()
                ->addColumn('event_type', function ($event) {
                    return ucfirst($event->event_type) ?: '--';
                })
                ->addColumn(
                    'start_date',
                    function ($event) {
                        return date('Y-m-d H:i A', strtotime($event->start_date));
                    }
                )->addColumn('end_date', function ($event) {
                    return date('Y-m-d H:i A', strtotime($event->end_date));
                })
                ->addColumn('actions', function ($event) {
                    $editUrl = route('vendor.events.edit', $event->id);
                    $deleteUrl = route('vendor.events.delete', $event->id);
                    return compact('editUrl', 'deleteUrl');
                })
                ->rawColumns(['event_type', 'start_date', 'end_date', 'actions'])
                ->make(true);
        }
        return view('vendor.events.index');
    }

    public function create()
    {
        return view('vendor.events.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'event_type' => 'nullable|in:meeting,holiday,deadline,reminder,personal,birthday,other',
            'startDate' => 'required|date|after_or_equal:today',
            'endDate' => 'required|date|after_or_equal:startDate',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        CalendarEvent::create([
            'title' => $request->input('title'),
            'event_type' => $request->input('event_type'),
            'start_date' => $request->input('startDate'),
            'end_date' => $request->input('endDate'),
            'description' => $request->input('description'),
            'vendor_id' => auth()->id(),
        ]);

        session()->flash('success', 'Event created successfully');
        return response()->json(['status' => 'success', 'message' => 'Event created successfully'], 201);
    }

    public function edit($id)
    {
        $event = CalendarEvent::where('vendor_id', auth()->id())->where('id', $id)->first();
        if (!$event) {
            return response()->json(['status' => 'error', 'message' => 'Event not found'], 404);
        }
        return view('vendor.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'event_type' => 'nullable|in:meeting,holiday,deadline,reminder,personal,birthday,other',
            'startDate' => 'required|date|after_or_equal:today',
            'endDate' => 'required|date|after_or_equal:startDate',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $event = CalendarEvent::where('vendor_id', auth()->id())->where('id', $id)->first();
        if (!$event) {
            return response()->json(['status' => 'error', 'message' => 'Event not found'], 404);
        }

        $event->update([
            'title' => $request->input('title'),
            'event_type' => $request->input('event_type'),
            'start_date' => $request->input('startDate'),
            'end_date' => $request->input('endDate'),
            'description' => $request->input('description'),
        ]);

        session()->flash('success', 'Event updated successfully');
        return response()->json(['status' => 'success', 'message' => 'Event updated successfully'], 200);
    }

    public function destroy($id)
    {
        $event = CalendarEvent::where('vendor_id', auth()->id())->where('id', $id)->first();
        if (!$event) {
            return response()->json(['status' => 'error', 'message' => 'Event not found'], 404);
        }
        $event->delete();
        session()->flash('success', 'Event deleted successfully');
        return response()->json(['status' => 'success', 'message' => 'Event deleted successfully'], 200);
    }
}
