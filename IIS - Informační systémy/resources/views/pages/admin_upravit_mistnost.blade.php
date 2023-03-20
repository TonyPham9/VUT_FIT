@extends('layouts.app')
@section('content')
<h1>Upravit místnost</h1>
<h2>{{$mistnost->nazev}}</h2>
<form action="admin/update_mistnost">
    <div class="form-group">
        <label for="nazev">Název</label>
        <input type="text" class="form-control" name="nazev" value = {{$mistnost->nazev}}>
    </div>
    <div class="form-group">
        <label for="budova">Budova</label>
        <input type="text" class="form-control" name="budova" value = {{$mistnost->budova}}>
    </div>
    <div class="form-group">
      <label for="kapacita">Kapacita</label>
      <input type="text" class="form-control" name="kapacita" pattern="\d*" maxlength="4" value = {{$mistnost->kapacita}}>
    </div>
    <div class="form-group">
      <label for="popis">Popis</label>
      <textarea class="form-control" name="popis" rows="1">{{$mistnost->popis}}</textarea>
    </div>
    <div class="form-group">
        <input type="submit" value="Upravit místnost">
    </div>
</form>
@endsection
