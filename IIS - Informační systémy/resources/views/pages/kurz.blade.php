@extends('layouts.app')
@section('content')
        <table class="tg">
            <thead>
              <tr>
                @guest
                <td class="tg-baqh">Zkratka</td>
                <td class="tg-0lax">Název</td>
                <td class="tg-0lax">Počet míst</td>
                <td class="tg-0lax">Kredity</td>
                <td class="tg-0lax">Typ</td>
                <td class="tg-0lax">Jazyk</td>
                <td class="tg-0lax">Zakončení</td>
                <td class="tg-0lax">Popis</td>
                <td class="tg-0lax">Cena</td>
                @else
                <td class="tg-baqh">Zkratka</td>
                <td class="tg-0lax">Název</td>
                <td class="tg-0lax">Počet míst</td>
                <td class="tg-0lax">Kredity</td>
                <td class="tg-0lax">Typ</td>
                <td class="tg-0lax">Jazyk</td>
                <td class="tg-0lax">Zakončení</td>
                <td class="tg-0lax">Popis</td>
                <td class="tg-0lax">Cena</td>
                <td class="tg-0lax">Registrace</td>
                @endguest
              </tr>
              @foreach($kurzs as $kurz)
                @if ($kurz->schvaleno == 1)
                    <tr>
                      @guest
                        <td class="tg-baqh">{{$kurz->zkratka}}</td>
                        <td class="tg-0lax">{{$kurz->nazev}}</td>
                        <td class="tg-0lax">{{$kurz->pocet_mist}}</td>
                        <td class="tg-0lax">{{$kurz->kredity}}</td>
                        <td class="tg-0lax">{{$kurz->typ}}</td>
                        <td class="tg-0lax">{{$kurz->jazyk}}</td>
                        <td class="tg-0lax">{{$kurz->zpusob_zakonceni}}</td>
                        <td class="tg-0lax">{{$kurz->popis}}</td>
                        <td class="tg-0lax">{{$kurz->cena}}</td>
                      @else
                        <td class="tg-baqh">{{$kurz->zkratka}}</td>
                        <td class="tg-0lax">{{$kurz->nazev}}</td>
                        @php ($pocet = 0)
                        @foreach($stud_all as $stud_kurz)
                          @if ($kurz->id_kurz == $stud_kurz->id_kurz)
                            @php ($pocet += 1)
                          @endif
                        @endforeach
                        <td class="tg-0lax">{{$pocet}}/{{$kurz->pocet_mist}}</td>
                        <td class="tg-0lax">{{$kurz->kredity}}</td>
                        <td class="tg-0lax">{{$kurz->typ}}</td>
                        <td class="tg-0lax">{{$kurz->jazyk}}</td>
                        <td class="tg-0lax">{{$kurz->zpusob_zakonceni}}</td>
                        <td class="tg-0lax">{{$kurz->popis}}</td>
                        <td class="tg-0lax">{{$kurz->cena}}</td>
                        @php ($registrace = 0)
                        @foreach($stud_kurzs as $stud_kurz)
                          @if ($kurz->id_kurz == $stud_kurz->id_kurz)
                            @php ($registrace = 1)
                            @break
                          @else
                            @php ($registrace = 0)
                          @endif
                        @endforeach
                        @php($islektor = 0)
                        @foreach($lektors as $lektor)
                          @if($lektor == $kurz->id_kurz)
                            @php($islektor = 1)
                            @break
                          @endif
                        @endforeach
                        @if($kurz->garant == $user->osobni_cislo)
                          <td class="tg-0lax"><button>Garant</button></td>
                        @else
                        @if($islektor == 1)
                        <td class="tg-0lax"><button>Lektor</button></td>
                        @else
                        @if($registrace == 1)
                          <td class="tg-0lax"><button><a href="{{ route('rm_kurz_student', [$kurz->id_kurz]) }}">Odregistrovat</a></button></td>
                        @else
                        @if ($kurz->auto_add == 1 && $pocet < $kurz->pocet_mist)
                            <td class="tg-0lax"><button><a href="{{ route('add_kurz_student', [$kurz->id_kurz, 1]) }}">Registrovat</a></button></td>
                        @elseif ($pocet >= $kurz->pocet_mist)
                          <td class="tg-0lax"><button>Kurz je plný</button></td>
                        @else
                          <td class="tg-0lax"><button><a href="{{ route('add_kurz_student', [$kurz->id_kurz, 0]) }}">Registrovat</a></button></td>
                        @endif
                        @endif
                        @endif
                        @endif
                        @endguest
                    </tr>
                @endif
              @endforeach
            </thead>
            </table>
@endsection