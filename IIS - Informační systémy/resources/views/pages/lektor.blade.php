@extends('layouts.app')
@section('content')
<h1>lektorované termíny</h1>
    <table class="tg">
            <thead>
              <tr>
                <td class="tg-baqh">Kurz</td>
                <td class="tg-baqh">Název</td>
                <td class="tg-0lax">Typ</td>
                <td class="tg-0lax">Popis</td>
                <td class="tg-0lax">Od</td>
                <td class="tg-0lax">Do</td>
                <td class="tg-0lax">Den</td>
                <td class="tg-0lax">Skupina</td>
                <td class="tg-0lax">mistnost</td>
              </tr>
              @foreach($termins as $kurz)
                    <tr>
                        <p hidden="true">{{$termin = App\Models\Kurz::find($kurz->id_kurz)}}</p>
                        <td class="tg-baqh">{{$termin->zkratka}}</td>
                        <td class="tg-baqh">{{$kurz->nazev}}</td>
                        <td class="tg-0lax">{{$kurz->typ}}</td>
                        <td class="tg-0lax">{{$kurz->popis}}</td>
                        <td class="tg-0lax">{{$kurz->od}}</td>
                        <td class="tg-0lax">{{$kurz->do}}</td>
                        <td class="tg-0lax">{{$kurz->den}}</td>
                        <td class="tg-0lax">{{$kurz->skupina}}</td>
                        <td class="tg-0lax">{{$kurz->id_mistnost}}</td>
                        <td class="tg-0lax"><a href="{{ route('stud_terminu', [$kurz->id_termin]) }}">zobrazit studenty</a></td>
                        <td class="tg-0lax"><a href="{{ route('data_kurz', [$kurz->id_termin]) }}">hodnotit studenty</a></td>
                    </tr>
              @endforeach
            </thead>
         </table>
@endsection
