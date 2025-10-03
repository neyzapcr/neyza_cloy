<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $name = "Neyza Shafalika";
        $tanggal_lahir = Carbon::parse('2006-03-08');
        $my_age = $tanggal_lahir->age;
        $hobbies = ["Membaca", "Bersepeda", "Coding", "Memasak", "Traveling"];

        $tgl_harus_wisuda = Carbon::parse('2028-10-01');
        $time_to_study_left = Carbon::now()->diffInDays($tgl_harus_wisuda, false);
        if ($time_to_study_left < 0) {
         $time_to_study_left = 0;
        }
        $current_semester = 3;
        $future_goal = "Menjadi orang yang bisa";

        if ($current_semester <= 3) {
            $info = "Masih Awal, Kejar TAK";
        } else {
            $info = "Jangan main-main, kurang-kurangi main game!";
        }

        $data = [
            'name' => $name,
            'my_age' => $my_age,
            'hobbies' => $hobbies,
            'tgl_harus_wisuda' => $tgl_harus_wisuda,
            'time_to_study_left' => $time_to_study_left,
            'current_semester' => $current_semester,
            'info' => $info,
            'future_goal' => $future_goal,
        ];

        return view('pegawai', $data);  
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
