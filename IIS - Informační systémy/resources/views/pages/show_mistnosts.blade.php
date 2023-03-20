@extends('layouts.app')
@section('content')
<h1>Místnosti</h1>
<table class="tg">
    <thead>
        <tr>
            <td class="tg-baqh">Název</td>
            <td class="tg-0lax">Budova</td>
            <td class="tg-0lax">Kapacita</td>
            <td class="tg-0lax">Popis</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($mistnosts as $mistnost)
            <tr>
                <td class="tg-baqh">{{$mistnost->nazev}}</td>
                <td class="tg-0lax">{{$mistnost->budova}}</td>
                <td class="tg-0lax">{{$mistnost->kapacita}}</td>
                <td class="tg-0lax">{{$mistnost->popis}}</td>
                <td class="tg-baqh"><a href="/admin/{{$mistnost->id_mistnost}}/upravit_mistnost">Upravit místnost</a></td>
                <td class="tg-baqh"><a href="/admin/{{$mistnost->id_mistnost}}/odstranit_mistnost">Odstranit místnost</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection