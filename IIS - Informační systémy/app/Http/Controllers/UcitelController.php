<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kurz;
use App\Models\Termin;
use Illuminate\Support\Facades\Auth;
use App\Models\Lektor;
use App\Models\Student;
use App\Models\Termindatum;
use App\Models\Hodnoceni;
use App\Models\Mistnost;

class UcitelController extends Controller
{
    /**
     * zobrazení domovské stránky.
     *
     */
    public function index()
    {
        if(Auth::user() != null){    
            return view('pages.ucitel');
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * vytvoření kurzu.
     *
     * @param  \Illuminate\Http\Request  $request data z formuláře
     */
    public function create(Request $request)
    {
        $this->validate($request, ['zkratka' => 'required', 
        'nazev' => 'required',
        'pocet_mist' => 'required',
        'kredity' => 'required',
        'typ' => 'required',
        'jazyk' => 'required',
        'zakonceni' => 'required',
        'cena' => 'required',
        'auto' => 'required']);
        if(Auth::user() != null){   
            $kurz = new Kurz;
            $kurz->zkratka = $request->input('zkratka');
            $kurz->nazev = $request->input('nazev');
            $kurz->pocet_mist = $request->input('pocet_mist');
            $kurz->kredity = $request->input('kredity');
            $kurz->typ = $request->input('typ');
            $kurz->jazyk = $request->input('jazyk');
            $kurz->zpusob_zakonceni = $request->input('zakonceni');
            $kurz->popis = $request->input('popis');
            $kurz->cena = $request->input('cena');
            $kurz->schvaleno = 0;
            $kurz->garant = Auth::user()->osobni_cislo;
            $kurz->auto_add = $request->input('auto');
            $kurz->save();
        return redirect('/');
        }else{
            return view('pages.not_auth');
        }
    }

    
    /**
     * zobrazit termíny pro kurz.
     *
     * @param  int  $id id kurzu
     */
    public function terminy($id){
        $kurz = Kurz::find($id);
        $user = Auth::user();
        $mistnost = Mistnost::all();
        if($user != null && $user->osobni_cislo == $kurz->garant){
            return view('pages.terminy')->with('termins', $kurz->termins)->with('id',$id)->with('kurz',$kurz)->with('mistnosts',$mistnost);
        }else{
            return view('pages.not_auth');
        }
        
    }

    /**
     * zobrazit kurzy garanta.
     *
     */
    public function garant(){
        if(Auth::user() != null){
            $kurzs = User::find(Auth::user()->osobni_cislo);
            return view('pages.garant')->with('kurzs', $kurzs->kurzs);
        }else{
            return view('pages.not_auth');
        }
        
    }

    /**
     * zobrazit termíny lektora.
     *
     */
    public function lektor(){
        if(Auth::user() != null){
            $kurzs = User::find(Auth::user()->osobni_cislo);
            return view('pages.lektor')->with('termins', $kurzs->lektors);
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * zobrazit rozvrh lektora.
     *
     */
    public function lektor_rozvrh(){
        if(Auth::user() != null){
            $kurzs = User::find(Auth::user()->osobni_cislo);
            return view('pages.lektor_rozvrh')->with('termins', $kurzs->lektors);
        }else{
            return view('pages.not_auth');
        }
    }
    /**
     * zobrazit stránku s formulářem pro přidání lektora.
     *
     */
    public function add_kurz(){
        return view('pages.add_kurz');
    }

    /**
     * vytvořit nový termín pro kurz, přidat vytvořenému termínu lektora.
     *
     */
    public function create_termin(Request $request){
        $user = Auth::user();
        if(User::where('email', 'LIKE', $request->input('lektor'))->first() == null){
            echo("enter valid user");
        }else   if(Mistnost::find($request->input('mistnost')) == null){
            echo("enter valid mistnost");
        }else{
            if($user != null){
                if(Kurz::find($request->input('id_kurzu'))->garant == $user->osobni_cislo){
                    $this->validate($request, [
                    'nazev' => 'required',
                    'typ' => 'required',
                    'od' => 'required',
                    'do' => 'required',
                    'kapacita' => 'required',
                    'den' => 'required',
                    'skupina' => 'required',
                    'id_kurzu' => 'required',
                    'mistnost' => 'required',
                    'lektor' => 'required']);
                    $termin = new Termin;
                    $termin->nazev = $request->input('nazev');
                    $termin->typ = $request->input('typ');
                    $termin->popis = $request->input('popis');
                    $termin->od = $request->input('od');
                    $termin->do = $request->input('do');
                    $termin->kapacita = $request->input('kapacita');
                    $termin->den = $request->input('den');
                    $termin->skupina = $request->input('skupina');
                    $termin->id_kurz = $request->input('id_kurzu');
                    $termin->id_mistnost = $request->input('mistnost');
                    $termin->save();
                    $lektor = new Lektor;
                    $lektor->id_termin = $termin->id_termin;
                    $id_lektor = User::where('email', 'LIKE', $request->input('lektor'))->get();
                    $lektor->osobni_cislo = $id_lektor[0]->osobni_cislo;
                    $lektor->save();
                    return redirect('/');
                }else{
                    return view('pages.not_auth');
                }
            }else{
                return view('pages.not_auth');
            }
        }
    }
    
    /**
     * odstranit termín.
     *
     * @param  int  $id id termínu
     */
    public function rm_termin($id){
        $user = Auth::user();
        if($user != null){
            if(Kurz::find(Termin::find($id)->id_kurz)->garant == $user->osobni_cislo){
                Termin::destroy($id);
                return redirect('/');
            }else{
                return view('pages.not_auth');
            }
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * zobrazit studenty termínu.
     *
     * @param  int  $id id termínu
     */
    public function student_termin($id){
        $user = Auth::user();
        $termin = Termin::find($id);
        $lektors = $termin->lektors;
        $found = 0;
        foreach($lektors as $lektor){
            if($lektor->osobni_cislo == $user->osobni_cislo){
                $found = 1;
            }
        }
        if($user != null){
            if(Kurz::find(Termin::find($id)->id_kurz)->garant == $user->osobni_cislo || $found == 1){
                return view('pages.stud_kurzu')->with('students',$termin->students)->with('datums',$termin->termindatums);
            }else{
                return view('pages.not_auth');
            }
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * zobrazit formulář pro úpravu kurzu.
     *
     * @param  int  $id id kurzu
     */
    public function upravit_kurz($id){
        $kurz = Kurz::find($id);
        $user = Auth::user();
        if($user != null && $user->osobni_cislo == $kurz->garant){
            return view('pages.upravit_kurz')->with('kurz',$kurz);
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * upravit kurz.
     *
     * @param  int  $id id termínu
     * @param  \Illuminate\Http\Request  $request data z formuláře
     */
    public function kurz_update(Request $request){
        $user = Auth::user();
        $kurz = Kurz::find($request->input('id_kurz'));
        if($user != null && $user->osobni_cislo == $kurz->garant){
            $kurz->pocet_mist = $request->input('pocet_mist');
            $kurz->kredity = $request->input('kredity');
            $kurz->typ = $request->input('typ');
            $kurz->jazyk = $request->input('jazyk');
            $kurz->zpusob_zakonceni = $request->input('zakonceni');
            $kurz->popis = $request->input('popis');
            $kurz->cena = $request->input('cena');
            $kurz->save();
            return redirect('/');
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * zobrazit data termínu, formulář pro přidání data pro termín.
     *
     * @param  int  $id id termínu
     */
    public function termin_datum($id){
        $termindatum = Termindatum::where('id_termin','LIKE',$id)->get();
        $termin = Termin::find($id);
        $kurz = Kurz::find($termin->id_kurz);
        $user = Auth::user();
        if($user != null && $user->osobni_cislo == $kurz->garant){
            return view('pages.termin_datum')->with('termins',$termindatum)->with('term',$termin);
        }else{
            return view('pages.not_auth');
        }
        
    }

    /**
     * odstranit datum termínu.
     *
     * @param  int  $id id data termínu
     */
    public function rm_datum($id){
        $user = Auth::user();
        if($user != null && $user->osobni_cislo == Kurz::find(Termin::find(Termindatum::find($id)->id_termin)->id_kurz)->garant){
            Termindatum::destroy($id);
            return redirect('/');
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * vytvoření data pro termín, zadání nulového hodnocení pro všechny studenty termínu.
     *
     * @param  \Illuminate\Http\Request  $request data z formuláře
     */
    public function create_datum(Request $request){
        $user = Auth::user();
        if($user != null && $user->osobni_cislo == Kurz::find(Termin::find($request->input('id_termin'))->id_kurz)->garant){
            $termin = new Termindatum;
            $termin->typ = $request->input('typ');
            $termin->popis = $request->input('popis');
            $termin->minbody = $request->input('min');
            $termin->maxbody = $request->input('max');
            $termin->datum = $request->input('datum');
            $termin->id_termin = $request->input('id_termin');
            $termin->save();
            $mat_termin = Termin::find($request->input('id_termin'));
            $students = $mat_termin->students;
            foreach($students as $student){
                $hodnoceni = new Hodnoceni;
                $hodnoceni->hodnoceni = 0;
                $hodnoceni->id_student = $student->id_student;
                $hodnoceni->id_termindatum = $termin->id_termindatum;
                $hodnoceni->save();
            }
            return redirect('/');
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * zobrazit data termínu pro výběr data, které chceme hodnotit.
     *
     * @param  int  $id id termínu
     */
    public function data_kurz($id){
        $termin = Termin::find($id);
        return view('pages.data_kurz')->with('datumy',$termin->termindatums);
    }

    /**
     * zobrazit formulář pro hodnocení studentů daného data.
     *
     * @param  int  $id id data termínu
     */
    public function hodnotit_termin($id){
        $termindatum = Termindatum::find($id);
        $termin = Termin::find($termindatum->id_termin);
        $kurz = Kurz::find($termin->id_kurz);
        $user = Auth::user();
        $found = 0;
        $lektors = $termin->lektors;
        if($user != null){
            foreach($lektors as $lektor){
                if($lektor->osobni_cislo == $user->osobni_cislo){
                    $found = 1;
                }
            }
            if($user->osobni_cislo == $kurz->garant || $found == 1){
                return view('pages.hodnotit_termin')->with('students',$termindatum->students)->with('id',$id)->with('termin',$termindatum);
            }else{
                return view('pages.not_auth');
            }
        }else{
            return view('pages.not_auth');
        }
        
    }

    /**
     * uložit hodnocení pro studenty.
     *
     * @param  \Illuminate\Http\Request  $request data z formuláře
     */
    public function ulozit_hodnoceni(Request $request){
        $data = $request->all();
        if(count($data)>1){
            $firstKey = array_key_first($data);
            $user = Auth::user();
            $termin = Termin::find(Termindatum::find($request->input('id_termindatum'))->id_termin);
            $lektors = $termin->lektors;
            $found = 0;
            foreach($lektors as $lektor){
                if($lektor->osobni_cislo == $user->osobni_cislo){
                    $found = 1;
                }
            }
            if($user != null && ($user->osobni_cislo == Kurz::find(Student::Find($firstKey)->id_kurz)->garant || $found == 1)){
                foreach ($data as $key=>$value) {
                    if($key != "id_termindatum"){
                        $hodnoceni = Hodnoceni::where('id_termindatum','LIKE',$request->input('id_termindatum'))->where('id_student','LIKE',$key)->first();
                        $hodnoceni->hodnoceni = $value;
                        $hodnoceni->save();
                    }
                }
            }else{
                return view('pages.not_auth');
            }
            return redirect("/");
        }else{
            echo("nemuzete odeslat hodnoceni pro nikoho");
        }
        
    }

    /**
     * zobrazení studentů kurzu, zobrazení formuláře pro potvrzení registrace studenta do kurzu.
     *
     * @param  int  $id id kurzu
     */
    public function studenti_kurz($id){
        $user = Auth::user();
        if($user != null && $user->osobni_cislo == Kurz::find($id)->garant){
            $kurz = Kurz::find($id);
            return view('pages.studenti_kurz')->with('students',$kurz->students);
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * potvrdit registraci studenta do kurzu.
     *
     * @param  int  $id_kurz id kurzu
     * @param  int  $osobni_cislo osobní číslo studenta
     */
    public function confirm_kurz($id_kurz, $osobni_cislo){
        $user = Auth::user();
        $kurz = Kurz::find($id_kurz);
        if($user != null && $user->osobni_cislo == $kurz->garant){
            $student_out = Student::where('osobni_cislo','LIKE', $osobni_cislo)->where('id_kurz','LIKE',$id_kurz)->first();
            $student_out->potvrzeni = 1;
            $student_out->save();
            return view('pages.studenti_kurz')->with('students',$kurz->students);
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * zobrazení formuláře pro přidání lektora do daného termínu, zobrazení lektorů daného termínu
     *
     * @param  int  $id id terminu
     */
    public function add_lektor($id){
        $user = Auth::user();
        if($user != null && $user->osobni_cislo == Kurz::find(Termin::find($id)->id_kurz)->garant){
            $termin = Termin::find($id);
            return view('pages.add_lektor')->with('id',$id)->with('lektors',$termin->lektors);
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * přidání lektora do kurzu
     *
     * @param  \Illuminate\Http\Request  $request data z formuláře
     */
    public function add_lektor_form(Request $request){
        $user = Auth::user();
        if(User::where('email', 'LIKE', $request->input('lektor'))->first() == null){
            echo("enter valid user");
        }else{
            if($user != null && $user->osobni_cislo == Kurz::find(Termin::find($request->input('id_termin'))->id_kurz)->garant){
                $lektor = new Lektor;
                $lektor->id_termin = $request->input('id_termin');
                $id_lektor = User::where('email', 'LIKE', $request->input('lektor'))->first();
                $lektor->osobni_cislo = $id_lektor->osobni_cislo;
                $lektor->save();
                return redirect('/');
            }else{
                return view('pages.not_auth');
            }
        }
    }

    /**
     * odstranění lektora z termínu.
     *
     * @param  int  $id_termin id termínu
     * @param  int  $osobni_cislo osobní číslo lektora
     */
    public function rm_lektor($id_termin, $osobni_cislo){
        $user = Auth::user();
        if($user != null && $user->osobni_cislo == Kurz::find(Termin::find($id_termin)->id_kurz)->garant){
            $lektor = Lektor::where('id_termin','LIKE', $id_termin)->where('osobni_cislo','LIKE',$osobni_cislo)->first();
            Lektor::destroy($lektor->id_lektor);
            return redirect('/');
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * odstranění studenta z kurzu.
     *
     * @param  int  $id_kurz id kurzu
     * @param  int  $osobni_cislo osobní číslo studenta
     */
    public function rm_student($id_kurz, $osobni_cislo){
        $user = Auth::user();
        if($user != null && $user->osobni_cislo == Kurz::find($id_kurz)->garant){
            $student = Student::where('id_kurz','LIKE', $id_kurz)->where('osobni_cislo','LIKE',$osobni_cislo)->first();
            Student::destroy($student->id_student);
            return redirect('/');
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * zobrazit formulář pro upravení termínu
     *
     * @param  int  $id id terminu
     */
    public function modify_termin($id){
        $termin = Termin::find($id);
        $user = Auth::user();
        $mistnost = Mistnost::all();
        if($user != null && $user->osobni_cislo == Kurz::find($termin->id_kurz)->garant){
            return view('pages.upravit_termin')->with('termin',$termin)->with('mistnosts',$mistnost);
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * upravit termín
     *
     * @param  \Illuminate\Http\Request  $request data z formuláře
     */
    public function termin_update(Request $request){
        $user = Auth::user();
        $termin = Termin::find($request->input('id_termin'));
        $kurz = Kurz::find($termin->id_kurz);
        if(Mistnost::find($request->input('mistnost')) == null){
            echo("enter valid mistnost");
        }else{
            if($user != null && $user->osobni_cislo == $kurz->garant){
                $termin->popis = $request->input('popis');
                $termin->od = $request->input('od');
                $termin->do = $request->input('do');
                $termin->den = $request->input('den');
                $termin->skupina = $request->input('skupina');
                $termin->id_mistnost = $request->input('mistnost');
                $termin->kapacita = $request->input('kapacita');
                $termin->save();
                return redirect('/');
            }else{
                return view('pages.not_auth');
            }
        }
    }
}
