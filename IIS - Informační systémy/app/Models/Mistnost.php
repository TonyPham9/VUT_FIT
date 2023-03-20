<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mistnost extends Model
{
    use HasFactory;

    protected $table = "mistnosts";
    public $primaryKey = "id_mistnost";

    protected $fillable = [
        'id_mistnost',
        'nazev',
        'budova',
        'kapacita',
        'popis'        
    ];   

    public function termins(){
        return $this->hasMany(Termin::class);
    }
}
