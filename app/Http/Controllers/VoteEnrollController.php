<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Votes;
use App\Models\VotesEnroll;
use App\Models\VotesDetail;
use Illuminate\Support\Facades\DB;
class VoteEnrollController extends Controller
{
    public function index($id, $code)
    {
        $vote = Votes::findOrFail($id);
        $activeVotesDetails = VotesDetail::whereNull('deleted_at')
            ->where('id_votes', $id)
            ->get();
        if ($vote->qrcode != $code) {
            abort(404);
        }

        return inertia('VoteEnroll', [
            'vote' => $vote,
            'activeVotesDetails' => $activeVotesDetails
        ]);
    }

    public function enroll(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'id_votes' => 'required',
            'id_votes_detail' => 'required'
        ]);

        $params = $request->all();
        $data = [
            'student_id' => $params['student_id'],
            'id_votes' => $params['id_votes'],
            'id_votes_details' => $params['id_votes_detail']
        ];

        VotesEnroll::create($data);

        return redirect(url("/vote/{$params['id_votes']}/{$params['qrcode']}"))
            ->with('status', 'Vote submitted successfully!');
    }

    public function getVoteData($id)
    {
        $vote = Votes::findOrFail($id);
        $voteDetails = DB::table('votes_enrolls')
        ->join('votes_details', 'votes_enrolls.id_votes_details', '=', 'votes_details.id')
        ->where('votes_enrolls.id_votes', $id)
        ->whereNull('votes_details.deleted_at') 
        ->select('votes_details.name_choice', DB::raw('COUNT(votes_enrolls.id) as count'))
        ->groupBy('votes_details.name_choice')
        ->get();

    return response()->json($voteDetails);
    }
}
