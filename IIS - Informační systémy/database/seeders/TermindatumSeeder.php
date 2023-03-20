<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TermindatumSeeder extends Seeder
{
    public function run()
    {
        DB::table('termindatums')->insert(array(
            //itu 1
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 1),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 1),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 1),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 1),
            //itu 2
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 2),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 2),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 2),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 2),
            //itu 3
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 3),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 3),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 3),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 3),
            //ima 1
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 5),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 5),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 5),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 5),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 5),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 5),
            //ima 2
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 6),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 6),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 6),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 6),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 6),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 6),
            //ima 3
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 7),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 7),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 7),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 7),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 7),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 7),
            //izp 1
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 10),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 10),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 10),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 10),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 10),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 10),
            //izp 2
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 11),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 11),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 11),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 11),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 11),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 11),
            //izp 3
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 12),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 12),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 12),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 12),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 12),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 12),
            //izp projekt
            array('typ' => 'projekt','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 20, 'datum' => Today(), 'id_termin' => 24),
            //izp zkouska
            array('typ' => 'zkouska','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 50, 'datum' => Today(), 'id_termin' => 25),
            //izp 4
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 13),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 13),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 13),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 13),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 13),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 13),
            //izp 5
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 14),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 14),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 14),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 14),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 14),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 14),
            //isa 1
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 16),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 16),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 16),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 16),
            //isa 2
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 17),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 17),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 17),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 17),
            //isa 3
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 18),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 18),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 18),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 18),
            //ima2 1
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 20),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 20),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 20),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 20),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 20),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 20),
            //ima2 2
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 21),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 21),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 21),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 21),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 21),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 21),
            //ima2 3
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 22),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 22),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 22),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 22),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 22),
            array('typ' => 'cviceni','popis' => Str::random(10), 'minbody' => 0, 'maxbody' => 5, 'datum' => Today(), 'id_termin' => 22),
        ));
    }
}
