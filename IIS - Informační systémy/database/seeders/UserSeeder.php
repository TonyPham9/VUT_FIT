<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(array(
            array('name' => 'Pepa','surname' => 'Sláma','email' => 'admin@seznam.cz','password' => Hash::make('admin'),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Vít','surname' => 'Janeček','email' => 'student@seznam.cz','password' => Hash::make('student'),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Radek','surname' => 'Šerejch','email' => 'garant@seznam.cz','password' => Hash::make('garant'),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Tony','surname' => 'Pham','email' => 'lektor@seznam.cz','password' => Hash::make('lektor'),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Pavel','surname' => Str::random(5),'email' => Str::random(5).'@gmail.com','password' => Str::random(5),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Karel','surname' => Str::random(5),'email' => Str::random(5).'@gmail.com','password' => Str::random(5),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Zdenek','surname' => Str::random(5),'email' => Str::random(5).'@gmail.com','password' => Str::random(5),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Tomas','surname' => Str::random(5),'email' => Str::random(5).'@gmail.com','password' => Str::random(5),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Pepa','surname' => Str::random(5),'email' => Str::random(5).'@gmail.com','password' => Str::random(5),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Stepan','surname' => Str::random(5),'email' => Str::random(5).'@gmail.com','password' => Str::random(5),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Adam','surname' => Str::random(5),'email' => Str::random(5).'@gmail.com','password' => Str::random(5),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Simon','surname' => Str::random(5),'email' => Str::random(5).'@gmail.com','password' => Str::random(5),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Tereza','surname' => Str::random(5),'email' => Str::random(5).'@gmail.com','password' => Str::random(5),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Lenka','surname' => Str::random(5),'email' => Str::random(5).'@gmail.com','password' => Str::random(5),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Karolina','surname' => Str::random(5),'email' => Str::random(5).'@gmail.com','password' => Str::random(5),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Vojta','surname' => Str::random(5),'email' => Str::random(5).'@gmail.com','password' => Str::random(5),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Milan','surname' => Str::random(5),'email' => Str::random(5).'@gmail.com','password' => Str::random(5),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
            array('name' => 'Honza','surname' => Str::random(5),'email' => Str::random(5).'@gmail.com','password' => Str::random(5),'datum_narozeni' => Today(), 'adresa' => 'moje', 'phone_number' => 111222333),
        ));
    }
}
