<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\QuestionBank;
use App\Models\UserFlashCardsPractice;
use App\Models\UserStreak;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use DB;

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

    public function availableQuestionBanks()
    {
        $spacesIds = Auth::user()->spaces->pluck('id');

        $questionBanks = QuestionBank::whereIn('space_id', $spacesIds)->get();

        return response()->json([
            'question_banks' => $questionBanks
        ], 200);
    }

    public function markSession(string $id)
    {
        $questionBank = QuestionBank::where('id', $id)->firstOrFail();
        if (!$questionBank) {
            return response()->json([
                'message' => 'Question bank not found!'
            ], 404);
        }

        $userFlashCardPractice = UserFlashCardsPractice::create([
            'user_id' => Auth::user()->id,
            'question_bank_id' => $questionBank->id,
            'last_practiced_at' => now()
        ]);

        $laststreak = UserStreak::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->first();
        $diff = -1;

        if ($laststreak) {
            $now = Carbon::now();
            $laststreakdate = Carbon::parse($laststreak->datetime);
            $diff = $now->diffInDays($laststreakdate);
        }

        if ($diff == 1) {
            $laststreak->update([
                'streak' => $laststreak->streak + 1,
                'last_practiced_at' => now()
            ]);
        } else if ($diff == 0) {
            $laststreak->update([
                'last_practiced_at' => now()
            ]);
        } else if ($diff != 1) {
            UserStreak::create([
                'user_id' => Auth::user()->id,
                'streak' => 1,
                'last_practiced_at' => now()
            ]);
        }

        return response()->json([
            'message' => 'Successfully Completed Practice Session',
        ], 201);
    }

    public function streakDetails()
    {
        $weeklydetails = DB::table('user_flashcards_practice')
            ->select(DB::raw('DATE(last_practiced_at) as date'), DB::raw('count(*) as streak'))
            ->where('user_id', Auth::user()->id)
            ->whereBetween('last_practiced_at', [Carbon::now()->startOfWeek(), Carbon::now()])
            ->groupBy(DB::raw('DATE(last_practiced_at)'))
            ->get()->map(function ($item) {
                return Carbon::parse($item->date)->dayOfWeek;
            });

        $laststreak = UserStreak::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->first();
        $streak = 0;

        if ($laststreak) {
            $now = Carbon::now();
            $laststreakdate = Carbon::parse($laststreak->datetime);
            $diff = $now->diffInDays($laststreakdate);
        }
        if ($diff > 1) {
            $streak = 0;
        } else {
            $streak = $laststreak->streak;
        }

        return response()->json([
            'weeklydetails' => $weeklydetails,
            'streak' => $streak
        ], 200);
        // $userFlashCardPractice = UserFlashCardsPractice::where('user_id', Auth::user()->id)->whereBetween('last_practiced_at', [Carbon::now()->startOfWeek(), Carbon::now()])->groupBy(DB::raw('DATE(last_practiced_at)'))->get();
    }
}
