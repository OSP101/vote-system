<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Votes;
use App\Models\VotesDetail;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VotesController extends Controller
{
    public function findvotes()
    {

        $votes = Votes::where('id_user', Auth::id())->get();

        $votesWithCount = $votes->map(function ($vote) {
            $count = DB::table('votes_enrolls')
                ->where('id_votes', $vote->id)
                ->count();

            $vote->count = $count;
            return $vote;
        });

        return Inertia::render('Dashboard', ['items' => $votesWithCount]);
    }


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

    public function store(Request $request)
    {
        $params = $request->all();
        $isbn = $this->generateRandomNumber(10);
        $data = [
            'name' => $params['name'],
            'id_user' => Auth::id(),
            'status' => 'disabled',
            'qrcode' => $isbn
        ];
        Votes::create($data);
        return redirect()->route('dashboard');
    }

    public function create(Request $request)
    {
        $params = $request->all();
        $data = [
            'name_choice' => $params['name_choice'],
            'id_votes' => $params['id_votes'],
            'description' => $params['description']
        ];

        VotesDetail::create($data);

        return redirect()->route('vote.detail', ['id' => $params['id_votes']]);
    }

    public function destroy($id)
    {
        $voteDetail = VotesDetail::findOrFail($id);
        $voteDetail->delete();

        // หรือใช้การลบจริง
        // $voteDetail->forceDelete();

        return redirect()->route('vote.detail', ['id' => $voteDetail->id_votes])->with('success', 'Vote detail deleted successfully.');
    }

    public function change($id)
    {
        // ค้นหา vote ตาม ID
        $vote = Votes::findOrFail($id);

        // เปลี่ยนสถานะ
        if ($vote->status == 'active') {
            $vote->status = 'disabled';
        } else {
            $vote->status = 'active';
        }

        // บันทึกการเปลี่ยนแปลง
        $vote->save();

        // Redirect กลับไปยังหน้าเดิมหรือหน้าอื่นๆ ตามที่ต้องการ
        return redirect()->route('vote.detail', ['id' => $id])->with('success', 'Vote detail deleted successfully.');
    }
}
