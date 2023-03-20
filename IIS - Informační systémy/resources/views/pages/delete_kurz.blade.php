@extends('layouts.app')
@section('content')
<h1>Odstranit kurz</h1>
        <table class="tg">
            <thead>
              <tr>
                <td class="tg-baqh">Zkratka</td>
                <td class="tg-0lax">Název</td>
                <td class="tg-0lax">Počet míst</td>
                <td class="tg-0lax">Kredity</td>
                <td class="tg-0lax">Typ</td>
                <td class="tg-0lax">Jazyk</td>
                <td class="tg-0lax">Zakončení</td>
                <td class="tg-0lax">Popis</td>
                <td class="tg-0lax">Cena</td>
                <td class="tg-0lax">Schváleno</td>
              </tr>
            </thead>
            <tbody>
              @foreach($kurzs as $kurz)
                  <tr>
                        <td class="tg-baqh">{{$kurz->zkratka}}</td>
                        <td class="tg-0lax">{{$kurz->nazev}}</td>
                        <td class="tg-0lax">{{$kurz->pocet_mist}}</td>
                        <td class="tg-0lax">{{$kurz->kredity}}</td>
                        <td class="tg-0lax">{{$kurz->typ}}</td>
                        <td class="tg-0lax">{{$kurz->jazyk}}</td>
                        <td class="tg-0lax">{{$kurz->zpusob_zakonceni}}</td>
                        <td class="tg-0lax">{{$kurz->popis}}</td>
                        <td class="tg-0lax">{{$kurz->cena}}</td>
                        <td class="tg-0lax">{{$kurz->schvaleno}}</td>
                        <td class="tg-0lax"><button><a href="/admin/{{$kurz->id_kurz}}/odstranit_kurz">Odstranit kurz</a></button></td>
                  </tr>
              @endforeach
            </tbody>  
        </table>
@endsection