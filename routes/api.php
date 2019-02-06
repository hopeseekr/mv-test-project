<?php

use App\Company;

Route::get('companies/{id}', function ($id) {
    // Get company with necessary relationships
});

Route::post('companies', 'CompaniesController@store');
Route::patch('companies/{id}', 'CompaniesController@update');
