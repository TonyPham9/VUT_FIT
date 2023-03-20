<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mistnost;
use App\Models\Kurz;
use App\Models\Termin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Zobrazí hlavní menu admina.
     *
     */
    public function index()
    {
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){
            return view('pages.admin');
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * Vytvoří nového uživatele a zapíše ho do databáze.
     *
     * @param \Illuminate\Http\Request $request - data z formuláře 
     */
    public function create(Request $request)
    {
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){  
            $user = new User;
            $this->validate($request, [
                'name' => 'required',
                'surname' => 'required',
                'email' => 'required',
                'password' => 'required',
                'datum_narozeni' => 'required',
                'adresa' => 'required',
                'phone_number' => 'required']);
            $user->name = $request->input('name');
            $user->surname = $request->input('surname');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->datum_narozeni = $request->input('datum_narozeni');
            $user->adresa = $request->input('adresa');
            $user->phone_number = $request->input('phone_number');
            $user->save();
            return redirect('/admin/add_user');
        } else {
            return view('pages.not_auth');
        }
    }


    /**
     * Zobrazí upravovaného uživatele.
     *
     * @param  int  $id - id upravovaného uživatele
     */
    public function show($id)
    {
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){    
            $user = User::Find($id);
            return view('pages.admin_upravit_user')->with('user', $user);
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * Zobrazí upravovanou místnost.
     *
     * @param  int  $id - id upravované místnosti
     */
    public function show_mistnost($id)
    {
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){    
            $mistnost = Mistnost::Find($id);
            return view('pages.admin_upravit_mistnost')->with('mistnost', $mistnost);
        }else{
            return view('pages.not_auth');
        }
    }


    /**
     * Update dat uživatele.
     *
     * @param  \Illuminate\Http\Request  $request - data z formuláře
     * @param  int  $id - id uživatele kterého updatuji
     */
    public function update($id, Request $request)
    {   
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){    
            $user = User::Find($id);
            $user->name = $request->input('name');
            $user->surname = $request->input('surname');
            $user->email = $request->input('email');
            $user->datum_narozeni = $request->input('datum_narozeni');
            $user->adresa = $request->input('adresa');
            $user->phone_number = $request->input('phone_number');
            $user->save();
            return redirect('/admin/show_users');
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * Update hesla uživatele.
     *
     * @param  \Illuminate\Http\Request  $request - data z formuláře
     * @param  int  $id - id uživatele kterého updatuji
     */
    public function update_pswd($id, Request $request)
    {   
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){    
            $user = User::Find($id);
            $user->password = Hash::make($request->input('password'));
            $user->save();
            return redirect('/admin/show_users');
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * Přepsání garanta když mažu uživatele, který něco garantuje.
     *
     * @param  \Illuminate\Http\Request  $request - data z formuláře
     * @param  int  $id - id uživatele 
     * @param  int  $id_garant - id garanta 
     */
    public function update_garant($id, $id_garant,Request $request)
    {   
        if(User::where('email', 'LIKE', $request->input('email'))->first() == null){
            echo("enter valid user");
        }else{
            if(Auth::user() != null && Auth::user()->osobni_cislo == 999){    
                $kurz = Kurz::Find($id);
                $this->validate($request, ['email' => 'required']);
                $garant = User::where('email', $request->input('email'))->get();
                $kurz->garant = $garant[0]->osobni_cislo;
                $kurz->save();
                return redirect('/admin/'.$id_garant.'/odstranit');
            }else{
                return view('pages.not_auth');
            }
        }
    }

    /**
     * Update dat místnosti.
     *
     * @param  \Illuminate\Http\Request  $request - data z formuláře
     * @param  int  $id - id místnosti
     */
    public function update_mistnost($id, Request $request)
    {
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){    
            $mistnost = Mistnost::Find($id);
            $mistnost->nazev = $request->input('nazev');
            $mistnost->budova = $request->input('budova');
            $mistnost->kapacita = $request->input('kapacita');
            $mistnost->popis = $request->input('popis');
            $mistnost->save();
            return redirect('/admin/show_mistnosts');
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * Odstraní uživatele.
     *
     * @param  int  $id - id uživatele
     */
    public function destroy($id)
    {
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){    
            $kurzs = Kurz::All();
            $user = User::Find($id);
            foreach($kurzs as $kurz){
                if($kurz->garant == $id){
                    return view('pages.delete_kurz_change_garant')->with('kurz', $kurz)->with('user', $user);
                }
            }
            User::destroy($id);
            return redirect('/admin/show_users');
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * Odstraní uživatele.
     *
     * @param  int  $id - id místnosti
     */
    public function destroy_mistnost($id)
    {
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){   
            $termins = Termin::All();
            foreach($termins as $termin){
                if($termin->id_mistnost == $id){
                    return redirect('/admin/show_mistnosts');
                }
            }
            Mistnost::destroy($id);
            return redirect('/admin/show_mistnosts');
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * Odstraní kurz.
     *
     * @param  int  $id - id kurzu
     */
    public function destroy_kurz($id)
    {
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){    
            Kurz::destroy($id);
            return redirect('/admin/delete_kurz');
        }else{
            return view('pages.not_auth');
        }
    }

    /**
     * Zobrazí všechny uživatele.
     *
     */
    public function show_users(){
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){
            $users = User::All();
            return view('pages.show_users')->with('users', $users);
        }else{
            return view('pages.not_auth');
        } 
    }

    /**
     * Zobrazí všechny kurzy.
     *
     */
    public function show_kurzs(){
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){
            $kurzs = Kurz::All();
            return view('pages.delete_kurz')->with('kurzs', $kurzs);     
        }else{
            return view('pages.not_auth');
        } 
    }

    /**
     * Zobrazí všechny místnosti.
     *
     */
    public function show_mistnosts(){
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){
            $mistnosts = Mistnost::All();
            return view('pages.show_mistnosts')->with('mistnosts', $mistnosts); 
        }else{
            return view('pages.not_auth');
        } 
    }

    /**
     * Zobrazí pohled pro přidání uživatele.
     *
     */
    public function add_user()
    {   
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){
            return view('pages.add_user');
        }else{
            return view('pages.not_auth');
        }  
    }

    /**
     * Zobrazí pohled pro přidání místnosti.
     *
     */
    public function add_mistnost()
    {   
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){    
            return view('pages.add_mistnost');
        }else{
            return view('pages.not_auth');
        }  
    }

    /**
     * Zobrazí pohled pro schválené kurzu.
     *
     */
    public function check_kurz()
    {   
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){  
            $kurzs = Kurz::All();
            return view('pages.check_kurz')->with('kurzs', $kurzs);
        }else{
            return view('pages.not_auth');
        } 
    }

    /**
     * Schválí vybraný kurz.
     *
     * @param  int  $id - id kurzu co schvaluji
     */
    public function check_kurz_admin($id)
    {   
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){   
            $id = Kurz::Find($id);
            $id->schvaleno = 1;
            $id->save();
            return redirect('/admin/check_kurz');
        }else{
            return view('pages.not_auth');
        }         
    }

    /**
     * Vytvoří novou místnost.
     *
     * @param  \Illuminate\Http\Request  $request - data z formuláře
     */
    public function create_mistnost(Request $request)
    {   
        if(Auth::user() != null && Auth::user()->osobni_cislo == 999){   
            $mistnost = new Mistnost;
            $this->validate($request, [
                'nazev' => 'required',
                'budova' => 'required',
                'kapacita' => 'required',
                'popis' => 'required']);
            $mistnost->nazev = $request->input('nazev');
            $mistnost->budova = $request->input('budova');
            $mistnost->kapacita = $request->input('kapacita');
            $mistnost->popis = $request->input('popis');
            $mistnost->save();
            return redirect('/admin/add_mistnost');
        }else{
            return view('pages.not_auth');
        } 
    }
}
