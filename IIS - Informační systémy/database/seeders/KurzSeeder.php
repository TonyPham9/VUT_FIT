<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KurzSeeder extends Seeder
{
    
    public function run()
    {
        DB::table('kurzs')->insert(array(
            array('zkratka' => 'ITU','nazev' => Str::random(5),'pocet_mist' => 100,'kredity' => 5,'typ' => 'povinne', 'jazyk' => 'cestina', 'zpusob_zakonceni' => 'zkouska', 'popis' => Str::random(15), 'cena' => 100, 'schvaleno' => true, 'auto_add' => true, 'garant' => 1001),
            array('zkratka' => 'IMA','nazev' => Str::random(5),'pocet_mist' => 100,'kredity' => 5,'typ' => 'povinne', 'jazyk' => 'cestina', 'zpusob_zakonceni' => 'zkouska', 'popis' => Str::random(15), 'cena' => 100, 'schvaleno' => true, 'auto_add' => true, 'garant' => 1001),
            array('zkratka' => 'IMS','nazev' => Str::random(5),'pocet_mist' => 100,'kredity' => 5,'typ' => 'povinne', 'jazyk' => 'cestina', 'zpusob_zakonceni' => 'zkouska', 'popis' => Str::random(15), 'cena' => 100, 'schvaleno' => true, 'auto_add' => true, 'garant' => 1001),
            array('zkratka' => 'IZP','nazev' => Str::random(5),'pocet_mist' => 100,'kredity' => 5,'typ' => 'povinne', 'jazyk' => 'cestina', 'zpusob_zakonceni' => 'zkouska', 'popis' => Str::random(15), 'cena' => 100, 'schvaleno' => true, 'auto_add' => true, 'garant' => 1001),
            array('zkratka' => 'ISA','nazev' => Str::random(5),'pocet_mist' => 100,'kredity' => 5,'typ' => 'povinne', 'jazyk' => 'cestina', 'zpusob_zakonceni' => 'zkouska', 'popis' => Str::random(15), 'cena' => 100, 'schvaleno' => true, 'auto_add' => true, 'garant' => 1006),
            array('zkratka' => 'IMA2','nazev' => Str::random(5),'pocet_mist' => 100,'kredity' => 5,'typ' => 'povinne', 'jazyk' => 'cestina', 'zpusob_zakonceni' => 'zkouska', 'popis' => Str::random(15), 'cena' => 100, 'schvaleno' => true, 'auto_add' => true, 'garant' => 1006)
        ));
    }
}
