<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\OpsHelpers;
use App\Support\Admin\Uploads;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/** admin/manage-events, edit-event (create + edit), delete-event */
class EventsController extends AdminController
{
    use OpsHelpers;

    /** GET /admin/manage-events */
    public function index()
    {
        $events = [];
        try {
            DB::select('SELECT 1 FROM events LIMIT 1');
            $events = $this->rows(DB::table('events')->orderByDesc('event_date')->orderByDesc('id')->get());
        } catch (QueryException $e) {
            // Table may not exist
        }

        return $this->page('admin.events.index', 'Manage Events', 'manage-events', null, [
            'events' => $events,
            'event_message' => $this->pull('event_message'),
        ]);
    }

    /** GET|POST /admin/edit-event[?id=] */
    public function edit(Request $request)
    {
        $editing = false;
        $event = null;
        try {
            DB::select('SELECT 1 FROM events LIMIT 1');
        } catch (QueryException $e) {
            return $this->plain('Events table not found. Run install_events_table.sql in phpMyAdmin first.');
        }

        if ($request->query->has('id')) {
            $editing = true;
            $event = $this->row(DB::table('events')->where('id', (int) $this->strQuery($request, 'id'))->first());
            if (! $event) {
                return $this->plain('Event not found.');
            }
        }

        $errorMessage = '';

        if ($request->isMethod('post')) {
            $title = trim($this->post($request, 'title'));
            $slug = trim($this->post($request, 'slug'));
            $excerpt = trim($this->post($request, 'excerpt'));
            $content = $this->post($request, 'content');
            $eventDate = trim($this->post($request, 'event_date'));
            $eventEndDate = ! empty($request->input('event_end_date')) ? trim($this->post($request, 'event_end_date')) : null;
            $eventTimeStart = ! empty($request->input('event_time_start')) ? trim($this->post($request, 'event_time_start')) : null;
            $eventTimeEnd = ! empty($request->input('event_time_end')) ? trim($this->post($request, 'event_time_end')) : null;
            $venue = trim($this->post($request, 'venue'));
            // Whitelist status (audit #21).
            $postedStatus = $request->input('status');
            $status = in_array($postedStatus, ['draft', 'published'], true) ? $postedStatus : 'draft';

            if (empty($slug)) {
                $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));
            }
            if (empty($eventDate)) {
                $eventDate = date('Y-m-d');
            }

            // Validated (extension + real MIME) like every other upload path in the app.
            $featuredImage = $editing ? ($event['featured_image'] ?? '') : '';
            try {
                $uploaded = Uploads::save($request, 'featured_image', 'events', Uploads::imageExt(), Uploads::imageMime(), 5 * 1024 * 1024);
                if ($uploaded !== null) {
                    $featuredImage = $uploaded;
                }
            } catch (\RuntimeException $e) {
                $errorMessage = $e->getMessage();
            }

            $data = [
                'title' => $title, 'slug' => $slug, 'excerpt' => $excerpt, 'content' => $content,
                'event_date' => $eventDate, 'event_end_date' => $eventEndDate,
                'event_time_start' => $eventTimeStart, 'event_time_end' => $eventTimeEnd,
                'venue' => $venue, 'featured_image' => $featuredImage, 'status' => $status,
            ];
            try {
                if ($editing) {
                    DB::table('events')->where('id', $event['id'])->update($data);
                    session()->put('event_message', 'Event updated.');
                } else {
                    DB::table('events')->insert($data);
                    session()->put('event_message', 'Event created.');
                }
                return redirect('/admin/manage-events');
            } catch (QueryException $e) {
                $errorMessage = $e->getCode() == 23000 ? 'Slug already exists.' : ($e->getPrevious()?->getMessage() ?? $e->getMessage());
            }
        }

        return $this->page('admin.events.edit', $editing ? 'Edit Event' : 'Add Event', 'manage-events', null, [
            'editing' => $editing,
            'event' => $event,
            'error_message' => $errorMessage,
            'post' => $request->isMethod('post') ? $request->post() : [],
        ]);
    }

    /** POST /admin/delete-event */
    public function destroy(Request $request)
    {
        $role = DB::table('users')->where('id', $request->session()->get('user_id'))->value('role');
        if (! $role || $role !== 'admin') {
            return $this->plain('Access denied.', 403);
        }

        // Deletion must be a POST (with a valid CSRF token, enforced by Laravel) (audit #20).
        if (! $request->isMethod('post')) {
            session()->put('event_message', 'Invalid or expired request. Please use the Delete button.');
            // The original answered 403 *with* a Location header.
            return response('', 403)->header('Location', url('/admin/manage-events'));
        }

        $id = (int) $this->post($request, 'id', '0');
        if ($id <= 0) {
            return redirect('/admin/manage-events');
        }

        try {
            DB::table('events')->where('id', $id)->delete();
            session()->put('event_message', 'Event deleted.');
        } catch (QueryException $e) {
            Log::error('delete-event: ' . $e->getMessage());
            session()->put('event_message', 'Error deleting event. Please try again.');
        }
        return redirect('/admin/manage-events');
    }
}
