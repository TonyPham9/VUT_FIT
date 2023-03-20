<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Termin extends Model
{
    use HasFactory;

    protected $table = "termins";
    public $primaryKey = "id_termin";

    protected $fillable = [
        'id_termin',
        'nazev',
        'typ',
        'popis',
        'od',
        'do',
        'den',
        'skupina',
        'kapacita',
        'id_kurz',
        'id_mistnost'
    ];   

    /**
     * Get the Kurz that owns the Termin
     *
     * @return \Illuminate\Kurzbase\Eloquent\Relations\BelongsTo
     */
    public function kurz(){
        return $this->belongsTo(Kurz::class);
    }

    public function mistnost(){
        return $this->belongsTo(Mistnost::class);
    }

    public function termindatums(){
        return $this->hasMany(Termindatum::class, 'id_termin');
    }

    public function lektors(){
        return $this->belongsToMany(User::class, 'lektors', 'id_termin', 'osobni_cislo');
    }

    public function students(){
        return $this->belongsToMany(Student::class, 'student_kurzs', 'id_termin', 'id_student');
    }
}
