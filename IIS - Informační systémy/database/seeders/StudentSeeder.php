<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run()
    {
        DB::table('students')->insert(array(

            array('potvrzeni' => true,'osobni_cislo' => 1000,'id_kurz' => 4),
            array('potvrzeni' => true,'osobni_cislo' => 1000,'id_kurz' => 5),
            array('potvrzeni' => true,'osobni_cislo' => 1000,'id_kurz' => 6),
            array('potvrzeni' => true,'osobni_cislo' => 1000,'id_kurz' => 1),
            array('potvrzeni' => true,'osobni_cislo' => 1000,'id_kurz' => 2),
            array('potvrzeni' => true,'osobni_cislo' => 1000,'id_kurz' => 3),
            array('potvrzeni' => true,'osobni_cislo' => 1015,'id_kurz' => 6),
            array('potvrzeni' => true,'osobni_cislo' => 1012,'id_kurz' => 1),
            array('potvrzeni' => true,'osobni_cislo' => 1012,'id_kurz' => 2),
            array('potvrzeni' => true,'osobni_cislo' => 1013,'id_kurz' => 3),
            array('potvrzeni' => true,'osobni_cislo' => 1011,'id_kurz' => 4),
            array('potvrzeni' => true,'osobni_cislo' => 1014,'id_kurz' => 5),
        ));
    }
}
