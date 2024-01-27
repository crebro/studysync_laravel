<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\QuestionBank;
use Illuminate\Http\Request;
use Auth;

class QuestionBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $id)
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
            'name' => 'required|string',
            'space_id' => 'required|integer',
        ]);

        $note = new Note([
            'name' => $request->name,
            'space_id' => $request->space_id,
            'creator_id' => Auth::user()->id,
        ]);

        if ($note->save()) {
            return response()->json([
                'message' => 'Successfully created Question Bank!',
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
        $questionBank = QuestionBank::where('id', $id)->firstOrFail();

        return ['question_bank' => $questionBank, 'questions' => $questionBank->questions];
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

        $question_bank = QuestionBank::where('id', $id)->firstOrFail();
        $question_bank->update($request->all());

        if ($question_bank->save()) {
            return response()->json([
                'message' => 'Successfully updated note!',
                'question_bank' => $question_bank
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
        $qb = QuestionBank::where('id', $id)->firstOrFail();
        $qb->delete();
        //
    }
}
