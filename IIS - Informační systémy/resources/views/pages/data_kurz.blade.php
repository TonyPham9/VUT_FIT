@extends('layouts.app')
@section('content')
<h1>Data pro termín</h1>
<table class="tg">
    <thead>
        <tr>
            <td class="tg-baqh">popis</td>
            <td class="tg-0lax">minbody</td>
            <td class="tg-0lax">maxbody</td>
            <td class="tg-0lax">datum</td>
        </tr>
    @foreach($datumy as $datum)
        <tr>
            <td class="tg-baqh">{{$datum->popis}}</td>
            <td class="tg-baqh">{{$datum->minbody}}</td>
            <td class="tg-baqh">{{$datum->maxbody}}</td>
            <td class="tg-baqh">{{$datum->datum}}</td>
            <td class="tg-baqh"><a href="{{ route('hodnotit_termin', [$datum->id_termindatum]) }}">ohodnotit termin</a></td>
        </tr>
    @endforeach
    </thead>
</table>
@endsection