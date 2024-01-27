<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Auth;

class NotesController extends Controller
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
            'title' => 'required|string',
            'space_id' => 'required|integer',
            'document' => 'required|file',
        ]);

        // generate a random string and append the extension of the file to it
        $document = \Str::random(10) . '.' . $request->file('document')->extension();
        // use this filename to save the file in the storage/app/public directory
        $request->file('document')->storeAs('public', $document);

        // the the location of the document and store it in location column

        $note = new Note([
            'title' => $request->title,
            'location' => $document,
            'space_id' => $request->space_id,
            'creator_id' => Auth::user()->id,
        ]);

        if ($note->save()) {
            return response()->json([
                'message' => 'Successfully created note!',
                'note' => $note,
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }

        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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
        //
        $this->validate($request, [
            'title' => 'string',
        ]);

        $note = Note::where('note_identifier', $id)->firstOrFail();
        $note->update($request->all());

        if ($note->save()) {
            return response()->json([
                'message' => 'Successfully updated note!',
                'note' => $note,
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $note = Note::where('note_identifier', $id)->firstOrFail();
        $note->delete();
        //
    }

    public function annotateNote(string $uuid)
    {
        $note = Note::where('note_identifier', $uuid)->firstOrFail();
        $annotations = $note->annotations;
        return response()->json([
            'annotations' => $annotations,
        ], 200);
    }
}
