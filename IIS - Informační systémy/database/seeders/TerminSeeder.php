<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TerminSeeder extends Seeder
{
    public function run()
    {
        DB::table('termins')->insert(array(
            //itu 1-4
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '8:00:00','do' => '9:50:00','den' => 2,'skupina' => 1,'kapacita' => 35,'id_kurz' => 1,'id_mistnost' => 5),
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '8:00:00','do' => '9:50:00','den' => 3,'skupina' => 2,'kapacita' => 35,'id_kurz' => 1,'id_mistnost' => 5),
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '8:00:00','do' => '9:50:00','den' => 2,'skupina' => 3,'kapacita' => 30,'id_kurz' => 1,'id_mistnost' => 5),
            array('nazev' => 'Prednaska','typ' => 'prednaska','popis' => Str::random(10),'od' => '8:00','do' => '9:50:00','den' => 1,'skupina' => 0,'kapacita' => 100,'id_kurz' => 1,'id_mistnost' => 1),
            //ima 5-8
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '8:00:00','do' => '9:50:00','den' => 2,'skupina' => 1,'kapacita' => 35,'id_kurz' => 2,'id_mistnost' => 3),
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '8:00:00','do' => '9:50:00','den' => 3,'skupina' => 2,'kapacita' => 35,'id_kurz' => 2,'id_mistnost' => 3),
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '10:00:00','do' => '11:50:00','den' => 2,'skupina' => 3,'kapacita' => 30,'id_kurz' => 2,'id_mistnost' => 3),
            array('nazev' => 'Prednaska','typ' => 'prednaska','popis' => Str::random(10),'od' => '10:00:00','do' => '11:50:00','den' => 1,'skupina' => 0,'kapacita' => 100,'id_kurz' => 2,'id_mistnost' => 1),
            //ims 9
            array('nazev' => 'Prednaska','typ' => 'prednaska','popis' => Str::random(10),'od' => '18:00:00','do' => '20:50:00','den' => 2,'skupina' => 0,'kapacita' => 100,'id_kurz' => 3,'id_mistnost' => 1),
            //izp 10-15
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '8:00:00','do' => '9:50:00','den' => 2,'skupina' => 1,'kapacita' => 20,'id_kurz' => 4,'id_mistnost' => 6),
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '8:00:00','do' => '9:50:00','den' => 3,'skupina' => 2,'kapacita' => 20,'id_kurz' => 4,'id_mistnost' => 6),
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '8:00:00','do' => '9:50:00','den' => 1,'skupina' => 3,'kapacita' => 20,'id_kurz' => 4,'id_mistnost' => 6),
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '10:00:00','do' => '11:50:00','den' => 1,'skupina' => 4,'kapacita' => 20,'id_kurz' => 4,'id_mistnost' => 6),
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '10:00:00','do' => '11:50:00','den' => 4,'skupina' => 5,'kapacita' => 20,'id_kurz' => 4,'id_mistnost' => 6),
            array('nazev' => 'Prednaska','typ' => 'prednaska','popis' => Str::random(10),'od' => '8:00:00','do' => '9:50:00','den' => 3,'skupina' => 0,'kapacita' =>100,'id_kurz' => 4,'id_mistnost' => 1),
            //isa 16-19
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '8:00:00','do' => '9:50:00','den' => 2,'skupina' => 1,'kapacita' => 35,'id_kurz' => 5,'id_mistnost' => 4),
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '8:00:00','do' => '9:50:00','den' => 3,'skupina' => 2,'kapacita' => 35,'id_kurz' => 5,'id_mistnost' => 4),
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '8:00:00','do' => '9:50:00','den' => 2,'skupina' => 3,'kapacita' => 30,'id_kurz' => 5,'id_mistnost' => 4),
            array('nazev' => 'Prednaska','typ' => 'prednaska','popis' => Str::random(10),'od' => '8:00:00','do' => '9:50:00','den' => 5,'skupina' => 0,'kapacita' => 100,'id_kurz' => 5,'id_mistnost' => 1),
            //ima2 20-23
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '10:00:00','do' => '11:50:00','den' => 2,'skupina' => 1,'kapacita' => 35,'id_kurz' => 6,'id_mistnost' => 7),
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '10:00:00','do' => '11:50:00','den' => 3,'skupina' => 2,'kapacita' => 35,'id_kurz' => 6,'id_mistnost' => 7),
            array('nazev' => 'Cviceni','typ' => 'cviceni','popis' => Str::random(10),'od' => '10:00:00','do' => '11:50:00','den' => 2,'skupina' => 3,'kapacita' => 30,'id_kurz' => 6,'id_mistnost' => 7),
            array('nazev' => 'Prednaska','typ' => 'prednaska','popis' => Str::random(10),'od' => '12:00:00','do' => '13:50:00','den' => 1,'skupina' => 0,'kapacita' => 100,'id_kurz' => 6,'id_mistnost' => 1),
            //izp projekt a zkouska 24-25
            array('nazev' => 'Projekt','typ' => 'projekt','popis' => Str::random(10),'od' => '00:00:00','do' => '00:00:00','den' => 1,'skupina' => 0,'kapacita' => 100,'id_kurz' => 4,'id_mistnost' => 1),
            array('nazev' => 'Zkouska','typ' => 'zkouska','popis' => Str::random(10),'od' => '13:00:00','do' => '13:50:00','den' => 4,'skupina' => 0,'kapacita' => 100,'id_kurz' => 4,'id_mistnost' => 1),
        ));
    }
}
