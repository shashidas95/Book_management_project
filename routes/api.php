<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\BookController;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

// Read-only access is public
Route::get('books', [BookController::class, 'index']);
Route::get('books/{id}', [BookController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Requires Sanctum Token)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn(Request $request) => $request->user());

    /*
    |----------------------------------------------------------------------
    | Library Actions (Available to all authenticated Members)
    |----------------------------------------------------------------------
    */
    Route::post('books/{id}/borrow', [BookController::class, 'borrow']);
    Route::post('books/{id}/return', [BookController::class, 'return']);

    /*
    |----------------------------------------------------------------------
    | Administrative Routes (Restricted by Role)
    |----------------------------------------------------------------------
    */

    // Only Admins can Delete
    Route::delete('books/{id}', [BookController::class, 'destroy'])
        ->middleware('can:admin-only');
    // routes/api.php
    // Route::delete('books/{id}', [BookController::class, 'destroy'])
    //     ->middleware('role:admin');
    // Route::put('books/{id}', [BookController::class, 'update'])
    //     ->middleware('role:admin,librarian');
    // Admins AND Librarians can Create/Update
    // We use a group to apply the 'can:manage-books' gate (which you'd define in a Policy or Gate)
    Route::middleware('can:update-books')->group(function () {
        Route::post('books', [BookController::class, 'store']);
        Route::put('books/{id}', [BookController::class, 'update']);
        Route::patch('books/{id}', [BookController::class, 'update']);
    });
});
