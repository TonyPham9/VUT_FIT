@extends('layouts.app')
@section('content')
<h1>Založit nový kurz</h1>
<form action="/ucitel/create">
    <div class="form-group">
      <label for="zkratka">Zkratka</label>
      <input type="text" class="form-control" name="zkratka" placeholder="AAA">
    </div>
    <div class="form-group">
        <label for="nazev">Název</label>
        <input type="text" class="form-control" name="nazev" placeholder="HTML basics">
    </div>
    <div class="form-group">
        <label for="pocet_mist">Počet míst</label>
        <input type="number" class="form-control" name="pocet_mist" placeholder="150" min="1">
    </div>
    <div class="form-group">
        <label for="kredity">kredity</label>
        <input type="number" class="form-control" name="kredity" placeholder="5" min="1" max="20">
    </div>
    <div class="form-group">
      <label for="typ">typ</label>
      <select class="form-control" name="typ">
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
      <textarea class="form-control" name="popis" rows="3"></textarea>
    </div>
    <div class="form-group">
        <label for="cena">cena</label>
        <input type="number" class="form-control" name="cena" placeholder="100" min="1">
    </div>
    <div class="form-group">
      <label for="auto">auto schválení studentů</label>
      <input type="number" class="form-control" name="auto" placeholder="0-zakazano, 1-povoleno" min="0" max="1">
    </div>
    <div class="form-group">
        <input type="submit" value="odeslat k potvrzení">
    </div>
  </form>
@endsection