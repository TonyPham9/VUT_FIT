@extends('layouts.app')
@section('content')
<h1>Data pro konkrétní termín</h1>
<table class="tg">
    <thead>
        <tr>
            <td class="tg-baqh">popis</td>
            <td class="tg-0lax">minbody</td>
            <td class="tg-0lax">maxbody</td>
            <td class="tg-0lax">datum</td>
        </tr>
    @foreach($termins as $termin)
        <tr>
            <td class="tg-baqh">{{$termin->popis}}</td>
            <td class="tg-baqh">{{$termin->minbody}}</td>
            <td class="tg-baqh">{{$termin->maxbody}}</td>
            <td class="tg-baqh">{{$termin->datum}}</td>
            <td class="tg-baqh"><a href="{{ route('rm_datum', [$termin->id_termindatum]) }}">odstranit datum terminu</a></td>
        </tr>
    @endforeach
    </thead>
</table>
    <h2>přidat datum pro termín</h2>
    <form action="/ucitel/create_datum">
        
        <div class="form-group">
            <label for="popis">popis</label>
            <textarea class="form-control" name="popis" rows="3"></textarea>
          </div>
        <div class="form-group">
            <label for="min">Min body</label>
            <input type="text" class="form-control" name="min" placeholder="0">
        </div>
        <div class="form-group">
            <label for="max">Max body</label>
            <input type="text" class="form-control" name="max" placeholder="5">
        </div>
        <div class="form-group">
            <label for="datum">Datum</label>
            <input type="Date" class="form-control" name="datum" placeholder="1" min="1">
        </div>
        <div class="form-group">
            <input type="submit" value="pridat datum">
        </div>
        <input type="hidden" value="{{$term->id_termin}}" name="id_termin">
        <input type="hidden" value="{{$term->typ}}" name="typ">
      </form>

@endsection