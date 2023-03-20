@extends('layouts.app')
@section('content')
<h2>schvalene</h2>    
<table class="tg">
    <thead>
      <tr>
        <td class="tg-0lax">osobní číslo</td>
        <td class="tg-baqh">Jméno</td>
        <td class="tg-0lax">Příjmení</td>
        <td class="tg-0lax">email</td>
      </tr>
      @foreach($students as $student)
            @if ($student->pivot->potvrzeni == 1)
              <tr>
                <td class="tg-baqh">{{$student->osobni_cislo}}</td>
                <td class="tg-0lax">{{$student->name}}</td>
                <td class="tg-0lax">{{$student->surname}}</td>
                <td class="tg-0lax">{{$student->email}}</td>
                <td class="tg-0lax"><a href="{{ route('rm_student', [$student->pivot->id_kurz,$student->pivot->osobni_cislo]) }}">odstranit studenta</a></td>
              </tr>    
            @endif
      @endforeach
    </thead>
  </table>
  <h2>čeká na schválení</h2>    
  <table class="tg">
    <thead>
        <tr>
          <td class="tg-0lax">osobní číslo</td>
          <td class="tg-baqh">Jméno</td>
          <td class="tg-0lax">Příjmení</td>
          <td class="tg-0lax">email</td>
        </tr>
        @foreach($students as $student)
              @if ($student->pivot->potvrzeni == 0)
                <tr>
                  <td class="tg-baqh">{{$student->osobni_cislo}}</td>
                  <td class="tg-0lax">{{$student->name}}</td>
                  <td class="tg-0lax">{{$student->surname}}</td>
                  <td class="tg-0lax">{{$student->email}}</td>
                  <td class="tg-0lax"> <a href="{{ route('confirm_kurz', [$student->pivot->id_kurz,$student->pivot->osobni_cislo]) }}">potvrdit příhlášení do kurzu</a></td>
                </tr>    
              @endif
        @endforeach
    </thead>
  </table>
@endsection