@extends('layouts.app')
@section('content')

    <h1>Upravit Termín</h1>
    <form action={{ url('/ucitel/termin_update')}}>
        <div class="form-group">
            <label for="popis">popis</label>
            <textarea class="form-control" name="popis" rows="3" >{{$termin->popis}}</textarea>
        </div>
        <div class="form-group">
            <label for="od">Od</label>
            <input type="time" class="form-control" name="od" value={{$termin->od}}>
        </div>
        <div class="form-group">
            <label for="do">Do</label>
            <input type="time" class="form-control" name="do" value={{$termin->do}}>
        </div>
        <div class="form-group">
            <label for="den">den</label>
            <select class="form-control" name="den">
              <option value="1">pondělí</option>
              <option value="2">úterý</option>
              <option value="3">středa</option>
              <option value="4">čtvrtek</option>
              <option value="5">pátek</option>
            </select>
        </div>
            <div class="form-group">
                <label for="skupina">Skupina</label>
                <input type="number" class="form-control" name="skupina" value={{$termin->skupina}} min="1">
            </div>
            <div class="form-group">
                <label for="mistnost">Místnost</label>
                <select class="form-control" name="mistnost">
                    @foreach($mistnosts as $mistnost)
                        <option value={{$mistnost->id_mistnost}}>{{$mistnost->nazev}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="kapacita">Kapacita</label>
                <input type="number" class="form-control" name="kapacita" value={{$termin->kapacita}}>
            </div>
        <div class="form-group">
            <input type="number" class="form-control" name="id_termin" value={{$termin->id_termin}} hidden="true">
        </div>
        <div class="form-group">
            <input type="submit" value="Upravit termin">
        </div>
    </form>

@endsection