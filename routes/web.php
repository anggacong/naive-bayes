<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataXController;
use App\Http\Controllers\DataThreadController;
use App\Http\Controllers\PreprocessingXController;
use App\Http\Controllers\PreprocessingThreadController;
use App\Http\Controllers\UnigramXController;
use App\Http\Controllers\UnigramThreadController;
use App\Http\Controllers\NaiveBayesTrainingController;
use App\Http\Controllers\NaiveBayesPengujianController;
use App\Http\Controllers\NaiveBayesEvaluasiController;
use App\Http\Controllers\KamusNormalisasiController;
use App\Http\Controllers\StopwordController;
use Illuminate\Support\Facades\Route;


// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Data X
Route::resource('data-x', DataXController::class)->except(['show']);
Route::post('data-x/import', [DataXController::class, 'import'])->name('data-x.import');
Route::post('data-x/destroy-all', [DataXController::class, 'destroyAll'])->name('data-x.destroy-all');

// Data Thread
Route::resource('data-thread', DataThreadController::class)->except(['show']);
Route::post('data-thread/import', [DataThreadController::class, 'import'])->name('data-thread.import');
Route::post('data-thread/destroy-all', [DataThreadController::class, 'destroyAll'])->name('data-thread.destroy-all');

// Preprocessing X
Route::get('preprocessing/x', [PreprocessingXController::class, 'index'])->name('preprocessing.x');
Route::post('preprocessing/x/proses', [PreprocessingXController::class, 'proses'])->name('preprocessing.x.proses');
Route::post('preprocessing/x/reset', [PreprocessingXController::class, 'reset'])->name('preprocessing.x.reset');

// Preprocessing Thread
Route::get('preprocessing/thread', [PreprocessingThreadController::class, 'index'])->name('preprocessing.thread');
Route::post('preprocessing/thread/proses', [PreprocessingThreadController::class, 'proses'])->name('preprocessing.thread.proses');
Route::post('preprocessing/thread/reset', [PreprocessingThreadController::class, 'reset'])->name('preprocessing.thread.reset');

// Unigram X
Route::get('unigram/x', [UnigramXController::class, 'index'])->name('unigram.x');
Route::post('unigram/x/hitung', [UnigramXController::class, 'hitung'])->name('unigram.x.hitung');
Route::post('unigram/x/reset', [UnigramXController::class, 'reset'])->name('unigram.x.reset');

// Unigram Thread
Route::get('unigram/thread', [UnigramThreadController::class, 'index'])->name('unigram.thread');
Route::post('unigram/thread/hitung', [UnigramThreadController::class, 'hitung'])->name('unigram.thread.hitung');
Route::post('unigram/thread/reset', [UnigramThreadController::class, 'reset'])->name('unigram.thread.reset');

// Naive Bayes - Pelatihan Model
Route::get('naive-bayes/training', [NaiveBayesTrainingController::class, 'index'])->name('naive-bayes.training');
Route::post('naive-bayes/training/latih', [NaiveBayesTrainingController::class, 'latih'])->name('naive-bayes.training.latih');
Route::post('naive-bayes/training/reset', [NaiveBayesTrainingController::class, 'reset'])->name('naive-bayes.training.reset');

// Naive Bayes - Pengujian
Route::get('naive-bayes/pengujian', [NaiveBayesPengujianController::class, 'index'])->name('naive-bayes.pengujian');
Route::post('naive-bayes/pengujian/batch', [NaiveBayesPengujianController::class, 'batch'])->name('naive-bayes.pengujian.batch');
Route::post('naive-bayes/pengujian/prediksi', [NaiveBayesPengujianController::class, 'prediksi'])->name('naive-bayes.pengujian.prediksi');

// Naive Bayes - Evaluasi
Route::get('naive-bayes/evaluasi', [NaiveBayesEvaluasiController::class, 'index'])->name('naive-bayes.evaluasi');

// Kamus Normalisasi (CRUD: index, store, update, destroy)
Route::get('kamus-normalisasi', [KamusNormalisasiController::class, 'index'])->name('kamus-normalisasi.index');
Route::post('kamus-normalisasi', [KamusNormalisasiController::class, 'store'])->name('kamus-normalisasi.store');
Route::put('kamus-normalisasi/{id}', [KamusNormalisasiController::class, 'update'])->name('kamus-normalisasi.update');
Route::delete('kamus-normalisasi/{id}', [KamusNormalisasiController::class, 'destroy'])->name('kamus-normalisasi.destroy');

// Stopwords (CRUD: index, store, destroy)
Route::get('stopwords', [StopwordController::class, 'index'])->name('stopwords.index');
Route::post('stopwords', [StopwordController::class, 'store'])->name('stopwords.store');
Route::delete('stopwords/{id}', [StopwordController::class, 'destroy'])->name('stopwords.destroy');


