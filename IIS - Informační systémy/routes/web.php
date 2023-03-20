<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KurzsController;
use App\Http\Controllers\RozvrhController;
use App\Http\Controllers\StudiumController;
use App\Http\Controllers\RegistraceController;
use App\Http\Controllers\UcitelController;
use App\Http\Controllers\UpravitController;
use App\Http\Controllers\GarantController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('pages/index');
});
Route::get('admin/check_kurz_admin/{id}', [AdminController::class, 'check_kurz_admin'])->name ('check_kurz_admin');
Route::get('admin/create_mistnost', [AdminController::class, 'create_mistnost']);
Route::get('admin/add_mistnost', [AdminController::class, 'add_mistnost']);
Route::get('admin/add_user', [AdminController::class, 'add_user']);
Route::get('admin/check_kurz', [AdminController::class, 'check_kurz']);
Route::get('admin/delete_kurz', [AdminController::class, 'show_kurzs']);
Route::get('admin/show_users', [AdminController::class, 'show_users']);
Route::get('admin/show_mistnosts', [AdminController::class, 'show_mistnosts']);
Route::get('admin/{id}/odstranit', [AdminController::class, 'destroy']);
Route::get('admin/{id}/odstranit_mistnost', [AdminController::class, 'destroy_mistnost']);
Route::get('admin/{id}/odstranit_kurz', [AdminController::class, 'destroy_kurz']);
Route::get('admin/{id}/{id_garant}/zmenit_garanta', [AdminController::class, 'update_garant']);
Route::get('admin/{id}/upravit', [AdminController::class, 'show']);
Route::get('admin/{id}/upravit_mistnost', [AdminController::class, 'show_mistnost']);
Route::get('admin/{id}/admin/update', [AdminController::class, 'update']);
Route::get('admin/{id}/admin/update_mistnost', [AdminController::class, 'update_mistnost']);
Route::get('admin/{id}/admin/update_pswd', [AdminController::class, 'update_pswd']);
Route::resource('admin', AdminController::class);
Route::resource('rozvrh', RozvrhController::class);
Route::get('kurz/add_kurz_student/{id}/{potvrzeni}', [KurzsController::class, 'add_kurz_student'])->name ('add_kurz_student');
Route::get('kurz/rm_kurz_student/{id}', [KurzsController::class, 'rm_kurz_student'])->name ('rm_kurz_student');
Route::resource('kurz', KurzsController::class);
Route::get('studium/{id}/odhlasit', [StudiumController::class, 'destroy']);
Route::resource('studium', StudiumController::class);
Route::get('registrace/{id}/odregistrovat', [RegistraceController::class, 'destroy']);
Route::get('registrace/{id}/registrovat', [RegistraceController::class, 'create']);
Route::get('registrace/filtr', [RegistraceController::class, 'index_filtered']);
Route::resource('registrace', RegistraceController::class);
Route::get('ucitel/garant', [UcitelController::class, 'garant']);
Route::get('ucitel/lektor', [UcitelController::class, 'lektor']);
Route::get('ucitel/lektor_rozvrh', [UcitelController::class, 'lektor_rozvrh']);
Route::get('ucitel/add_kurz', [UcitelController::class, 'add_kurz']);
Route::get('ucitel/kurz_update', [UcitelController::class, 'kurz_update']);
Route::get('ucitel/termin_update', [UcitelController::class, 'termin_update']);
Route::get('ucitel/create_termin', [UcitelController::class, 'create_termin']);
Route::get('ucitel/create_datum', [UcitelController::class, 'create_datum']);
Route::get('ucitel/ulozit_hodnoceni', [UcitelController::class, 'ulozit_hodnoceni']);
Route::get('ucitel/add_lektor_form', [UcitelController::class, 'add_lektor_form']);
Route::get('ucitel/studenti_kurz/{id}', [UcitelController::class, 'studenti_kurz'])->name('studenti_kurz');
Route::get('ucitel/add_lektor/{id}', [UcitelController::class, 'add_lektor'])->name('add_lektor');
Route::get('ucitel/confirm_kurz/{id_kurz}/{id_student}', [UcitelController::class, 'confirm_kurz'])->name('confirm_kurz');
Route::get('ucitel/terminy/{id}', [UcitelController::class, 'terminy'])->name('terminy');
Route::get('ucitel/upravit_kurz/{id}', [UcitelController::class, 'upravit_kurz'])->name('upravit_kurz');
Route::get('ucitel/hodnotit_termin/{id}', [UcitelController::class, 'hodnotit_termin'])->name('hodnotit_termin');
Route::get('ucitel/data_kurz/{id}', [UcitelController::class, 'data_kurz'])->name('data_kurz');
Route::get('ucitel/student_termin/{id}', [UcitelController::class, 'student_termin'])->name('stud_terminu');
Route::get('ucitel/rm_termin/{id}', [UcitelController::class, 'rm_termin'])->name('rm_termin');
Route::get('ucitel/modify_termin/{id}', [UcitelController::class, 'modify_termin'])->name('modify_termin');
Route::get('ucitel/termin_datum/{id}', [UcitelController::class, 'termin_datum'])->name('termin_datum');
Route::get('ucitel/rm_datum/{id}', [UcitelController::class, 'rm_datum'])->name('rm_datum');
Route::get('ucitel/rm_lektor/{id_termin}/{osobni_cislo}', [UcitelController::class, 'rm_lektor'])->name('rm_lektor');
Route::get('ucitel/rm_student/{id_kurz}/{osobni_cislo}', [UcitelController::class, 'rm_student'])->name('rm_student');
Route::get('upravit_profil/update', [UpravitController::class, 'update']);
Route::get('upravit_profil/update_password', [UpravitController::class, 'update_pswd']);
Route::get('studium/{id}/cviceni', [StudiumController::class, 'show_cviceni'])->name('show_cviceni');
Route::get('studium/{id}/projekt', [StudiumController::class, 'show_projekt'])->name('show_projekt');
Route::get('studium/{id}/zkouska', [StudiumController::class, 'show_zkouska'])->name('show_zkouska');
Route::resource('ucitel', UcitelController::class);
Route::resource('upravit_profil',UpravitController::class);
Auth::routes();

