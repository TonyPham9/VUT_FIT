<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class StudentKurzSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('student_kurzs')->insert(array(
            array('id_student' => 1,'id_termin' => 12),
            array('id_student' => 1,'id_termin' => 24),
            array('id_student' => 1,'id_termin' => 25)
        ));
    }
}
