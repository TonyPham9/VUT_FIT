@extends('layouts.app')
@section('content')
    <h1>Studium</h1>
    <h2>Moje kurzy</h2>
    <table class="tg">
        <thead>
            <tr>
                <td class="tg-baqh">Zkratka</td>
                <td class="tg-0lax">Název</td>
                <td class="tg-0lax">Popis</td>
            </tr>
        </thead>
        <tbody>
            @foreach($kurzs as $kurz)
                @if($kurz->pivot->potvrzeni == 1)
                    <tr>
                        <td class="tg-baqh"><a href="/studium/{{$kurz->id_kurz}}">{{$kurz->zkratka}}</a></td>
                        <td class="tg-0lax">{{$kurz->nazev}}</td>
                        <td class="tg-0lax">{{$kurz->popis}}</td>
                        <td class="tg-0lax"><a href="/studium/{{$kurz->id_kurz}}/odhlasit">Odhlásit se od kurzu</a></td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    <h2>Kurzy čekající na potvrzení</h2>
    <table class="tg">
        <thead>
            <tr>
                <td class="tg-baqh">Zkratka</td>
                <td class="tg-0lax">Název</td>
                <td class="tg-0lax">Popis</td>
            </tr>
        </thead>
        <tbody>
            @foreach($kurzs as $kurz)
                @if($kurz->pivot->potvrzeni == 0)
                    <tr>
                        <td class="tg-baqh">{{$kurz->zkratka}}</td>
                        <td class="tg-0lax">{{$kurz->nazev}}</td>
                        <td class="tg-0lax">{{$kurz->popis}}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
       </table>
@endsection