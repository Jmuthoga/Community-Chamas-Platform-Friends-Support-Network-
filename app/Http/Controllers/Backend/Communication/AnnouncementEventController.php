<?php

namespace App\Http\Controllers\Backend\Communication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\GenericNotificationMail;
use App\Jobs\SendSmsJob;
use Illuminate\Support\Str;

class AnnouncementEventController extends Controller
{
    // ================= ANNOUNCEMENTS =================
    public function announcements(Request $request)
    {
        abort_if(!Auth::user()->can('announcement_view'), 403);

        if ($request->ajax()) {
            $announcements = Announcement::latest()->with('creator');

            return DataTables::of($announcements)
                ->addIndexColumn()
                ->addColumn('creator', fn($row) => $row->creator?->name ?? 'System')
                ->addColumn('message', fn($row) => Str::limit($row->message, 50))
                ->addColumn('audience', fn($row) => $row->audience ?? 'All Members')
                ->addColumn(
                    'send_email',
                    fn($row) =>
                    $row->send_email ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>'
                )
                ->addColumn(
                    'send_sms',
                    fn($row) =>
                    $row->send_sms ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>'
                )
                ->addColumn(
                    'is_active',
                    fn($row) =>
                    $row->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'
                )
                ->addColumn(
                    'created_at',
                    fn($row) =>
                    $row->created_at?->format('d M Y')
                )
                ->addColumn('actions', function ($row) {
                    $edit = '<a href="' . route('backend.admin.communications.announcements.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                    $delete = '<button data-id="' . $row->id . '" class="btn btn-sm btn-danger btn-delete">Delete</button>';
                    return $edit . ' ' . $delete;
                })
                ->rawColumns(['send_email', 'send_sms', 'is_active', 'actions'])
                ->make(true);
            }

        return view('backend.communication.announcements.index');
    }

    public function createAnnouncement()
    {
        abort_if(!Auth::user()->can('announcement_create'), 403);
        return view('backend.communication.announcements.create');
    }

    public function storeAnnouncement(Request $request)
    {
        abort_if(!Auth::user()->can('announcement_create'), 403);

        // Validate form input
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'send_email' => 'required|boolean',
            'send_sms'   => 'required|boolean',
        ]);

        // Create announcement
        $announcement = Announcement::create([
            'title'      => $data['title'],
            'message'    => $data['body'],
            'send_email' => $data['send_email'],
            'send_sms'   => $data['send_sms'],
            'is_active'  => true,
            'created_by' => Auth::id(),
        ]);

        // Send Email notifications if enabled
        if ($data['send_email']) {
            User::pluck('email')->each(function ($email) use ($announcement) {
                Mail::to($email)->queue(new GenericNotificationMail(
                    $announcement->title,
                    $announcement->message
                ));
            });
        }

        // Send SMS notifications if enabled
        if ($data['send_sms']) {
            $smsMessage = $announcement->title . "\n\n" // Title as heading
                . $announcement->message . "\n\n" // Body
                . "Powered by JM Innovatech Solution\n"
                . "https://jminnovatechsolution.co.ke/";

            User::whereNotNull('phone')->pluck('phone')->each(function ($phone) use ($smsMessage) {
                SendSmsJob::dispatch($phone, $smsMessage);
            });
        }


        return redirect()->route('backend.admin.communications.announcements')
            ->with('success', 'Announcement created successfully!');
    }

    public function editAnnouncement(Announcement $announcement)
    {
        abort_if(!Auth::user()->can('announcement_create'), 403);
        return view('backend.communication.announcements.edit', compact('announcement'));
    }

    public function updateAnnouncement(Request $request, Announcement $announcement)
    {
        abort_if(!Auth::user()->can('announcement_create'), 403);

        // Validate form input
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'send_email' => 'required|boolean',
            'send_sms'   => 'required|boolean',
        ]);

        // Update announcement
        $announcement->update([
            'title'      => $data['title'],
            'message'    => $data['body'],
            'send_email' => $data['send_email'],
            'send_sms'   => $data['send_sms'],
        ]);

        // Send Email notifications if enabled
        if ($data['send_email']) {
            User::pluck('email')->each(function ($email) use ($announcement) {
                Mail::to($email)->queue(new GenericNotificationMail(
                    $announcement->title,
                    $announcement->message
                ));
            });
        }

        // Send SMS notifications if enabled
        if ($data['send_sms']) {
            $smsMessage = $announcement->title . "\n\n" // Title as heading
                . $announcement->message . "\n\n" // Body
                . "Powered by JM Innovatech Solution\n"
                . "https://jminnovatechsolution.co.ke/";

            User::whereNotNull('phone')->pluck('phone')->each(function ($phone) use ($smsMessage) {
                SendSmsJob::dispatch($phone, $smsMessage);
            });
        }

        return redirect()->route('backend.admin.communications.announcements')
            ->with('success', 'Announcement updated successfully!');
    }

    public function deleteAnnouncement(Announcement $announcement)
    {
        abort_if(!Auth::user()->can('announcement_create'), 403);
        $announcement->delete();

        return response()->json(['success' => 'Announcement deleted successfully!']);
    }

    // ================= EVENTS =================
    public function events(Request $request)
    {
        abort_if(!Auth::user()->can('event_view'), 403);

        if ($request->ajax()) {
            $events = Event::latest()->with('creator');

            return DataTables::of($events)
                ->addIndexColumn()
                ->addColumn('description', fn($row) => Str::limit($row->message, 50))
                ->addColumn('event_date', fn($row) => \Carbon\Carbon::parse($row->event_date)->format('d M Y'))
                ->addColumn('event_time', fn($row) => $row->event_time ?? '-')
                ->addColumn(
                    'send_email',
                    fn($row) =>
                    $row->send_email
                        ? '<span class="badge bg-success">Yes</span>'
                        : '<span class="badge bg-secondary">No</span>'
                )
                ->addColumn(
                    'send_sms',
                    fn($row) =>
                    $row->send_sms
                        ? '<span class="badge bg-success">Yes</span>'
                        : '<span class="badge bg-secondary">No</span>'
                )
                ->addColumn(
                    'is_active',
                    fn($row) =>
                    $row->is_active
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>'
                )
                ->addColumn('creator', fn($row) => $row->creator?->name ?? 'System')
                ->addColumn('created_at', fn($row) => $row->created_at?->format('d M Y'))
                ->addColumn('actions', function ($row) {
                    $edit = Auth::user()->can('event_create')
                        ? '<a href="' . route('backend.admin.communications.events.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>'
                        : '';

                    $delete = Auth::user()->can('event_create')
                        ? '<button data-id="' . $row->id . '" class="btn btn-sm btn-danger btn-delete">Delete</button>'
                        : '';

                    return $edit . ' ' . $delete;
                })
                ->rawColumns(['send_email', 'send_sms', 'is_active', 'actions'])
                ->make(true);
        }

        return view('backend.communication.events.index');
    }

    public function createEvent()
    {
        abort_if(!Auth::user()->can('event_create'), 403);
        return view('backend.communication.events.create');
    }

    public function storeEvent(Request $request)
    {
        abort_if(!Auth::user()->can('event_create'), 403);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'location'    => 'nullable|string|max:255',
            'event_date'  => 'required|date',
            'event_time'  => 'nullable',
            'send_email'  => 'required|boolean',
            'send_sms'    => 'required|boolean',
        ]);

        $event = Event::create([
            'title'       => $data['title'],
            'description' => $data['description'],
            'location'    => $data['location'],
            'event_date'  => $data['event_date'],
            'event_time'  => $data['event_time'],
            'send_email'  => $data['send_email'],
            'send_sms'    => $data['send_sms'],
            'is_active'   => true,
            'created_by'  => Auth::id(),
        ]);

        // Send Email
        if ($data['send_email']) {
            User::pluck('email')->each(function ($email) use ($event) {
                Mail::to($email)->queue(new GenericNotificationMail(
                    $event->title,
                    $event->description
                ));
            });
        }

        // Send SMS
        if ($data['send_sms']) {
            $smsMessage = $event->title . "\n\n"
                . "When: " . \Carbon\Carbon::parse($event->event_date)->format('d M Y')
                . ($event->event_time ? ' ' . $event->event_time : '') . "\n"
                . "Where: " . ($event->location ?? '-') . "\n\n"
                . $event->description . "\n\n"
                . "Powered by JM Innovatech Solution\n"
                . "https://jminnovatechsolution.co.ke/";

            User::whereNotNull('phone')->pluck('phone')->each(function ($phone) use ($smsMessage) {
                SendSmsJob::dispatch($phone, $smsMessage);
            });
        }

        return redirect()->route('backend.admin.communications.events')
            ->with('success', 'Event created successfully!');
    }

    public function editEvent(Event $event)
    {
        abort_if(!Auth::user()->can('event_create'), 403);
        return view('backend.communication.events.edit', compact('event'));
    }

    public function updateEvent(Request $request, Event $event)
    {
        abort_if(!Auth::user()->can('event_create'), 403);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'location'    => 'nullable|string|max:255',
            'event_date'  => 'required|date',
            'event_time'  => 'nullable',
            'send_email'  => 'required|boolean',
            'send_sms'    => 'required|boolean',
        ]);

        $event->update($data);

        // Send notifications again if enabled
        if ($data['send_email']) {
            User::pluck('email')->each(function ($email) use ($event) {
                Mail::to($email)->queue(new GenericNotificationMail(
                    $event->title,
                    $event->description
                ));
            });
        }

        // Send SMS
        if ($data['send_sms']) {
            $smsMessage = $event->title . "\n\n"
                . "When: " . \Carbon\Carbon::parse($event->event_date)->format('d M Y')
                . ($event->event_time ? ' ' . $event->event_time : '') . "\n"
                . "Where: " . ($event->location ?? '-') . "\n\n"
                . $event->description . "\n\n"
                . "Powered by JM Innovatech Solution\n"
                . "https://jminnovatechsolution.co.ke/";

            User::whereNotNull('phone')->pluck('phone')->each(function ($phone) use ($smsMessage) {
                SendSmsJob::dispatch($phone, $smsMessage);
            });
        }

        return redirect()->route('backend.admin.communications.events')
            ->with('success', 'Event updated successfully!');
    }

    public function deleteEvent(Event $event)
    {
        abort_if(!Auth::user()->can('event_create'), 403);
        $event->delete();

        return response()->json(['success' => 'Event deleted successfully!']);
    }
}