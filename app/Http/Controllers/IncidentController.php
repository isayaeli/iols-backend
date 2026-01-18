<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncidentController extends Controller
{
    /**
     * Get all incidents
     */
    public function index(): JsonResponse
    {
        $incidents = Incident::with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($incident) {
                return [
                    'id' => $incident->id,
                    'title' => $incident->title,
                    'status' => $incident->status,
                    'reported_by' => $incident->user->name ?? 'System',
                    'created_on' => $incident->created_at->toDateString(),
                ];
            });

        return response()->json($incidents);
    }

    /**
     * Get incident statistics by status
     */
    public function stats(): JsonResponse
    {
        $stats = Incident::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->map(function ($row) {
                return [
                    'label' => $row->status,
                    'count' => $row->count,
                ];
            });

        return response()->json($stats);
    }


        /**
     * Get a single incident by ID
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $incident = \App\Models\Incident::with('user')->find($id);

        if (!$incident) {
            return response()->json([
                'message' => 'Incident not found'
            ], 404);
        }

        return response()->json([
            'id' => $incident->id,
            'title' => $incident->title,
            'status' => $incident->status,
            'reported_by' => $incident->user->name ?? 'System',
            'created_on' => $incident->created_at->toDateString(),
            'description' => $incident->description,
        ]);
    }

    // Create a new incident
   public function store(Request $request): JsonResponse
{
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|string|in:Open,Investigating,Resolved,Closed',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Create the incident
    $incident = Incident::create([
        'title' => $request->title,
        'description' => $request->description,
        'status' => $request->status,
        'user_id' => $request->user()->id, // reporter
    ]);

    // Load the user relationship so we can include the name
    $incident->load('user');

    // Return the incident with reported_by
    return response()->json([
        'id' => $incident->id,
        'title' => $incident->title,
        'status' => $incident->status,
        'description' => $incident->description,
        'reported_by' => $incident->user ? $incident->user->name : 'System',
        'assigned_to' => $incident->assignedOperator ? $incident->assignedOperator->name : null,
        'created_on' => $incident->created_at->toDateString(),
    ], 201);
}


    public function update(Request $request, Incident $incident): JsonResponse
{
    $validator = Validator::make($request->all(), [
        'title' => 'sometimes|required|string|max:255',
        'description' => 'sometimes|nullable|string',
        'status' => 'sometimes|required|string|in:Open,Investigating,Resolved,Closed',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $oldStatus = $incident->status;

    // Update the incident
    $incident->update($request->only(['title', 'description', 'status']));

    $newStatus = $incident->status;

    // Log activity only if status changed
    if ($oldStatus !== $newStatus) {
        \App\Models\ActivityLog::create([
            'incident' => $incident->toArray(), // store full incident data
            'user_id' => $request->user()->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'comment' => $request->get('comment', null),
        ]);
    }



    return response()->json([
        'id' => $incident->id,
        'title' => $incident->title,
        'status' => $incident->status,
        'description' => $incident->description,
        'reported_by' => $incident->user ? $incident->user->name : 'System',
        'created_on' => $incident->created_at->toDateString(),
    ]);
}



    // Delete an incident
    public function destroy(Incident $incident): JsonResponse
    {
        $incident->delete();

        return response()->json(['message' => 'Incident deleted successfully']);
    }



        /**
     * Assign an incident to an operator
     */
    public function assign(Request $request, Incident $incident): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'operator_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $operator = \App\Models\User::find($request->operator_id);

        // Check if user has operator role
        if ($operator->role !== 'operator') {
            return response()->json(['message' => 'User is not an operator'], 422);
        }

        $incident->assigned_to = $operator->id;
        $incident->save();

        return response()->json([
            'message' => 'Incident assigned successfully',
            'incident' => $incident,
        ]);
    }



}
