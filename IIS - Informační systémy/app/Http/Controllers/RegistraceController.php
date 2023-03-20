<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Kurz;
use App\Models\Termin;
use App\Models\Hodnoceni;
use App\Models\StudentKurz;
use Illuminate\Support\Facades\Auth;

class RegistraceController extends Controller
{
    /**
     * Funkce pro zobrazení veškerých termínů, které jsou vypsány pro všechny kurzy, která má danný student vypsány.
     *
     * 
     */
    public function index()
    {
        if(Auth::user() != null){    
            $user = User::find(Auth::user()->osobni_cislo);
            $kurzs = $user->studs;
            $termin_id = array();
            foreach ($kurzs as $kurz) {
                if($kurz->pivot->potvrzeni == 1){
                    $termin = $kurz->termins;
                    foreach($termin as $term){
                        $termin_id[] = $term->id_termin;
                    }
                }
            }
            $termins = Termin::whereIn('id_termin', $termin_id)->get();
            $students = Student::where('osobni_cislo', $user->osobni_cislo)->get();
            $students_id = array();
            foreach($students as $student){
                if($student->potvrzeni == 1){
                    $students_id[] = $student->id_student;
                }
            }
            $stud_termins = StudentKurz::whereIn('id_student', $students_id)->get();
            $termins_array = array();
            foreach($stud_termins as $stud_termin){
                $termins_array[] = $stud_termin->id_termin;
            }
            $sortTermins = Termin::whereIn('id_termin', $termins_array)->get();
            $termins_count = StudentKurz::All();
            return view('pages.registrace')->with('kurzs', $kurzs)->withPivot('potvrzeni')->with('termins', $termins)->with('stud_termins', $stud_termins)->with('sortTermins', $sortTermins)->with('termins_count', $termins_count);    
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * Funkce pro zobrazení vyfilrovaných termínů, které jsou vypsány pro všechny kurzy, která má danný student vypsány.
     *
     * @param  \Illuminate\Http\Request  $request Získané informace z formuláře, vybraný kurz a typ termínu.
     */
    public function index_filtered(Request $request)
    {
        if(Auth::user() != null){    
            $user = User::find(Auth::user()->osobni_cislo);
            $kurzs = $user->studs;
            $termin_id = array();
            if(($request->input('kurz') == "Všechny") && ($request->input('typ') == "Všechny")){
                foreach ($kurzs as $kurz) {
                    if($kurz->pivot->potvrzeni == 1){
                        $termin = $kurz->termins;
                        foreach($termin as $term){
                            $termin_id[] = $term->id_termin;
                        }
                    }
                }
            }
            if(($request->input('kurz') == "Všechny") && ($request->input('typ') != "Všechny")){
                foreach ($kurzs as $kurz) {
                    if($kurz->pivot->potvrzeni == 1){
                        $termin = $kurz->termins;
                        foreach($termin as $term){
                            if($term->typ == $request->input('typ')){
                                $termin_id[] = $term->id_termin;
                            }
                        }
                    }
                }
            }
            if(($request->input('kurz') != "Všechny") && ($request->input('typ') == "Všechny")){
                foreach ($kurzs as $kurz) {
                    if($kurz->zkratka == $request->input('kurz')){
                        $termin = $kurz->termins;
                        foreach($termin as $term){
                            $termin_id[] = $term->id_termin;
                        }
                    }
                }
            }
            if(($request->input('kurz') != "Všechny") && ($request->input('typ') != "Všechny")){
                foreach ($kurzs as $kurz) {
                    if($kurz->zkratka == $request->input('kurz')){
                        $termin = $kurz->termins;
                        foreach($termin as $term){
                            if($term->typ == $request->input('typ')){
                                $termin_id[] = $term->id_termin;
                            }
                        }
                    }
                }
            }
            $termins = Termin::whereIn('id_termin', $termin_id)->get();
            $students = Student::where('osobni_cislo', $user->osobni_cislo)->get();
            $students_id = array();
            foreach($students as $student){
                if($student->potvrzeni == 1){
                    $students_id[] = $student->id_student;
                }
            }
            $stud_termins = StudentKurz::whereIn('id_student', $students_id)->get();
            $termins_array = array();
            foreach($stud_termins as $stud_termin){
                $termins_array[] = $stud_termin->id_termin;
            }
            $sortTermins = Termin::whereIn('id_termin', $termins_array)->get();
            $termins_count = StudentKurz::All();
            return view('pages.registracefiltr')->with('termins', $termins)->with('kurzs', $kurzs)->with('stud_termins', $stud_termins)->with('sortTermins', $sortTermins)->with('termins_count', $termins_count);
        }else{
            return view('pages.not_auth');
        } 
    }


    /**
     * Funkce pro vytváření nového záznamu do tabulky studentKurzs v případě, jestliže se chce student na danný termín přihlásit.
     * Taktéž předpřipravý záznamy ve vztahové tabulce hodnocenis, kde jejich množství je dáno počtem záznamů v tabulce termindatums pro danný termín. 
     *
     * @param int $id Identifikátor termínu, na který se chce student přihlásit.
     */
    public function create($id)
    {
        if(Auth::user() != null){    
            $user = User::find(Auth::user()->osobni_cislo);
            $termins = Termin::Find($id);
            if(empty($termins)){
                return view('pages.not_auth');
            }
            $student = Student::where('osobni_cislo', $user->osobni_cislo)->where('id_kurz', $termins->id_kurz)->get();
            if(empty($student[0])){
                return view('pages.not_auth');
            }
            $stud_termins = StudentKurz::where('id_student', $student[0]->id_student)->get();
            $termins_array = array();
            foreach($stud_termins as $stud_termin){
                if($termins->id_termin == $stud_termin->id_termin){
                    return view('pages.not_auth');
                }
                $termin = Termin::Find($stud_termin->id_termin);
                if($termins->typ == $termin->typ && $termins->typ != "zkouska"){
                    return view('pages.not_auth');
                }
            }
            $termins_count = StudentKurz::where('id_termin', $id)->get();
            $count = 0;
            foreach($termins_count as $termin_count){
                $count++;
            }
            if($count >= $termins->kapacita){
                return view('pages.not_auth');
            }
            $student_kurz = new StudentKurz;
            $student_kurz->id_student = $student[0]->id_student;
            $student_kurz->id_termin = $id;
            $student_kurz->save();
            $termindatums = $termins->termindatums;
            foreach($termindatums as $termindatum){
                $hodnoceni = new Hodnoceni;
                $hodnoceni->hodnoceni = 0;
                $hodnoceni->id_student = $student[0]->id_student;
                $hodnoceni->id_termindatum = $termindatum->id_termindatum;
                $hodnoceni->save();
            }
            return redirect('/registrace');  
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * Funce pro mazání záznamu z tabulky studentKurzs v případě, když se student z termínu odhlásí.
     * Taktéž se smazají veškeré záznamy z tabulky hodnocenis související s danný odhlášeným termínem. 
     *
     * @param  int  $id Identifikátor určující termín, z kterého se chce student odhlásit.
     */
    public function destroy($id)
    {
        if(Auth::user() != null){    
            $user = User::find(Auth::user()->osobni_cislo);
            $termins = Termin::Find($id);
            if(empty($termins)){
                return view('pages.not_auth');
            }
            $student = Student::where('osobni_cislo', $user->osobni_cislo)->where('id_kurz', $termins->id_kurz)->get();
            if(empty($student[0])){
                return view('pages.not_auth');
            }
            $termin = StudentKurz::where('id_termin', $id)->where('id_student', $student[0]->id_student)->get();
            if(empty($termin[0])){
                return view('pages.not_auth');
            }
            StudentKurz::destroy($termin[0]->id);
            $termindatums = $termins->termindatums;
            foreach($termindatums as $termindatum){
                $hodnoceni = Hodnoceni::where('id_termindatum', $termindatum->id_termindatum)->where('id_student', $student[0]->id_student)->get();
                Hodnoceni::destroy($hodnoceni[0]->id);
            }        
            return redirect('/registrace');
        }else{
            return view('pages.not_auth');
        }
    }
}
