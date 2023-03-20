<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Kurz;
use Illuminate\Support\Facades\Auth;

class KurzsController extends Controller
{
    /**
     * Zobrazí view se všemi potvrzenými kurzy od admina
     *
     */
    public function index()
    {
        $kurzs = Kurz::All();
        $stud_all = Student::All();
        if (Auth::check() != null) {
            $user = User::find(Auth::user()->osobni_cislo);
            $termins = $user->lektors;
            $kurz_array = array();
            foreach($termins as $termin){
                $kurz_array[] = $termin->id_kurz;
            }
            return view('pages.kurz')->with('kurzs', $kurzs)->with('stud_kurzs', $user->studs)->with('stud_all', $stud_all)->with('user', $user)->with('lektors', $kurz_array);
        } else {
            return view('pages.kurz')->with('kurzs', $kurzs);
        }
        
    }

    /**
     * Odhlásí studenta z kurzu.
     *
     * @param  int  $id - id kurz z kterýho se odhlašuji
     */
    public function rm_kurz_student($id){
        if (Auth::check() != null) {
        $user = User::find(Auth::user()->osobni_cislo);
        $all_kurzs = Kurz::All();
        foreach ($all_kurzs as $cmp) {
            if($cmp->garant == $user->osobni_cislo && $cmp->id_kurz == $id) {
                return view('pages.not_auth');
            }
        }
        $termins = $user->lektors;
        $kurz_array = array();
        foreach($termins as $termin){
            $kurz_array[] = $termin->id_kurz;
        }
        foreach($kurz_array as $lektor) {
            if($lektor == $id) {
                return view('pages.not_auth');
            }
        }
        if(empty(Kurz::find($id))) {
            return view('pages.not_auth');
        }
        $student = Student::where('id_kurz','LIKE', $id)->where('osobni_cislo', 'LIKE', Auth::user()->osobni_cislo)->get();
        Student::destroy($student);
        return redirect('/kurz');
        } else {
            return view('pages.not_auth');
        }
    }

    /**
     * Přihlásí studenta na kurz.
     *
     * @param  int  $id - id kurzu na který se uživatel přihlašuje
     * @param  int  $potvrzeni - 1 nebo 0 zda garant musí přihlášení potvrdit
     */
    public function add_kurz_student($id, $potvrzeni){
        if (Auth::check() != null) {
        $user = User::find(Auth::user()->osobni_cislo);
        $all_kurzs = Kurz::All();
        foreach ($all_kurzs as $cmp) {
            if($cmp->garant == $user->osobni_cislo && $cmp->id_kurz == $id) {
                return view('pages.not_auth');
            }
        }
        $termins = $user->lektors;
        $kurz_array = array();
        foreach($termins as $termin){
            $kurz_array[] = $termin->id_kurz;
        }
        foreach($kurz_array as $lektor) {
            if($lektor == $id) {
                return view('pages.not_auth');
            }
        }
        if(empty(Kurz::find($id))) {
            return view('pages.not_auth');
        }
        $students_count = Kurz::where('id_kurz', $id)->get();
        $count = 0;
        foreach($students_count as $student_count){
            $count++;
        }
        if($count >= Kurz::find($id)->pocet_mist){
            return view('pages.not_auth');
        }

        $kurz = Student::where('osobni_cislo', $user->osobni_cislo)->where('id_kurz', $id)->get();
        if(!(empty($kurz[0]))) {
            return view('pages.not_auth');
        }

        $student = new Student;
        $student->potvrzeni = $potvrzeni;
        $student->osobni_cislo = $user->osobni_cislo;
        $student->id_kurz = $id;
        $student->save();
        return redirect('/kurz');
        } else {
            return view('pages.not_auth');
        }
    }
}
