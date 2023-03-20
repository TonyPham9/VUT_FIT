<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lektor extends Model
{
    use HasFactory;
    protected $table = "lektors";
    public $primaryKey = "id_lektor";

    protected $fillable = [
        'id_lektor',
        'id_termin',
        'osobni_cislo'
    ];   
}
