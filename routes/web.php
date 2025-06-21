<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/site/clear-cache', ['App\Http\Controllers\AdminController','clearcache'])->name('clear.cache');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', ['App\Http\Controllers\AuthController','form'])->name('login.form');
    Route::post('/login', ['App\Http\Controllers\AuthController','login'])->name('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', ['App\Http\Controllers\AuthController','logout'])->name('logout');
    Route::match(['get','post'],'/change-password', ['App\Http\Controllers\AuthController','changePassword'])->name('change.password');
});

Route::get('/not-found', function() {
    return view('errors.404');
})->name('error.notfound');

Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', ['App\Http\Controllers\AdminController','adminDashboard'])->name('admin.dashboard');

    Route::get('/admin/test', ['App\Http\Controllers\AdminController','showTestForm'])->name('admin.test.form');
    Route::post('/admin/test/excel', ['App\Http\Controllers\AdminController','createTestUsingExcel'])->name('admin.test.excel');
    Route::get('/admin/test/list', ['App\Http\Controllers\AdminController','showTestList'])->name('admin.test.list');
    Route::get('/admin/{id}/test', ['App\Http\Controllers\AdminController','showTestForm'])->name('admin.test.form.edit');
    Route::post('/admin/test/store', ['App\Http\Controllers\AdminController','saveTest'])->name('admin.test.save');
    Route::post('/admin/test/{id}/store', ['App\Http\Controllers\AdminController','saveTest'])->name('admin.test.update');
    Route::get('/admin/test/{id?}/destroy', ['App\Http\Controllers\AdminController','deleteTest'])->name('admin.test.destroy');

    Route::get('/admin/student/form', ['App\Http\Controllers\AdminController','showStudentForm'])->name('admin.student.form');
    Route::get('/admin/{id?}/student', ['App\Http\Controllers\AdminController','showStudentForm'])->name('admin.student.form.edit');
    Route::post('/admin/student/store', ['App\Http\Controllers\AdminController','storeStudent'])->name('admin.student.add');
    Route::post('/admin/student/{id?}/store', ['App\Http\Controllers\AdminController','storeStudent'])->name('admin.student.update');
    Route::get('/admin/student/{id?}/destroy', ['App\Http\Controllers\AdminController','deleteStudent'])->name('admin.student.destroy');
    Route::post('/admin/student/excel/add', ['App\Http\Controllers\AdminController','addStudentsFromExcel'])->name('admin.student.excel.add');


    Route::get('/admin/exam', ['App\Http\Controllers\AdminController','showExamForm'])->name('admin.exam.form');
    Route::post('/admin/exam/store', ['App\Http\Controllers\AdminController','saveExam'])->name('admin.exam.save');
    Route::get('/admin/{id}/exam', ['App\Http\Controllers\AdminController','showExamForm'])->name('admin.exam.form.edit');
    Route::post('/admin/exam/{id?}/store', ['App\Http\Controllers\AdminController','saveExam'])->name('admin.exam.update');
    Route::get('/admin/exam/{id?}/destroy', ['App\Http\Controllers\AdminController','deleteExam'])->name('admin.exam.destroy');

    Route::get('/admin/students/reports', ['App\Http\Controllers\AdminController','students'])->name('admin.reports.students');
    Route::get('/admin/exam/reports', ['App\Http\Controllers\AdminController','exam'])->name('admin.reports.exam.list');
    Route::get('/admin/reports/student-test-attempt', ['App\Http\Controllers\AdminController','studentTestAttempt'])->name('admin.reports.studenttestattempt');
    Route::get('/admin/reports/student-test/{examid?}', ['App\Http\Controllers\AdminController','studentTest'])->name('admin.reports.studenttest');
    Route::get('/admin/reports/student-test-question', ['App\Http\Controllers\AdminController','studentTestQuestion'])->name('admin.reports.studenttestquestion');

    Route::get('/admin/feestructure', ['App\Http\Controllers\FeeStructureController','index'])->name('admin.feestructure.form');
    Route::post('/admin/feestructure/store', ['App\Http\Controllers\FeeStructureController','saveFeeStructure'])->name('admin.feestructure.save');
    Route::get('/admin/{id}/feestructure', ['App\Http\Controllers\FeeStructureController','index'])->name('admin.feestructure.form.edit');
    Route::post('/admin/feestructure/{id?}/store', ['App\Http\Controllers\FeeStructureController','saveFeeStructure'])->name('admin.feestructure.update');
    Route::get('/admin/feestructure/{id?}/destroy', ['App\Http\Controllers\FeeStructureController','deleteFeeStructure'])->name('admin.feestructure.destroy');
});

Route::middleware(['student'])->group(function () {
    Route::get('/student/dashboard', ['App\Http\Controllers\StudentController','index'])->name('student.dashboard');
    Route::any('/student/select-exam', ['App\Http\Controllers\StudentController','selectExam'])->name('student.exam');
    Route::any('/student/start-exam', ['App\Http\Controllers\StudentController','startExam'])->name('student.start.exam');
    Route::any('/student/fetch-question', ['App\Http\Controllers\StudentController','fetchQuestions'])->name('student.fetch.question');
    Route::any('/student/submit-test', ['App\Http\Controllers\StudentController','submitTest'])->name('student.submit.test');
    Route::any('/student/test-timer', ['App\Http\Controllers\StudentController','testTimer'])->name('student.test.timer');

    Route::get('/student/reports/student-test-attempt', ['App\Http\Controllers\StudentController','studentTestAttempt'])->name('student.reports.studenttestattempt');
    Route::get('/student/reports/{examid}/student-test-question', ['App\Http\Controllers\StudentController','studentTestQuestion'])->name('student.reports.studenttestquestion');
});