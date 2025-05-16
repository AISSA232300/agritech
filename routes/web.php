<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;

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

// Public routes
Route::get('/', function () {
    return view('index');
})->name('home');

// CSRF Token route for AJAX requests
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

// Authentication routes (only for API)
Route::post('/api/login', [LoginController::class, 'login'])->name('api.login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Direct HTML routes
Route::get('/login', function () {
    return File::get(public_path() . '/login.html');
});

Route::get('/dashboard', function () {
    return File::get(public_path() . '/dashboard.html');
});

Route::get('/dashboard_employe', function () {
    return File::get(public_path() . '/dashboard_employe.html');
});

Route::get('/profile', function () {
    return File::get(public_path() . '/profile.html');
});

Route::get('/ravageurs', function () {
    return File::get(public_path() . '/ravageurs.html');
});

// API routes
Route::match(['get', 'post', 'put', 'delete'], '/api/pivots.php', function () {
    return require_once public_path() . '/api/pivots.php';
});

Route::get('/planning', function () {
    return File::get(public_path() . '/planification.html');
});

// API routes for authentication
Route::post('/api/auth.php', function (Request $request) {
    $action = $request->input('action');

    if ($action === 'login') {
        $username = $request->input('username');
        $password = $request->input('password');

        // Find user in database
        $user = \App\Models\User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ]);
        }

        // Check password
        if (password_verify($password, $user->password)) {
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role->name
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Mot de passe incorrect'
            ]);
        }
    }

    return response()->json([
        'success' => false,
        'message' => 'Action non reconnue'
    ]);
});
