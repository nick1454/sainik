<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;

class StudentController extends Controller
{
    public function students(Request $request)
    {
        $students = Student::all();
        if (!$students->count()) {
            $students = [];
        }

        return response()->json([
            'status' => '200',
            'message' => 'Student List',
            'data' => $students
        ]);
    }
}
