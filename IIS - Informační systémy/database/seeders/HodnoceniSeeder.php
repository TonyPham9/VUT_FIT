<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HodnoceniSeeder extends Seeder
{   
    public function run()
    {
        DB::table('hodnocenis')->insert(array(
            //vitr
            array('hodnoceni' => 5, 'id_student' => 1, 'id_termindatum' => 43),
            array('hodnoceni' => 5, 'id_student' => 1, 'id_termindatum' => 44),
            array('hodnoceni' => 5, 'id_student' => 1, 'id_termindatum' => 45),
            array('hodnoceni' => 5, 'id_student' => 1, 'id_termindatum' => 46),
            array('hodnoceni' => 5, 'id_student' => 1, 'id_termindatum' => 47),
            array('hodnoceni' => 5, 'id_student' => 1, 'id_termindatum' => 48),
            array('hodnoceni' => 19, 'id_student' => 1, 'id_termindatum' => 49),
            array('hodnoceni' => 45, 'id_student' => 1, 'id_termindatum' => 50)
        ));
    }
}
