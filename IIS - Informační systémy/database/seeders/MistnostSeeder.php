<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MistnostSeeder extends Seeder
{
    public function run()
    {
        DB::table('mistnosts')->insert(array(
            array('nazev' => 'D105','budova' => 'D', 'kapacita' => 300, 'popis' => Str::random(10)),
            array('nazev' => 'D206','budova' => 'D', 'kapacita' => 50, 'popis' => Str::random(10)),
            array('nazev' => 'D207','budova' => 'D', 'kapacita' => 50, 'popis' => Str::random(10)),
            array('nazev' => 'A113','budova' => 'A', 'kapacita' => 35, 'popis' => Str::random(10)),
            array('nazev' => 'D112','budova' => 'A', 'kapacita' => 35, 'popis' => Str::random(10)),
            array('nazev' => 'N103','budova' => 'N', 'kapacita' => 35, 'popis' => Str::random(10)),
            array('nazev' => 'N203','budova' => 'N', 'kapacita' => 35, 'popis' => Str::random(10)),
        ));
    }
}
