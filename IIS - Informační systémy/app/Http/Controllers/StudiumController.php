<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Kurz;
use App\Models\Hodnoceni;
use App\Models\Termindatum;
use Illuminate\Support\Facades\Auth;

class StudiumController extends Controller
{
    /**
     * Funkce pro získání veškerých kurzů, na které je student přihlášen.
     *
     * 
     */
    public function index()
    {
        if(Auth::user() != null){    
            $user = User::find(Auth::user()->osobni_cislo);
            return view('pages.studium')->with('kurzs', $user->studs)->withPivot('znamka', 'potvrzeni');
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * Funkce pro zobrazení detailu vybraného kurzu.
     *
     * @param  int  $id Identifikátor určující vybraný kurz, jehož detail se má zobrazit.
     */
    public function show($id)
    {
        if(Auth::user() != null){    
            $kurz = Kurz::find($id);
            $student = Student::where('id_kurz','LIKE', $id)->where('osobni_cislo', 'LIKE', Auth::user()->osobni_cislo)->get();
            if(!(empty($student[0]))){
                $bodys = $student[0]->termindatums;
                $cbody = 0;
                $pbody = 0;
                $zbody = 0;
                $body = 0;
            
                foreach($bodys as $bod){
                    if($bod->typ == 'cviceni'){
                        $cbody += $bod->pivot->hodnoceni;
                    }
                    if($bod->typ == 'projekt'){
                        $pbody += $bod->pivot->hodnoceni;
                    }
                    if($bod->typ == 'zkouska'){
                        if($zbody < $bod->pivot->hodnoceni){
                            $zbody = $bod->pivot->hodnoceni;
                        }
                    }
                }
                $body = $cbody + $pbody + $zbody;
                $znamka = " ";
                if ($body <= 49) {
                    $znamka = "F";
                } else if (49 < $body && $body < 60) {
                    $znamka = "E";
                } else if (59 < $body && $body < 70) {
                    $znamka = "D";
                } else if (69 < $body && $body < 80) {
                    $znamka = "C";
                } else if (79 < $body && $body < 90) {
                    $znamka = "B";
                } else if (89 < $body && $body < 101) {
                    $znamka = "A";
                } else{
                    $znamka = "X";
                }
                return view('pages.studiumdetail')->with('kurz', $kurz)->with('body', $body)->with('cbody', $cbody)->with('pbody', $pbody)->with('zbody', $zbody)->with('znamka', $znamka);
            }else{
                return view('pages.not_auth');
            }
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * Funkce pro zobrazení detailu cvičení vybraného kurzu.
     *
     * @param  int  $id Identifikátor určující vybraný kurz, jehož detail se má zobrazit.
     */
    public function show_cviceni($id){
        if(Auth::user() != null){    
            $kurz = Kurz::find($id);
            $student = Student::where('id_kurz','LIKE', $id)->where('osobni_cislo', 'LIKE', Auth::user()->osobni_cislo)->get();
            if(!(empty($student[0]))){
                $termins = $student[0]->termindatums;
                return view('pages.studiumcvicenidetail')->with('kurz', $kurz)->with('termins', $termins)->withPivot('hodnoceni');
            }else{
                return view('pages.not_auth');
            }
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * Funkce pro zobrazení detailu projektu vybraného kurzu.
     *
     * @param  int  $id Identifikátor určující vybraný kurz, jehož detail se má zobrazit.
     */
    public function show_projekt($id){
        if(Auth::user() != null){    
            $kurz = Kurz::find($id);
            $student = Student::where('id_kurz','LIKE', $id)->where('osobni_cislo', 'LIKE', Auth::user()->osobni_cislo)->get();
            if(!(empty($student[0]))){
                $termins = $student[0]->termindatums;
                return view('pages.studiumprojektdetail')->with('kurz', $kurz)->with('termins', $termins)->withPivot('hodnoceni');
            }else{
                return view('pages.not_auth');
            }
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * Funkce pro zobrazení detailu zkoušek vybraného kurzu.
     *
     * @param  int  $id Identifikátor určující vybraný kurz, jehož detail se má zobrazit.
     */
    public function show_zkouska($id){
        if(Auth::user() != null){    
            $kurz = Kurz::find($id);
            $student = Student::where('id_kurz','LIKE', $id)->where('osobni_cislo', 'LIKE', Auth::user()->osobni_cislo)->get();
            if(!(empty($student[0]))){
                $termins = $student[0]->termindatums;
                return view('pages.studiumzkouskadetail')->with('kurz', $kurz)->with('termins', $termins)->withPivot('hodnoceni');
            }else{
                return view('pages.not_auth');
            }
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * Funkce pro odstranění záznamu z tabulky students, kde id kurzu je to, z kterého se chce student odhlásit.
     *
     * @param  int  $id Identifikátor určující vybraný kurz, z něhož se chce uživatel odregistrovat.
     */
    public function destroy($id)
    {
        if(Auth::user() != null){    
            $kurz = Kurz::find($id);
            $student = Student::where('id_kurz','LIKE', $id)->where('osobni_cislo', 'LIKE', Auth::user()->osobni_cislo)->get();
            if(!(empty($student[0]))){
                Student::destroy($student[0]->id_student);
                return redirect('/studium');
            }else{
                return view('pages.not_auth');
            }
        }else{
            return view('pages.not_auth');
        }
    }
}
