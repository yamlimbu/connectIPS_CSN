<?php

namespace Modules\Event\Controllers\Api;

use App\Http\Controllers\Controller; // Add this line
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\EventResource;
use Modules\Event\Repositories\EventRepository;
use App\Http\Resources\EventCategoryResource;
use Modules\Event\Repositories\EventCategoryRepository;
use App\Http\Resources\EventMainResource;

use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

    public function __construct(
        EventRepository $event,
        EventCategoryRepository $eventcategory,
    )
    {
        $this->event = $event;
        $this->eventcategory = $eventcategory;
        //$this->middleware('auth.token'); // Assuming you are using the 'auth:api' middleware

    }

    public function index()
    {
        $events =$this->event->get();
        if ($events->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No events found',
                'data' => []

            ], 200);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Events retrieved successfully',
            'data' => $events
        ], 200);
    }

    public function show($id)
    {
        {
            $event = $this->event->getEventWithRelations($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Event retrieved successfully',
                'data' => new EventResource($event),

            ], 200);
        }
    }

    public function getFeaturedEvents()
    {
        $featuredEvents = $this->event->getFeaturedEvents();

        return response()->json([
            'status' => 'success',
            'message' => 'Featured events retrieved successfully',
            'data' => $featuredEvents
        ], 200);
    }



    public function getParticipants($id)
    {
            $participants = DB::table('event_registrations')
            ->where('event_id', $id)
            ->get();


        return response()->json([
            'status' => 'success',
            'message' => 'Featured events retrieved successfully',
            'data' => $participants
        ], 200);
    }

    public function getTickets($id){
        $event = $this->event->getEventWithCategoryTickets($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Event tickets retrieved successfully',
            'data' => new EventMainResource($event),

        ], 200);


    }

}
