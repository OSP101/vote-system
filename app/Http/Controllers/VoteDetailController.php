<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Votes;
use App\Models\VotesDetail;


class VoteDetailController extends Controller
{
    function generateRandomNumber($length)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function index($id)
    {
        // $vote = Votes::findOrFail($id);
        // $isbn = $this->generateRandomNumber(10);
        // $vote->qrcode = $isbn;
        // $vote->save();

        $vote2 = Votes::findOrFail($id);
        $activeVotesDetails = VotesDetail::whereNull('deleted_at')
        ->where('id_votes', $id)
        ->get();

        return inertia('VoteDetail', [
            'vote' => $vote2,
            'activeVotesDetails' => $activeVotesDetails
        ]);
    }
}
