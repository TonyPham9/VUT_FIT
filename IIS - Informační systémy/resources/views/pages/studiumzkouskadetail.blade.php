@extends('layouts.app')
@section('content')
    <h1>{{$kurz->zkratka}} Zkouška</h1>
    <table class="tg">
      <thead>
        <tr>
          <td class="tg-0lax">Popis</td>
          <td class="tg-0lax">Datum</td>
          <td class="tg-0lax">Minimílní Body</td>
          <td class="tg-0lax">Maximální Body</td>
          <td class="tg-0lax">Získané Body</td>
        </tr>
      </thead>
      <tbody>
        @foreach($termins as $termin)
          @if($termin->typ == 'zkouska')
            <tr>
              <td class="tg-0lax">{{$termin->popis}}</td>
              <td class="tg-0lax">{{$termin->datum}}</td>
              <td class="tg-0lax">{{$termin->minbody}}</td>
              <td class="tg-0lax">{{$termin->maxbody}}</td>
              <td class="tg-0lax">{{$termin->pivot->hodnoceni}}</td>
            </tr>
          @endif
        @endforeach
      </tbody>
    </table>
@endsection