<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::apiResource('/member', 
// App\Http\Controllers\MemberController::class);

// Route::apiResource('/instruktur', 
// App\Http\Controllers\InstrukturController::class);

Route::apiResource('/jadwal_umum', 
App\Http\Controllers\JadwalUmumController::class);

// Route::apiResource('/jadwal_harian', 
// App\Http\Controllers\JadwalHarianController::class);

Route::apiResource('/kelas', 
App\Http\Controllers\KelasController::class);

Route::apiResource('/role', 
App\Http\Controllers\RoleController::class);

Route::apiResource('/pegawai', 
App\Http\Controllers\PegawaiController::class);

Route::apiResource('/aktivasi_tahunan', 
App\Http\Controllers\AktivasiTahunanController::class);

// Route::apiResource('/laporan_pendapatan_bulanan', 
// App\Http\Controllers\LaporanPendapatanBulananController::class);

// Route::apiResource('/izin', 
// App\Http\Controllers\IzinController::class);

// Route::apiResource('/deposit_umum', 
// App\Http\Controllers\DepositUmumController::class);

// Route::apiResource('/booking_kelas', 
// App\Http\Controllers\BookingKelasController::class);

Route::get('jadwal_umum', 'App\Http\Controllers\JadwalUmumController@index');
Route::get('jadwal_umum/{id_jadwal_umum}', 'App\Http\Controllers\JadwalUmumController@show');
Route::post('jadwal_umum}', 'App\Http\Controllers\JadwalUmumController@store');
Route::put('jadwal_umum/{id_jadwal_umum}', 'App\Http\Controllers\JadwalUmumController@update');
Route::delete('jadwal_umum/{id_jadwal_umum}', 'App\Http\Controllers\JadwalUmumController@destroy');

Route::get('jadwal_harian', 'App\Http\Controllers\JadwalHarianController@index');
Route::get('jadwal_harian/{id_jadwal_harian}', 'App\Http\Controllers\JadwalHarianController@show');
Route::post('jadwal_harian}', 'App\Http\Controllers\JadwalHarianController@store');
Route::put('jadwal_harian/{id_jadwal_harian}', 'App\Http\Controllers\JadwalHarianController@update');
Route::delete('jadwal_harian/{id_jadwal_harian}', 'App\Http\Controllers\JadwalHarianController@destroy');

Route::get('aktivasi_tahunan', 'App\Http\Controllers\AktivasiTahunanController@index');
Route::get('aktivasi_tahunan/{id_member}', 'App\Http\Controllers\AktivasiTahunanController@show');
Route::post('aktivasi_tahunan}', 'App\Http\Controllers\AktivasiTahunanController@store');
Route::put('aktivasi_tahunan/{id_aktivasi_tahuan}', 'App\Http\Controllers\AktivasiTahunanController@update');
Route::delete('aktivasi_tahunan/{id_aktivasi_tahuan}', 'App\Http\Controllers\AktivasiTahunanController@destroy');

Route::get('izin', 'App\Http\Controllers\IzinController@index');
Route::get('izin/{id_izin}', 'App\Http\Controllers\IzinController@show');
Route::post('izin', 'App\Http\Controllers\IzinController@store');
Route::put('izin/{id_izin}', 'App\Http\Controllers\IzinController@update');
Route::delete('izin/{id_izin}', 'App\Http\Controllers\IzinController@destroy');

Route::get('instruktur', 'App\Http\Controllers\InstrukturController@index');
Route::get('instruktur/{id_instruktur}', 'App\Http\Controllers\InstrukturController@show');
Route::post('instruktur', 'App\Http\Controllers\InstrukturController@store');
Route::put('instruktur/{id_instruktur}', 'App\Http\Controllers\InstrukturController@update');
Route::put('instruktur', 'App\Http\Controllers\InstrukturController@resetTotalTerlambat');
Route::delete('instruktur/{id_instruktur}', 'App\Http\Controllers\InstrukturController@destroy');

Route::get('deposit_umum', 'App\Http\Controllers\DepositUmumController@index');
Route::get('deposit_umum/{id_member}', 'App\Http\Controllers\DepositUmumController@show');
Route::post('deposit_umum', 'App\Http\Controllers\DepositUmumController@store');
Route::put('deposit_umum/{id_deposit_umum}', 'App\Http\Controllers\DepositUmumController@update');
Route::delete('deposit_umum/{id_deposit_umum}', 'App\Http\Controllers\DepositUmumController@destroy');

Route::get('deposit_kelas', 'App\Http\Controllers\DepositKelasController@index');
Route::put('deposit_kelas/expired', 'App\Http\Controllers\DepositKelasController@resetExpiredToday');
Route::get('deposit_kelas/expired', 'App\Http\Controllers\DepositKelasController@expiredToday');
Route::get('deposit_kelas/{id_member}', 'App\Http\Controllers\DepositKelasController@show');
Route::post('deposit_kelas', 'App\Http\Controllers\DepositKelasController@store');
Route::put('deposit_kelas/{id_deposit_kelas}', 'App\Http\Controllers\DepositKelasController@update');
Route::delete('deposit_kelas/{id_deposit_kelas}', 'App\Http\Controllers\DepositKelasController@destroy');

Route::get('member', 'App\Http\Controllers\MemberController@index');
Route::put('member/expired', 'App\Http\Controllers\MemberController@resetExpiredToday');
Route::get('member/expired', 'App\Http\Controllers\MemberController@expiredToday');
Route::get('member/{id_member}', 'App\Http\Controllers\MemberController@show');
Route::post('member', 'App\Http\Controllers\MemberController@store');
Route::put('member/{id_member}', 'App\Http\Controllers\MemberController@update');
Route::delete('member/{id_member}', 'App\Http\Controllers\MemberController@destroy');

Route::get('booking_kelas', 'App\Http\Controllers\BookingKelasController@index');
Route::get('booking_kelas/{id_booking_kelas}', 'App\Http\Controllers\BookingKelasController@show');
Route::post('booking_kelas', 'App\Http\Controllers\BookingKelasController@store');
Route::put('booking_kelas/{id_booking_kelas}', 'App\Http\Controllers\BookingKelasController@update');
Route::delete('booking_kelas/{id_booking_kelas}', 'App\Http\Controllers\BookingKelasController@destroy');

Route::get('booking_gym', 'App\Http\Controllers\BookingGymController@index');
Route::get('booking_gym/{id_booking_gym}', 'App\Http\Controllers\BookingGymController@show');
Route::post('booking_gym', 'App\Http\Controllers\BookingGymController@store');
Route::put('booking_gym/{id_booking_gym}', 'App\Http\Controllers\BookingGymController@update');
Route::delete('booking_gym/{id_booking_gym}', 'App\Http\Controllers\BookingGymController@destroy');


Route::get('sesi_gym', 'App\Http\Controllers\SesiGymController@index');
Route::get('sesi_gym/{id_sesi_gym}', 'App\Http\Controllers\SesiGymController@show');
Route::post('sesi_gym', 'App\Http\Controllers\SesiGymController@store');
Route::put('sesi_gym/{id_sesi_gym}', 'App\Http\Controllers\SesiGymController@update');
Route::delete('sesi_gym/{id_sesi_gym}', 'App\Http\Controllers\SesiGymController@destroy');

Route::get('presensi_instruktur', 'App\Http\Controllers\PresensiInstrukturController@index');
Route::get('presensi_instruktur/{id_presensi_instruktur}', 'App\Http\Controllers\PresensiInstrukturController@show');
Route::post('presensi_instruktur', 'App\Http\Controllers\PresensiInstrukturController@store');
Route::put('presensi_instruktur/{id_presensi_instruktur}', 'App\Http\Controllers\PresensiInstrukturController@update');
Route::delete('presensi_instruktur/{id_presensi_instruktur}', 'App\Http\Controllers\PresensiInstrukturController@destroy');

Route::get('presensi_kelas', 'App\Http\Controllers\PresensiKelasController@index');
Route::get('presensi_kelas/{id_presensi_kelas}', 'App\Http\Controllers\PresensiKelasController@show');
Route::post('presensi_kelas', 'App\Http\Controllers\PresensiKelasController@store');
Route::put('presensi_kelas/{id_presensi_kelas}', 'App\Http\Controllers\PresensiKelasController@update');
Route::delete('presensi_kelas/{id_presensi_kelas}', 'App\Http\Controllers\PresensiKelasController@destroy');

Route::get('presensi_gym', 'App\Http\Controllers\PresensiGymController@index');
Route::get('presensi_gym/{id_presensi_gym}', 'App\Http\Controllers\PresensiGymController@show');
Route::post('presensi_gym', 'App\Http\Controllers\PresensiGymController@store');
Route::put('presensi_gym/presensiMemberGym/{id_presensi_gym}', 'App\Http\Controllers\PresensiGymController@presensiMemberGym');
Route::put('presensi_gym/{id_presensi_gym}', 'App\Http\Controllers\PresensiGymController@update');
Route::delete('presensi_gym/{id_presensi_gym}', 'App\Http\Controllers\PresensiGymController@destroy');


//Laporan 
Route::post('laporan_pendapatan_bulanan', 'App\Http\Controllers\LaporanPendapatanBulananController@store');
Route::post('aktivitas_kelas_bulanan', 'App\Http\Controllers\AktivitasKelasBulananController@store');
Route::post('aktivitas_gym_bulanan', 'App\Http\Controllers\AktivitasGymBulananController@store');
Route::post('kinerja_instruktur_bulanan', 'App\Http\Controllers\KinerjaInstrukturBulananController@store');

//Login
// Route::post('register', 'App\Http\Controllers\RegisterController@store');
// Route::post('login', 'App\Http\Controllers\LoginController@store');

Route::post('login', 'App\Http\Controllers\AuthController@login');

// Route::post('/register', [RegisterController::class , 'store']);

// Route::post('/login', [LoginController::class , 'check']);




