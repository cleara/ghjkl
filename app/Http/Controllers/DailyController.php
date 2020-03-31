<?php

namespace App\Http\Controllers;
use Auth;//tambah
use Illuminate\Http\Request;

class DailyController extends Controller
{
    public function daily() {
        $data = "Data All Daily Scrum";
        return response()->json($data, 200);
    }

    public function dailyAuth(){
        $data ="Welcome" . Auth::user()->Firstname;
        return response()->json($data, 200);
    }
}
