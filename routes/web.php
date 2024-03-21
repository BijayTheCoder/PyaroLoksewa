<?php

use App\Http\Controllers\Admin\LevelByCategoryController;
use App\Http\Controllers\Importers\ImportExcelController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/migrate', function(){
    Artisan::call('migrate'); 
    echo "migrated successfully";
});

Route::group(['prefix' => 'admin'], function(){
    Voyager::routes();
});

Route::post('categories-import', [ImportExcelController::class, 'importCategories'])->name('categories.import');
Route::post('services-import', [ImportExcelController::class, 'importServices'])->name('services.import');
Route::post('levels-import', [ImportExcelController::class, 'importLevels'])->name('levels.import');
Route::post('groups-import', [ImportExcelController::class, 'importGroups'])->name('groups.import');
Route::post('positions-import', [ImportExcelController::class, 'importPositions'])->name('positions.import');

Route::get('services/get-level', [LevelByCategoryController::class, 'get_level'])->name('services.get-level');
Route::get('levels/get-group', [LevelByCategoryController::class, 'get_group'])->name('levels.get-group');
Route::get('groups/get-position', [LevelByCategoryController::class, 'get_position'])->name('groups.get-position');