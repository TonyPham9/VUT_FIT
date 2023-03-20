@extends('layouts.app')
@section('content')
<h1>Přidat uživatele</h1>
<form action="/admin/create">
    <div class="form-group">
        <label for="name">Jméno</label>
        <input type="text" class="form-control" name="name" placeholder="Josef">
      </div>
      <div class="form-group">
          <label for="surname">Příjmení</label>
          <input type="text" class="form-control" name="surname" placeholder="Švejk">
      </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="text" class="form-control" name="email" placeholder="example@email.cz">
    </div>
    <div class="form-group">
        <label for="password">Heslo</label>
        <input type="text" class="form-control" name="password" placeholder="heslo1">
    </div>
    <div class="form-group">
        <label for="datum_narozeni">Datum narození</label>
        <input type="date" class="form-control" name="datum_narozeni" value="2018-07-22" min="2000-01-01" max="2022-12-31">
    </div>
    <div class="form-group">
        <label for="adresa">Adresa</label>
        <input type="text" class="form-control" name="adresa" placeholder="U vodojemu 158/1">
    </div>
    <div class="form-group">
        <label for="phone_number">Telefonní číslo</label>
        <input type="tel" class="form-control" name="phone_number" placeholder="123456789">
    </div>
    <div class="form-group">
        <input type="submit" value="Přidat uživatele">
    </div>
  </form>
@endsection