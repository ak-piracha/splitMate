<?php

use Illuminate\Support\Facades\Route;

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

// use L5Swagger\GeneratorFactory;

// Route::get('/generate-docs', function () {
//     if (app()->environment('local')) {
//         $factory = new GeneratorFactory();
//         $factory->make()->generateDocs();
//         return 'Swagger docs generated';
//     }
//     return 'Not allowed in this environment';
// });

Route::get('/', function () {
    return view('welcome');
});
