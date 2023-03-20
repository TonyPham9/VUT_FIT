@extends('layouts.app')
@section('content')

    <h1>Upravit kurz</h1>
    <h2>{{$kurz->zkratka}} - {{$kurz->nazev}}</h2>
    <form action={{ url('/ucitel/kurz_update')}}>
        <div class="form-group">
            <label for="pocet_mist">Počet míst</label>
            <input type="number" class="form-control" name="pocet_mist" value={{$kurz->pocet_mist}}>
        </div>
        <div class="form-group">
            <label for="kredity">kredity</label>
            <input type="number" class="form-control" name="kredity" value={{$kurz->kredity}}>
        </div>
        <div class="form-group">
          <label for="typ">typ</label>
          <select class="form-control" name="typ" >
            <option>povinné</option>
            <option>povinně volitelné</option>
            <option>volitelné</option>
          </select>
        </div>
        <div class="form-group">
            <label for="jazyk">jazyk</label>
            <select class="form-control" name="jazyk">
              <option>čeština</option>
              <option>angličtina</option>
            </select>
        </div>
        <div class="form-group">
            <label for="zakonceni">způsob zakončení</label>
            <select class="form-control" name="zakonceni">
              <option>zkouška</option>
              <option>klasifikovaný zápočet</option>
              <option>zápočet</option>
            </select>
          </div>
        
        <div class="form-group">
          <label for="popis">popis</label>
          <textarea class="form-control" name="popis" rows="3" >{{$kurz->popis}}</textarea>
        </div>
        <div class="form-group">
            <label for="cena">cena</label>
            <input type="number" class="form-control" name="cena" value={{$kurz->cena}}>
        </div>
        <div class="form-group">
          <label for="auto">auto schválení studentů</label>
          <input type="number" class="form-control" name="auto" placeholder="0-zakazano, 1-povoleno" min="0" max="1" value={{$kurz->auto_add}}>
        </div>
        <div class="form-group">
            <input type="number" class="form-control" name="id_kurz" value={{$kurz->id_kurz}} hidden="true">
        </div>
        <div class="form-group">
            <input type="submit" value="Upravit kurz">
        </div>
    </form>

@endsection