@extends('layouts.app')
@section('content')
<h1>Garantované kurzy</h1>
  <h2>schvalene</h2>    
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
        <td class="tg-0lax">aut. potvrzení</td>
      </tr>
      @foreach($kurzs as $kurz)
            @if ($kurz->schvaleno == 1)
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
                <td class="tg-0lax">{{$kurz->auto_add}}</td>
                <td class="tg-0lax"> <a href="{{ route('terminy', [$kurz->id_kurz]) }}">spravovat terminy</a></td>
                <td class="tg-0lax"> <a href="{{ route('upravit_kurz', [$kurz->id_kurz]) }}">upravit kurz</a></td>
                <td class="tg-0lax"> <a href="{{ route('studenti_kurz', [$kurz->id_kurz]) }}">zobrazit studenty kurzu</a></td>
              </tr>    
            @endif
      @endforeach
    </thead>
  </table>
  <h2>čeká na schválení</h2>    
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
        <td class="tg-0lax">aut. potvrzení</td>
      </tr>
      @foreach($kurzs as $kurz)
            @if ($kurz->schvaleno == 0)
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
                <td class="tg-0lax">{{$kurz->auto_add}}</td>
                <td class="tg-0lax"> <a href="{{ route('upravit_kurz', [$kurz->id_kurz]) }}">upravit kurz</a></td>
              </tr>    
            @endif
      @endforeach
    </thead>
  </table>
@endsection
