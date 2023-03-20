<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kurz extends Model
{
    use HasFactory;

    protected $table = "kurzs";
    public $primaryKey = "id_kurz";

    protected $fillable = [
        'id_kurz',
        'zkratka',
        'nazev',
        'pocet_mist',
        'kredity',
        'typ',
        'jazyk',
        'zpusob_zakonceni',
        'popis',
        'cena',
        'schvaleno',
        'auto_add',
        'garant'
    ];

    protected $hidden = [
        'schvaleno',
    ];
    
    public function garant(){
        return $this->belongsTo(User::class);
    }

    public function students(){
        return $this->belongsToMany(User::class, 'students', 'id_kurz', 'osobni_cislo')->withPivot('potvrzeni');
    }

    public function termins(){
        return $this->hasMany(Termin::class, 'id_kurz');
    }
}
