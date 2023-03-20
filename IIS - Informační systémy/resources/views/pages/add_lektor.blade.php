@extends('layouts.app')
@section('content')
<table class="tg">
<thead>
    <tr>
        <td class="tg-0lax">jméno</td>
        <td class="tg-0lax">příjmení</td>
        <td class="tg-baqh">email</td>
    </tr>
@foreach($lektors as $lektor)
    <tr>
        <td class="tg-baqh">{{$lektor->name}}</td>
        <td class="tg-baqh">{{$lektor->surname}}</td>
        <td class="tg-baqh">{{$lektor->email}}</td>
        <td class="tg-baqh"><a href="{{ route('rm_lektor', [$lektor->pivot->id_termin,$lektor->pivot->osobni_cislo]) }}">odstranit lektora</a></td>
    </tr>
@endforeach
</thead>
</table>
<h1>přidat lektora</h1>
<form action="/ucitel/add_lektor_form">
    <div class="form-group">
        <label for="lektor">email lektora</label>
        <input type="text" class="form-control" name="lektor" placeholder="example@seznam.cz">
    </div>
    <div class="form-group">
        <input type="submit" value="odeslat k potvrzení">
    </div>
    <input type="hidden" value="{{$id}}" name="id_termin">
  </form>
@endsection