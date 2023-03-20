@extends('layouts.app')
@section('content')
<h1>Studenti termínu</h1>
    <table class="tg">
    <thead>
        <tr>
        <td class="tg-baqh">osobní číslo</td>
        <td class="tg-0lax">jméno</td>
        <td class="tg-0lax">příjmení</td>
        <td class="tg-0lax">email</td>
        <td class="tg-0lax">hodnoceni</td>
        </tr>
        @foreach($students as $student)
            <p hidden="true">{{$user = App\Models\User::find($student->osobni_cislo)}}</p>
            <tr>
                <td class="tg-baqh">{{$student->osobni_cislo}}</td>
                <td class="tg-baqh">{{$user->name}}</td>
                <td class="tg-baqh">{{$user->surname}}</td>
                <td class="tg-baqh">{{$user->email}}</td>
                <div hidden="true">
                    {{$hodnoceni=0;}}
                    @foreach($datums as $datum)
                        {{$students2 = $datum->students}}
                        @foreach ($students2 as $student2)
                            @if ($student2->pivot->id_student == $student->id_student)
                                {{$hodnoceni += $student2->pivot->hodnoceni}}
                            @endif
                        @endforeach
                    @endforeach
                </div>
                <td class="tg-baqh">{{$hodnoceni}}</td>
            </tr>
        @endforeach
            
    </thead>
    </table>
@endsection