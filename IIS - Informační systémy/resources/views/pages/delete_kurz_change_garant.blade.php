@extends('layouts.app')
@section('content')
<h1>Změnit garanta kurzu {{$kurz->zkratka}}</h1>
<form action="/admin/{{$kurz->id_kurz}}/{{$user->osobni_cislo}}/zmenit_garanta">
  <div class="form-group">
      <label for="email">Zadat email nového garanta</label>
      <input type="email" class="form-control" name="email" placeholder="garant@gmail.com">
  </div>
  <div class="form-group">
      <input type="submit" value="Změnit garanta">
  </div>
</form>

@endsection