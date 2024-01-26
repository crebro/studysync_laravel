<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Space;
use Illuminate\Http\Request;
use Auth;

class SpacesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:spaces',
            'description' => 'required|string',
        ]);

        $space = new Space([
            'name' => $request->name,
            'description' => $request->description,
            'space_identifier' => \Str::random(10),
            'creator_id' => Auth::user()->id,
        ]);

        if ($space->save()) {
            return response()->json([
                'message' => 'Successfully created space!',
                'space' => $space,
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $space = Space::where('space_identifier', $id)->firstOrFail();
        return response()->json([
            'space' => $space,
        ], 200);
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'string|unique:spaces',
            'description' => 'string',
        ]);

        $space = Space::where('space_identifier', $id)->firstOrFail();
        $space->update($request->all());

        if ($space->save()) {
            return response()->json([
                'message' => 'Successfully updated space!',
                'space' => $space,
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $space = Space::where('space_identifier', $id)->firstOrFail();
        $space->delete();

        return response()->json([
            'message' => 'Successfully deleted space!',
        ], 201);
    }
}
