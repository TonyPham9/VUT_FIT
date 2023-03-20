@extends('layouts.app')
@section('content')
    <h1>Registrace termínů</h1>
    <form action="registrace/filtr">
        @csrf
        <div class="form-group">
            <label for="kurz">Kurz</label>
            <select class="form-control" name="kurz">
                <option>Všechny</option>
                @foreach($kurzs as $kurz)
                    @if($kurz->pivot->potvrzeni == 1)
                        <option>{{$kurz->zkratka}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="typ">Typ</label>
            <select class="form-control" name="typ">
                <option>Všechny</option>
                <option value="cviceni">Cvičení</option>
                <option value="prednaska">Přednáška</option>
                <option value="projekt">Projekt</option>
                <option value="zkouska">Zkouška</option>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" value="Filtrovat">
        </div>
    </form>
    <table class="tg">
        <thead>
          <tr>
            <td class="tg-baqh">Kurz</td>
            <td class="tg-0lax">Typ</td>
            <td class="tg-0lax">Název</td>
            <td class="tg-0lax">Popis</td>
            <td class="tg-0lax">Den</td>
            <td class="tg-0lax">Od</td>
            <td class="tg-0lax">Do</td>
            <td class="tg-0lax">Skupina</td>
            <td class="tg-0lax">Kapacita</td>
            <td class="tg-0lax">Přihlasení</td>
          </tr>
        </thead>
        <tbody>
            @foreach($termins as $termin)
                <tr>
                    <td class="tg-baqh">{{App\Models\Kurz::Find($termin->id_kurz)->zkratka}}</td>
                    <td class="tg-0lax">{{$termin->typ}}</td>
                    <td class="tg-0lax">{{$termin->nazev}}</td>
                    <td class="tg-0lax">{{$termin->popis}}</td>
                    @switch($termin->den)
                    @case(1)
                    <td class="tg-0lax" height="50">Pondeli</td>
                        @break
                    @case(2)
                    <td class="tg-0lax" height="50">Utery</td>
                        @break
                    @case(3)
                    <td class="tg-0lax" height="50">Streda</td>
                        @break
                    @case(4)
                    <td class="tg-0lax" height="50">Ctvrtek</td>
                        @break
                    @case(5)
                    <td class="tg-0lax" height="50">Patek</td>
                        @break
                    @endswitch
                    <td class="tg-0lax">{{$termin->od}}</td>
                    <td class="tg-0lax">{{$termin->do}}</td>
                    <td class="tg-0lax">{{$termin->skupina}}</td>
                    @php ($pocet = 0)
                    @foreach($termins_count as $termin_count)
                        @if ($termin->id_termin == $termin_count->id_termin)
                            @php ($pocet += 1)
                        @endif
                    @endforeach
                    <td class="tg-0lax">{{$pocet}}/{{$termin->kapacita}}</td>
                    @php ($registrace = 0)
                    @foreach($stud_termins as $stud_termin)
                          @if ($termin->id_termin == $stud_termin->id_termin)
                          @php ($registrace = 1)
                          @break
                          @else
                          @php ($registrace = 0)
                          @endif
                    @endforeach
                    @if($registrace == 1)
                        <td class="tg-0lax"><button><a href="/registrace/{{$termin->id_termin}}/odregistrovat">Odregistrovat</a></button></td>
                    @else
                        @php($ishere = 1)
                        @foreach($sortTermins as $sortTermin)
                            @if ($sortTermin->id_kurz == $termin->id_kurz && $sortTermin->typ == $termin->typ && $termin->typ != "zkouska")
                                @php ($ishere = 0)
                                @break
                            @else
                                @php ($ishere = 1)
                            @endif
                        @endforeach
                        @if($ishere == 1 && ($pocet < $termin->kapacita))
                            <td class="tg-0lax"><button><a href="/registrace/{{$termin->id_termin}}/registrovat">Registrovat</a></button></td>
                        @else
                            <td class="tg-0lax"><button>Nelze</button></td>
                        @endif
                    @endif
                </tr>
            @endforeach
        </tbody>
     </table>
@endsection