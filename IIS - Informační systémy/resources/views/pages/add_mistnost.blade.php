@extends('layouts.app')
@section('content')
<h1>Přidat místnost</h1>
<form action="/admin/create_mistnost">
    <div class="form-group">
        <label for="nazev">Název</label>
        <input type="text" class="form-control" name="nazev" placeholder="D100">
    </div>
    <div class="form-group">
        <label for="budova">Budova</label>
        <input type="text" class="form-control" name="budova" placeholder="D">
    </div>
    <div class="form-group">
        <label for="kapacita">Kapacita</label>
        <input type="text" class="form-control" name="kapacita" placeholder="100">
    </div>
    <div class="form-group">
        <label for="popis">Popis</label>
        <input type="text" class="form-control" name="popis" placeholder="Místnost pro přednášky">
    </div>
    <div class="form-group">
        <input type="submit" value="Přidat místnost">
    </div>
  </form>
@endsection