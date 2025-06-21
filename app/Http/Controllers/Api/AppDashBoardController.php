<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Student;
use Carbon\Carbon;

class AppDashBoardController extends Controller
{
    public function dashboardTiles(Request $request)
    {
        $totalStudents = 0;
        $totalPaymentThisMonth = 0;
        $paymentsToday = 0;
        $payingStudentThisMonth = 0;

        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        $totalPaymentThisMonth = Payment::with('studentInfo')->where('payment_for_month', $thisMonth)
            ->where('payment_for_year', $thisYear)
            ->sum('amount');

        $paymentsToday = Payment::with('studentInfo')->whereDate('created_at', date('Y-m-d'))->sum('amount');
        $totalStudents = Student::count();
        $payingStudentThisMonth = Payment::with('studentInfo')
            ->whereMonth('created_at', $thisMonth)
            ->whereYear('created_at', $thisYear)
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
        if (auth()->user()->role->name === 'student') {
            $payments = Payment::with('studentInfo')->where('paid_by', auth()->user()->id)->orderBy('id','desc')->get();
        } else {
            $payments = Payment::with('studentInfo')->orderBy('id','desc')->get();
        }

        return response()->json([
            'status' => '200',
            'message' => 'Payment List',
            'user' => auth()->user(),
            'data' => $payments->count() ? $payments : []
        ]);
    }

    public function getPaymentDetails(Request $request, $id = '')
    {
        if (!$id) {
            return response()->json([
               'status' => '400',
               'message' => 'Payment Id is required'
            ]);
        }

        $payment = '';

        if (auth()->user()->role == 'student') {
            $payment = Payment::with('studentInfo','feeStructure')->whereId($id)->where('paid_by', auth()->user()->id)->first();
        } else {
            $payment = Payment::with('studentInfo','feeStructure')->whereId($id)->first();
        }

        $total_fee = $payment->feeStructure ? $this->getFeeStructureAmount($payment->feeStructure,$payment->payment_for_month) : '';

        return response()->json([
            'status' => '200',
            'message' => 'Payment List',
            'data' => [
                'payment' => $payment ? $payment : '',
                'total_fee' => $total_fee
            ]
        ]);
    }
}
