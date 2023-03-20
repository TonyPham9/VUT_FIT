<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Termin;
use App\Models\StudentKurz;
use Illuminate\Support\Facades\Auth;

class RozvrhController extends Controller
{
    /**
     * Zobrazí všechny nekolizní termíny(kromě zkoušky) co má student zaregistrovaný.
     *
     */
    public function index()
    {
        if(Auth::user() != null){    
            $user = User::find(Auth::user()->osobni_cislo);
        $student = Student::whereIn('osobni_cislo', [$user->osobni_cislo])->get();
        $student_id = array(); //id studentro pro terminy
        foreach ($student as $itr) {
            $student_id[] = $itr->id_student;
        }
        $result2 = StudentKurz::whereIn('id_student', $student_id)->get();
        $pole3 = array();
        foreach ($result2 as $itr) {
            $pole3[] = $itr->id_termin;
        }
        $result3 = Termin::whereIn('id_termin', $pole3)->get();
        return view('pages.rozvrh')->with('termins', $result3);
        }else{
            return view('pages.not_auth');
        }
    }

}
