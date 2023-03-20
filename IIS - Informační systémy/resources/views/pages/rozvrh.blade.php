@extends('layouts.app')
@section('content')
<table>
    <tr>
        <td height="50" width="100">
            <b>Day/Period</b>
        </td>
        <td height="50" width="100">
            <b>8:00-8:50</b>
        </td>
        <td height="50" width="100">
            <b>9:00-9:50</b>
        </td>
        <td height="50" width="100">
            <b>10:00-10:50</b>
        </td>
        <td height="50" width="100">
            <b>11:00-11:50</b>
        </td>
        <td height="50" width="100">
            <b>12:00-12:50</b>
        </td>
        <td height="50" width="100">
            <b>13:00-13:50</b>
        </td>
        <td height="50" width="100">
            <b>14:00-14:50</b>
        </td>
        <td height="50" width="100">
            <b>15:00-15:50</b>
        </td>
        <td height="50" width="100">
            <b>16:00-16:50</b>
        </td>
        <td height="50" width="100">
            <b>17:00-17:50</b>
        </td>
        <td height="50" width="100">
            <b>18:00-18:50</b>
        </td>
        <td height="50" width="100">
            <b>19:00-19:50</b>
        </td>
        <td height="50" width="100">
            <b>20:00-20:50</b>
        </td>
        <td height="50" width="100">
            <b>21:00-21:50</b>
        </td>
    </tr>
    @for($j = 1; $j < 6; $j++)
    <tr>

        @switch($j)
            @case(1)
            <td height="50"><b>Pondeli</b></td>
                @break
            @case(2)
            <td height="50"><b>Utery</b></td>
                @break
            @case(3)
            <td height="50"><b>Streda</b></td>
                @break
            @case(4)
            <td height="50"><b>Ctvrtek</b></td>
                @break
            @case(5)
            <td height="50"><b>Patek</b></td>
                @break
        @endswitch
        @php 
        $i = 8;
        @endphp
        @while($i < 22) 
            @php
            $is_in_hour = 0;
            @endphp
        @foreach($termins as $term) 
            @php
            $timestampOD = strtotime($term->od);
            $timestampDO = strtotime($term->do);      
            $hoursOD = date('H', $timestampOD);
            $hoursDO = date('H', $timestampDO) + 1;
            $zkr = App\Models\Kurz::Find($term->id_kurz);
            $colspan = $hoursDO - $hoursOD;
            $is_in_hour = 0;
            @endphp
            @if ($term->den == $j && $hoursOD == $i)
                @php
                $is_in_hour = 1;
                $term->od = "0:00:00";
                $nazev = $term->nazev;
                $zkratka = $zkr->zkratka;
                $typ = $term->typ;
                @endphp
                @break
            @endif
        @endforeach
            @if ($is_in_hour == 1 && $typ != "zkouska")
            @php($i += $colspan)
            <td colspan="{{$colspan}}">{{$nazev}}<br>{{$zkratka}} </td>
            @else
            <td> </td>
            @php($i++)
            @endif
        @endwhile
    </tr>
    @endfor
</table>
@endsection