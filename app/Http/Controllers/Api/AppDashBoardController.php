<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Student;
use Auth;

class AppDashBoardController extends Controller
{
    public function dashboardTiles(Request $request)
    {
        $totalStudents = 0;
        $totalPaymentThisMonth = 0;
        $paymentsToday = 0;
        $payingStudentThisMonth = 0;

        $thisMonth = date('m');
        $thisYear = date('Y');
        $totalPaymentThisMonth = Payment::with('studentInfo')->whereMonth('created_at', $thisMonth)
            ->whereYear('created_at', $thisYear)
            ->sum('amount');

        $paymentsToday = Payment::with('studentInfo')->whereDate('created_at', date('Y-m-d'))->sum('amount');
        $totalStudents = Student::count();
        $payingStudentThisMonth = Payment::with('studentInfo')->whereMonth('created_at', $thisMonth)
        ->whereYear('created_at', $thisYear)
        ->distinct('paid_by')
        ->select('paid_by')
        ->groupBy('paid_by')
        ->get();
        

        return [
            'status' => '200',
            'message' => 'Dashboard Tiles',
            'data' => [
                'totalStudents' => $totalStudents ?? 0,
                'totalPaymentThisMonth' => $totalPaymentThisMonth ?? 0,
                'paymentsToday' => $paymentsToday ?? 0,
                'payingStudentThisMonth' => $payingStudentThisMonth->count() ?? 0
            ]
        ];
    }

    public function getPaymentList(Request $request)
    {   
        if (Auth::user()->role == 'student') {
            $payments = Payment::with('student_info')->where('paid_by', Auth::user()->id)->get();
        } else {
            $payments = Payment::with('studentInfo')->get();
        }

        return response()->json([
            'status' => '200',
            'message' => 'Payment List',
            'data' => $payments->count() ? $payments : []
        ]);
    }
}
