<?php

use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use Infureal\Http\Controllers\GuiController;
use DaveJamesMiller\RouteBrowser\AssetController;
use DaveJamesMiller\RouteBrowser\RouteListController;
use Arcanedev\LogViewer\Http\Controllers\LogViewerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;

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
    return view('welcome');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('password/confirm', [ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
Route::post('password/confirm', [ConfirmPasswordController::class, 'confirm']);

Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::middleware(['auth', 'check.dev'])->group(function () {
    Route::get('/artisan', [GuiController::class, 'index'])->name('gui.index');
    Route::post('{command}', [GuiController::class, 'run'])->name('gui.run');

    Route::get('/apilogs', 'AWT\Http\Controllers\ApiLogController@index')->name("apilogs.index");
    Route::delete('/apilogs/delete', 'AWT\Http\Controllers\ApiLogController@delete')->name("apilogs.deletelogs");

    Route::prefix(config('route-browser.path', 'routes'))
        ->group(static function () {
            Route::get('/', RouteListController::class)
                ->name('route-browser.list');

            Route::get('assets/{path}', AssetController::class)
                ->where('path', '.*')
                ->name('route-browser.asset');
        });

    Route::name('log-viewer::')->prefix('log-viewer')->group(function () {
        Route::get('/', [LogViewerController::class, 'index'])
            ->name('dashboard'); // log-viewer::dashboard

        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', [LogViewerController::class, 'listLogs'])
                ->name('list'); // log-viewer::logs.list

            Route::delete('delete', [LogViewerController::class, 'delete'])
                ->name('delete'); // log-viewer::logs.delete

            Route::prefix('{date}')->group(function () {
                Route::get('/', [LogViewerController::class, 'show'])
                    ->name('show'); // log-viewer::logs.show

                Route::get('download', [LogViewerController::class, 'download'])
                    ->name('download'); // log-viewer::logs.download

                Route::get('{level}', [LogViewerController::class, 'showByLevel'])
                    ->name('filter'); // log-viewer::logs.filter

                Route::get('{level}/search', [LogViewerController::class, 'search'])
                    ->name('search'); // log-viewer::logs.search
            });
        });
    });

    $enabled = config('laravelapiexplorer.enabled');
    if ($enabled) {
        $prefix = config('laravelapiexplorer.route');
        Route::namespace('\NetoJose\LaravelApiExplorer')->prefix($prefix)->name('laravelapiexplorer.')->group(function () {
            Route::get('/', 'LaravelApiExplorerController@getView')->name('view');
            Route::get('info', 'LaravelApiExplorerController@getInfo')->name('info');
            Route::get('assets/{file}', 'LaravelApiExplorerController@getAsset')->where('file', '^([a-z0-9_\-\.]+).(js|css|svg)$')->name('asset');
        });
    }

    /**
     * |-----------------------------------------
     * |  Prequel Web Routes /prequel or via config.
     * |-----------------------------------------
     * |
     * | Separate from web route to avoid user configured path messing up the Prequel-API.
     * |
     */
    Route::namespace("Protoqol\Prequel\Http\Controllers")
        ->middleware(config("prequel.middleware"))
        ->prefix(config("prequel.path"))
        ->name("prequel.")
        ->group(function () {
            Route::get("/", "PrequelController@index")->name("index");
        });

    /**
    * |-----------------------------------------
    * |  Prequel API Routes /prequel-api
    * |-----------------------------------------
    * |
    * | Separate from web route to avoid user configured path messing up the Prequel-API.
    * |
    */
    Route::namespace("Protoqol\Prequel\Http\Controllers")
        ->middleware(config("prequel.middleware"))
        ->prefix("prequel-api")
        ->name("prequel.")
        ->group(function () {
            // Get database status, includes number of migrations, avg. queries per second, open tables etc.
            Route::get("status", "DatabaseActionController@status");

            // Get latest Prequel version
            Route::get("version", "PrequelController@version");

            // Update Prequel to latest version
            Route::post("update", "PrequelController@autoUpdate");

            // Database related routes
            Route::prefix("database")->group(function () {
                // Default data retrieval
                Route::get(
                    "get/{database}/{table}",
                    "DatabaseController@getTableData"
                );
                Route::get("count/{database}/{table}", "DatabaseController@count");
                Route::get(
                    "find/{database}/{table}/{column}/{type}/{value}",
                    "DatabaseController@findInTable"
                );

                // MigrationAction, run or reset
                Route::get(
                    "migrations/run",
                    "DatabaseActionController@runMigrations"
                );
                Route::get(
                    "migrations/reset",
                    "DatabaseActionController@resetMigrations"
                );

                // Get information related to management functionality, ex. has model/factory/seeder etc.
                Route::get(
                    "info/{database}/{table}",
                    "DatabaseActionController@getInfoAboutTable"
                );

                // Get default values for new row form, ex. next AI-ID, date-times etc.
                Route::get(
                    "defaults/{database}/{table}",
                    "DatabaseActionController@getDefaultsForTable"
                );

                // Insert new row
                Route::post(
                    "insert/{database}/{table}",
                    "DatabaseActionController@insertNewRow"
                );

                // Controller Actions
                Route::get(
                    "controller/{database}/{table}/generate",
                    "DatabaseActionController@generateController"
                );

                // Factory Actions
                Route::get(
                    "factory/{database}/{table}/generate",
                    "DatabaseActionController@generateFactory"
                );

                // Model Actions
                Route::get(
                    "model/{database}/{table}/generate",
                    "DatabaseActionController@generateModel"
                );

                // Resource Actions
                Route::get(
                    "resource/{database}/{table}/generate",
                    "DatabaseActionController@generateResource"
                );

                // Seeder Actions
                Route::get(
                    "seeder/{database}/{table}/generate",
                    "DatabaseActionController@generateSeeder"
                );
                Route::get(
                    "seeder/{database}/{table}/run",
                    "DatabaseActionController@runSeeder"
                );

                // Raw SQL Query
                Route::post(
                    "sql/{database}/{table}/run",
                    "DatabaseActionController@runSql"
                );
            });
        });

});
