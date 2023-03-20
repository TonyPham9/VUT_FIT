<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LektorSeeder extends Seeder
{    
    public function run()
    {
        DB::table('lektors')->insert(array(
            //itu
            array('osobni_cislo' => 1009, 'id_termin' => 1),
            array('osobni_cislo' => 1002, 'id_termin' => 2),
            array('osobni_cislo' => 1002, 'id_termin' => 3),
            array('osobni_cislo' => 1001, 'id_termin' => 4),
            //ima
            array('osobni_cislo' => 1008, 'id_termin' => 5),
            array('osobni_cislo' => 1002, 'id_termin' => 6),
            array('osobni_cislo' => 1002, 'id_termin' => 7),
            array('osobni_cislo' => 1001, 'id_termin' => 8),
            //ims
            array('osobni_cislo' => 1001, 'id_termin' => 9),
            //izp
            array('osobni_cislo' => 1002, 'id_termin' => 10),
            array('osobni_cislo' => 1002, 'id_termin' => 11),
            array('osobni_cislo' => 1002, 'id_termin' => 12),
            array('osobni_cislo' => 1007, 'id_termin' => 13),
            array('osobni_cislo' => 1007, 'id_termin' => 14),
            array('osobni_cislo' => 1001, 'id_termin' => 15),
            //isa
            array('osobni_cislo' => 1004, 'id_termin' => 16),
            array('osobni_cislo' => 1001, 'id_termin' => 17),
            array('osobni_cislo' => 1005, 'id_termin' => 18),
            array('osobni_cislo' => 1006, 'id_termin' => 19),
            //ima2
            array('osobni_cislo' => 1002, 'id_termin' => 20),
            array('osobni_cislo' => 1002, 'id_termin' => 21),
            array('osobni_cislo' => 1002, 'id_termin' => 22),
            array('osobni_cislo' => 1006, 'id_termin' => 23),
        ));
    }
}
