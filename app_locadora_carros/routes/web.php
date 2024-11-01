<?php
use App\Http\Middleware\ExcludeApiFromCsrf;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CarroController;
use App\Http\Controllers\LocacaoController;
use App\Http\Controllers\ModeloController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Aplicar o middleware diretamente nas rotas de API
Route::prefix('api')->group(function () {
    // Route::get('marca', [MarcaController::class, 'index']);
    // Route::post('marca', [MarcaController::class, 'store']);
    // Route::get('marca/{marca}', [MarcaController::class, 'show']);
    Route::apiResource('marca', MarcaController::class);
    Route::apiResource('cliente', ClienteController::class);
    Route::apiResource('carro', CarroController::class);
    Route::apiResource('locacao', LocacaoController::class);
    Route::apiResource('modelo', ModeloController::class);
});