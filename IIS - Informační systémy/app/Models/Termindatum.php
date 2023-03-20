<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerminDatum extends Model
{
    use HasFactory;
    protected $table = "termindatums";
    public $primaryKey = "id_termindatum";

    protected $fillable = [
        'id_termindatum',
        'typ',
        'popis',
        'minbody',
        'maxbody',
        'datum',
        'id_termin'
    ];   

    /**
     * Get the Kurz that owns the Termin
     *
     * @return \Illuminate\Kurzbase\Eloquent\Relations\BelongsTo
     */
    public function termin(){
        return $this->belongsTo(Termin::class);
    }

    public function students(){
        return $this->belongsToMany(Student::class, 'hodnocenis', 'id_termindatum', 'id_student')->withPivot('hodnoceni');
    }
}
