<?php
namespace Modules\Event\Controllers\Api;

use App\Http\Controllers\Controller; // Add this line
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Event\Models\EventSession;
use App\Http\Resources\EventSessionResource;
use Illuminate\Support\Facades\DB;
class EventSessionController extends Controller
{

    public function __construct()
    {

    }

    public function index()
    {

        $eventSessions = EventSession::get();
        dd($eventSessions);die;
        if ($eventSessions->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'data' => [],
                'message' => 'No sessions found'
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'data' => $eventSessions
        ], 200);
    }

    public function show($id)
    {

        $eventSessions = EventSession::find($id);

        if (!$event) {
            return response()->json(['error' => 'Sessions not found'], 404);
        }

        return new EventSessionResource($eventSessions);
    }

    public function getAllSponsors($id)
    {

        $eventData = DB::table('events')
        ->join('event_sessions', 'events.id', '=', 'event_sessions.event_id')
        ->leftJoin('event_session_sponsors', 'event_sessions.id', '=', 'event_session_sponsors.event_session_id')
        ->leftJoin('event_sponsors', 'event_session_sponsors.event_sponsor_id', '=', 'event_sponsors.id')
        ->select(
            'events.id as event_id',
            'events.name as event_name',
            'event_sessions.id as session_id',
            'event_sessions.name as session_name',
            'event_session_sponsors.event_sponsor_id',
            'event_sponsors.name as sponsor_name',
            'event_sponsors.logo as sponsor_logo'
        )->where('events.id', $id)
        ->get();


            return response()->json([
                'data' => $eventData
            ], 200);

    }

    public function getAllMaterials($id)
    {

        $eventData = DB::table('events')
        ->join('event_sessions', 'events.id', '=', 'event_sessions.event_id')
        ->leftJoin('event_session_materials', 'event_sessions.id', '=', 'event_session_materials.event_session_id')
        ->select(
            'events.id as event_id',
            'events.name as event_name',
            'event_sessions.id as session_id',
            'event_sessions.name as session_name',
            'event_session_materials.id as material_id',
            'event_session_materials.material_name as material_name',
            'event_session_materials.material_description as material_description',
            'event_session_materials.file_path as material_file_path',
            'event_session_materials.file_type as material_file_type'

        )
        ->where('events.id', $id) // Optional: Filter by event ID
        ->get();



        return response()->json([
            'data' => $eventData
        ], 200);

    }


}
