<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table = "students";
    public $primaryKey = "id_student";

    protected $fillable = [
        'id_student',
        'potvrzeni',
        'osobni_cislo',
        'id_kurz'
    ];   

    public function termindatums(){
        return $this->belongsToMany(Termindatum::class, 'hodnocenis', 'id_student', 'id_termindatum')->withPivot('hodnoceni');
    }

    public function termins(){
        return $this->belongsToMany(Termin::class, 'student_kurzs', 'id_student', 'id_termin');
    }
}
