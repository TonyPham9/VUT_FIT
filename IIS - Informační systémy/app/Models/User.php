<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "users";
    public $primaryKey = "osobni_cislo";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'datum_narozeni',
        'adresa',
        'phone_number'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function kurzs(){
        return $this->hasMany(Kurz::class,'garant');
    }

    public function studs(){
		return $this->belongsToMany(Kurz::class, 'students', 'osobni_cislo', 'id_kurz')->withPivot('potvrzeni');
	}

    public function lektors(){
        return $this->belongsToMany(Termin::class, 'lektors', 'osobni_cislo', 'id_termin');
    }

    /*public function lektors(){
        return $this->belongsToMany()
    }*/
}
