<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vote;
use App\Models\Candidate;

class VoteController extends Controller
{
    public function store(Request $request)
    {
        $voter = Auth::user();

        if ($voter->has_voted) {
            return response()->json(['error' => 'You have already voted.'], 403); // Respond with an error if they have voted
        }

        $selections = $request->input('selections');

        foreach ($selections as $position => $candidateId) {
            $candidate = Candidate::find($candidateId);

            if ($candidate) {
                Vote::create([
                    'voter_id' => $voter->id,
                    'candidate_id' => $candidate->id,
                ]);
            }
        }

        // Mark voter as voted
        $voter->has_voted = true;
        $voter->save();

    }
}
