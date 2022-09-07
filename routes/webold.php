<?php

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

use Illuminate\Http\Request;

Route::get('/','HomeController@redirect');
Route::get('/structures','Structure\StructureController@index')->name('structures'); 
Route::get('/levels','Level\LevelController@index')->name('levels');
Route::get('/agents','Agent\AgentController@index')->name('agents');
Route::get('/indicators','Indicator\IndicatorController@index')->name('indicators');
Route::get('/infos','Infos\InfosController@index')->name('infos');
Route::get('/domains','Domain\DomainController@index')->name('domains');
Route::get('/subdomains','SubDomain\SubDomainController@index')->name('subdomains');

Route::get('/profile','ProfileController@index')->name('profile');
Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('home');
Route::get('/dash/stat','HomeController@stat');
Route::prefix('create')->group(function(){
    Route::get('structure','Structure\StructureController@form')->name('form.structure');
    Route::get('level','Level\LevelController@form')->name('form.level');
    Route::get('agent','Agent\AgentController@form')->name('form.agent');
    Route::get('indicator','Indicator\IndicatorController@form')->name('form.indicator');
    Route::get('info','Infos\InfosController@form')->name('form.info');
    Route::get('domain','Domain\DomainController@form')->name('form.domain');
    Route::get('subdomain','SubDomain\SubDomainController@form')->name('form.subdomain');

    Route::post('structure','Structure\StructureController@create')->name('create.structure');
    Route::post('level','Level\LevelController@create')->name('create.level');
    Route::post('level.type','Level\LevelController@createType')->name('create.levelType');
    Route::post('agent','Agent\AgentController@create')->name('create.agent');
    Route::post('indicator','Indicator\IndicatorController@create')->name('create.indicator');
    Route::post('info','Infos\InfosController@create')->name('create.info');
    Route::post('domain','Domain\DomainController@create')->name('create.domain');
    Route::post('subdomain','SubDomain\SubDomainController@create')->name('create.subdomain');
});

Route::prefix('update')->group(function(){
    Route::post('profile','ProfileController@update')->name('update.profile');
    Route::post('structure','Structure\StructureController@update')->name('update.structure');
    Route::post('level','Level\LevelController@update')->name('update.level');
    Route::post('type','Level\LevelController@updateType')->name('update.levelType');
    Route::post('agent','Agent\AgentController@update')->name('update.agent');
    Route::post('indicator','Indicator\IndicatorController@update')->name('update.indicator');
    Route::post('info','Infos\InfosController@update')->name('update.info');
    Route::post('domain','Domain\DomainController@update')->name('update.domain');
    Route::post('subdomain','SubDomain\SubDomainController@update')->name('update.subdomain');

});

//Route::get('update/tmp/level/{id}','Level\LevelController@prepareUpdate')->name('prepareUpdate.level');
Route::group(['prefix' => '/update/tmp/' ], function(){
    Route::get('/structure/{id}','Structure\StructureController@prepareUpdate')->name('prepareUpdate.structure');
    Route::get('/level/{id}','Level\LevelController@prepareUpdate')->name('prepareUpdate.level');
    Route::get('/type/{id}','Level\LevelController@prepareTypeUpdate')->name('prepareUpdate.type');
    Route::get('/agent/{id}','Agent\AgentController@prepareUpdate')->name('prepareUpdate.agent');
    Route::get('/indicator/{id}','Indicator\IndicatorController@prepareUpdate')->name('prepareUpdate.indicator');
    Route::get('/info/{id}','Infos\InfosController@prepareUpdate')->name('prepareUpdate.info');
    Route::get('/domain/{id}','Domain\DomainController@prepareUpdate')->name('prepareUpdate.domain');
    Route::get('/subdomain/{id}','SubDomain\SubDomainController@prepareUpdate')->name('prepareUpdate.subdomain');
});
 
Route::group(['prefix'=>'/delete'], function(){
    Route::get('/structure/{id}','Structure\StructureController@delete')->name('delete.structure');
    Route::get('/level/{id}','Level\LevelController@delete')->name('delete.level');
    Route::get('/type/{id}','Level\LevelController@deleteType')->name('delete.type');
    Route::get('/agent/{id}','Agent\AgentController@delete')->name('delete.agent');
    Route::get('/indicator/{id}','Indicator\IndicatorController@delete')->name('delete.indicator');
    Route::get('/info/{id}','Infos\InfosController@delete')->name('delete.info');
    Route::get('/domain/{id}','Domain\DomainController@delete')->name('delete.domain');
    Route::get('/subdomain/{id}','SubDomain\SubDomainController@delete')->name('delete.subdomain');
});

Route::group(['prefix' => '/read'], function(){ 
    Route::get('/{id}/{subdomain}','ReadController@readIndicators')->name('read.data'); 
    Route::get('/graph/{indicator}/{subdomain}','ReadController@viewChart')->name('graph.data');
    Route::post('/graph/{indicator}/{subdomain}','ReadController@viewChart')->name('graph.data.post');
    Route::get('/export/{indicator}/{subdomain}','ReadController@export')->name('export.data');
});

Route::post('/build/indicators','MiscController@setBuiltSUI');
Route::post('/domain/subdomains','MiscController@getSubdomains');
Route::post('/subdomain/indicators','MiscController@getIndicators');
Route::post('/indicator/levels/{infoId?}','MiscController@getLevels');
Route::post('/indicator/types','MiscController@getTypes');
Route::post('/struct/subdomains','Structure\StructureController@showMoreSubD');
Route::post('/level/indicators','Level\LevelController@showMoreIndicator');
Route::post('/info/details','Infos\InfosController@showMoreDetails');
Route::post('/info/levels','Infos\InfosController@showMoreLevel');
Route::get('/welcome','ReadController@viewChart');


Route::prefix('import')->group(function(){
    Route::get('data/generate','Infos\InfosController@importForm')->name('form.import');
    Route::post('data/generate','Infos\InfosController@buildImportMask')->name('info.genimport');
    
    Route::get('data/file','Infos\InfosController@importFile')->name('form.import_file');
    Route::post('data/file','Infos\InfosController@import')->name('info.import');

    Route::get('domain/file','Domain\DomainController@formImport')->name('domain.import');
    Route::get('subdomain/file','SubDomain\SubDomainController@formImport')->name('subdomain.import');
    Route::get('indicator/file','Indicator\IndicatorController@formImport')->name('indicator.import');
    Route::get('type/file','Level\LevelController@formImportType')->name('type.import');
    Route::get('level/file','Level\LevelController@formImport')->name('level.import');
    Route::get('structure/file','Structure\StructureController@formImport')->name('structure.import');;

    Route::post('domain/file','Domain\DomainController@import');
    Route::post('subdomain/file','SubDomain\SubDomainController@import');
    Route::post('indicator/file','Indicator\IndicatorController@import');
    Route::post('type/file','Level\LevelController@importType');
    Route::post('level/file','Level\LevelController@import');
    Route::post('structure/file','Structure\StructureController@import');

});


Route::prefix('export')->group(function(){
    Route::get('data','Infos\InfosController@exportForm')->name('form.export');
    Route::post('data','Infos\InfosController@export')->name('info.export');
    
    Route::get('domain','Domain\DomainController@export')->name('domain.export');

    Route::get('subdomain/file','SubDomain\SubDomainController@export')->name('subdomain.export');
    Route::get('indicator/file','Indicator\IndicatorController@export')->name('indicator.export');
    Route::get('type/file','Level\LevelController@exportType')->name('type.export');
    Route::get('level/file','Level\LevelController@export')->name('level.export'); 
    Route::get('structure/file','Structure\StructureController@export')->name('structure.export');

});
