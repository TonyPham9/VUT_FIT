@extends('layouts.app')
@section('content')
    <h1>Termíny - {{$kurz->zkratka}}</h1>
    <table class="tg">
        <thead>
        <tr>
          <td class="tg-baqh">Název</td>
          <td class="tg-0lax">Typ</td>
          <td class="tg-0lax">Popis</td>
          <td class="tg-0lax">Od</td>
          <td class="tg-0lax">Do</td>
          <td class="tg-0lax">Kapacita</td>
          <td class="tg-0lax">Den</td>
          <td class="tg-0lax">Skupina</td>
          <td class="tg-0lax">mistnost</td>
        </tr>
        @foreach($termins as $termin)
              <tr>
                  <td class="tg-baqh">{{$termin->nazev}}</td>
                  <td class="tg-0lax">{{$termin->typ}}</td>
                  <td class="tg-0lax">{{$termin->popis}}</td>
                  <td class="tg-0lax">{{$termin->od}}</td>
                  <td class="tg-0lax">{{$termin->do}}</td>
                  <td class="tg-0lax">{{$termin->kapacita}}</td>
                  <td class="tg-0lax">@switch($termin->den)
                    @case(1)
                        Pondeli
                        @break
                    @case(2)
                        Utery
                        @break
                    @case(3)
                        Streda
                        @break
                    @case(4)
                        Ctvrtek
                        @break
                    @case(5)
                        Patek
                        @break
                @endswitch</td>
                  <td class="tg-0lax">{{$termin->skupina}}</td>
                  <td class="tg-0lax">{{App\Models\Mistnost::find($termin->id_mistnost)->nazev}}</td>
                  <td class="tg-0lax"><a href="{{ route('rm_termin', [$termin->id_termin]) }}">odstranit<br>termin</a></td>
                  <td class="tg-0lax"><a href="{{ route('modify_termin', [$termin->id_termin]) }}">upravit<br>termin</a></td>
                  <td class="tg-0lax"><a href="{{ route('termin_datum', [$termin->id_termin]) }}">upravit data<br>terminu</a></td>
                  <td class="tg-0lax"><a href="{{ route('stud_terminu', [$termin->id_termin]) }}">zobrazit<br>studenty</a></td>
                  <td class="tg-0lax"><a href="{{ route('data_kurz', [$termin->id_termin]) }}">hodnotit<br>studenty</a></td>
                  <td class="tg-0lax"><a href="{{ route('add_lektor', [$termin->id_termin]) }}">spravovat<br>lektory</a></td>
              </tr>
        @endforeach
      </thead>
    </table>

    <h2>přidat nový termín</h2>
    <form action="/ucitel/create_termin">
        <div class="form-group">
          <label for="nazev">Název</label>
          <input type="text" class="form-control" name="nazev" placeholder="AAA">
        </div>
        <div class="form-group">
            <label for="typ">Typ</label>
            <select class="form-control" name="typ">
              <option value="prednaska">přednáška</option>
              <option value="cviceni">cvičení</option>
              <option value="zkouska">zkouška</option>
              <option value="projekt">projekt</option>
            </select>
          </div>
        <div class="form-group">
            <label for="popis">popis</label>
            <textarea class="form-control" name="popis" rows="3"></textarea>
          </div>
        <div class="form-group">
            <label for="od">Od</label>
            <input type="time" class="form-control" name="od" placeholder="08:00:00">
        </div>
        <div class="form-group">
            <label for="do">Do</label>
            <input type="time" class="form-control" name="do" placeholder="08:00:00">
        </div>
        <div class="form-group">
          <label for="kapacita">Kapacita</label>
          <input type="number" class="form-control" name="kapacita" placeholder="20">
      </div>
        <div class="form-group">
            <label for="den">den</label>
            <select class="form-control" name="den">
              <option value="1">pondělí</option>
              <option value="2">úterý</option>
              <option value="3">středa</option>
              <option value="4">čtvrtek</option>
              <option value="5">pátek</option>
            </select>
          </div>
        <div class="form-group">
            <label for="skupina">Skupina</label>
            <input type="number" class="form-control" name="skupina" placeholder="1" min="1">
        </div>
        <div class="form-group">
            <label for="mistnost">Místnost</label>
            <select class="form-control" name="mistnost">
              @foreach($mistnosts as $mistnost)
                  <option value={{$mistnost->id_mistnost}}>{{$mistnost->nazev}}</option>
              @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="lektor">email lektora</label>
            <input type="text" class="form-control" name="lektor" placeholder="example@seznam.cz">
        </div>
        <div class="form-group">
            <input type="submit" value="odeslat k potvrzení">
        </div>
        <input type="hidden" value="{{$id}}" name="id_kurzu">
      </form>
@endsection