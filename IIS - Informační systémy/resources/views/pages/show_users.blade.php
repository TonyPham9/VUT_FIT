@extends('layouts.app')
@section('content')
<h1>Uživatelé</h1>
<table class="tg">
    <thead>
        <tr>
            <td class="tg-baqh">Jméno</td>
            <td class="tg-0lax">Přijmení</td>
            <td class="tg-0lax">Email</td>
            <td class="tg-0lax">Datum narození</td>
            <td class="tg-0lax">Adresa</td>
            <td class="tg-0lax">Telefoní číslo</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td class="tg-baqh">{{$user->name}}</td>
                <td class="tg-0lax">{{$user->surname}}</td>
                <td class="tg-0lax">{{$user->email}}</td>
                <td class="tg-0lax">{{$user->datum_narozeni}}</td>
                <td class="tg-0lax">{{$user->adresa}}</td>
                <td class="tg-0lax">{{$user->phone_number}}</td>
                <td class="tg-baqh"><a href="/admin/{{$user->osobni_cislo}}/upravit">Upravit uživatele</a></td>
                @if($user->osobni_cislo != 999)
                    <td class="tg-baqh"><a href="/admin/{{$user->osobni_cislo}}/odstranit">Odstranit uživatele</a></td>  
                @else
                    <td class="tg-baqh"></td> 
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
@endsection