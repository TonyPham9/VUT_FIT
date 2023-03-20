@extends('layouts.app')
@section('content')
    <h1>{{$kurz->zkratka}}</h1>
    <table class="tg">
      <tbody>
        <tr>
            <td class="tg-baqh"><a href="/studium/{{$kurz->id_kurz}}/cviceni">Cvičení</a></td>
            <td class="tg-0lax" colspan="2">{{$cbody}}</td>
        </tr>
        <tr>
          <td class="tg-baqh"><a href="/studium/{{$kurz->id_kurz}}/projekt">Projekt</a></td>
          <td class="tg-0lax" colspan="2">{{$pbody}}</td>
        </tr>
        <tr>
          <td class="tg-baqh"><a href="/studium/{{$kurz->id_kurz}}/zkouska">Zkouška</a></td>
          <td class="tg-0lax" colspan="2">{{$zbody}}</td>
        </tr>
        <tr>
          <td class="tg-baqh">Celkové hodnocení</td>
          <td class="tg-0lax">{{$body}}</td>
          <td class="tg-0lax"><b>{{$znamka}}</b></td>
        </tr>  
      </tbody>
    </table>
@endsection