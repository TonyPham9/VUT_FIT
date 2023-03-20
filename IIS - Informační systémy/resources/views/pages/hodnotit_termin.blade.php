@extends('layouts.app')
@section('content')
    <h1>Hodnotit studenty</h1>
    <form action="/ucitel/ulozit_hodnoceni">
    <table class="tg">
        <thead>
            <tr>
                <td class="tg-baqh">jméno</td>
                <td class="tg-0lax">příjmení</td>
                <td class="tg-0lax">login</td>
                <td class="tg-0lax">body</td>
            </tr>
        
        @foreach($students as $student)
        <tr>
            <p hidden="true">{{$user = App\Models\User::find($student->osobni_cislo)}}</p>
            <td class="tg-baqh">{{$user->name}}</td>
            <td class="tg-baqh">{{$user->surname}}</td>
            <td class="tg-baqh">{{$user->email}}</td>
            
            <td class="tg-baqh"><input type="number" name="{{$student->id_student}}" value="{{$student->pivot->hodnoceni}}"
                 min="0" max="{{$termin->maxbody}}"></td>
        </tr>
        @endforeach
        
        </thead>
    </table>
    <div class="form-group">
        <input type="submit" value="uložit hodnocení">
    </div>
    <p hidden="true"><input type="text" name="id_termindatum" value="{{$id}}"></p>
    </form>
@endsection