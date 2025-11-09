<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Books\BookController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\Books\CategoryController;
use App\Http\Controllers\Books\BorrowingController;
use App\Http\Controllers\Communities\BranchLibraryController;
use App\Http\Controllers\Communities\CommentCommunityController;
use App\Http\Controllers\Communities\ImagePostCommunityController;
use App\Http\Controllers\Communities\LikeCommunityController;
use App\Http\Controllers\Communities\PostCommunityController;
use App\Http\Controllers\Communities\VideoPostCommunityController;

// Health check
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is running',
        'timestamp' => now()
    ]);
});

// Auth Routes (Public)
Route::prefix('v1/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Auth - Protected
    Route::prefix('v1/auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });

    // Books Management
    Route::apiResource('books', BookController::class);
    Route::apiResource('categories', CategoryController::class);

    // Users Management
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    // Borrowing Management
    Route::prefix('borrowing')->group(function () {
        Route::get('book', [BorrowingController::class, 'index']);
        Route::post('book', [BorrowingController::class, 'store']);
        Route::put('book/{id}', [BorrowingController::class, 'update']);
        Route::get('book/{id}', [BorrowingController::class, 'show']);
        Route::delete('book/{id}', [BorrowingController::class, 'destroy']);
    });

    // Return Book (separate endpoint)
    Route::put('return/book/{id}', [BorrowingController::class, 'update']);

    // Roles Management
    Route::apiResource('roles', RoleController::class);

    // Notifications
    Route::apiResource('notifications', NotificationsController::class);

    // Community Features (if ControlController handles communities)
    Route::prefix('community')->group(function () {
        Route::apiResource('branch-library', BranchLibraryController::class);
        Route::apiResource('post', PostCommunityController::class);
        Route::apiResource('video-post', VideoPostCommunityController::class);
        Route::apiResource('image-post', ImagePostCommunityController::class);
        Route::apiResource('like', LikeCommunityController::class);
        Route::apiResource('comment', CommentCommunityController::class);
    });
});