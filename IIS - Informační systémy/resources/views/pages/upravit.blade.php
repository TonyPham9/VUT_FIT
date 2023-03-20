@extends('layouts.app')
@section('content')
    <h1>Upravit profil</h1>
    <h2>{{$user->name}}</h2>
    <form action="upravit_profil/update">
        <div class="form-group">
            <label for="name">Jmeno</label>
            <input type="text" class="form-control" name="name" value = {{$user->name}}>
        </div>
        <div class="form-group">
            <label for="surname">Přijmení</label>
            <input type="text" class="form-control" name="surname" value = {{$user->surname}}>
        </div>
        <div class="form-group">
            <label for="datum_narozeni">Datum Narození</label>
            <input type="date" class="form-control" name="datum_narozeni" value = {{$user->datum_narozeni}}>
          </div>
        <div class="form-group">
          <label for="adresa">Adresa</label>
          <textarea class="form-control" name="adresa" rows="1">{{$user->adresa}}</textarea>
        </div>
        <div class="form-group">
            <label for="phone_number">Telefoní číslo</label>
            <input type="text" class="form-control" name="phone_number"  pattern="\d*" maxlength="9" value = {{$user->phone_number}}>
          </div>
        <div class="form-group">
            <input type="submit" value="Upravit profil">
        </div>
    </form>
        
    <form action="upravit_profil/update_password">
        @csrf
        <div class="form-group">
            <label for="password">Heslo</label>
            <input type="password" class="form-control" name="password">
        </div>
        <div class="form-group">
            <input type="submit" value="Upravit heslo">
        </div>
    </form>
@endsection