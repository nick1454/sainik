<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\FeeStructure;
use App\Models\Classes;
use App\Models\Payment;
use App\Models\Student;
use DB;

class FeeController extends Controller
{
    public function addPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student' => 'required|integer',
            'class' => 'required|integer',
            'amount' =>'required|decimal:0,2',
            'discount' => 'required|decimal:0,2',
            'receipt' => 'required|file|mimes:jpg,png,jpeg',
            'month' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'message' => $validator->errors()->first(),
                'data' => ''
            ]);
        }

        $class = Classes::find($request->class);

        if (!$class) {
            return response()->json([
               'status' => '404',
               'message' => 'Class not found',
                'data' => ''
            ]);
        }

        $feeStructure = FeeStructure::where('class_id', $class->id)->orderByDesc('id')->first();

        if (!$feeStructure) {
            return response()->json([
               'status' => '404',
               'message' => 'Fee Structure not found',
                'data' => ''
            ]);
        }

        $lastId = Payment::max('id');
        $lastId = $lastId ? $lastId + 1 : 1 ;

        // $balance = $this->getPreviousFeeBalance($request->student);
        // $balance = json_decode($balance)->data;
        $currentTime = Carbon::now()->timestamp;
        $receipt = '';

        if ($request->has('receipt') && $request->receipt) {
            $file = $request->file('receipt');
            $extension = $file->getClientOriginalExtension(); // Get the file extension
            $fileName = $lastId . '_' . $currentTime.'.'. $extension;
            $receipt = $file->storeAs('/fee-receipt', $fileName,'public');
        }

        $year = Carbon::now()->year;

        if ($request->month === 1 || $request->month === 2 || $request->month === 3) {
            $year = $year - 1;
        }

        $discount = $request->discount;

        // $balance = $this->getFeeStructureAmount($feeStructure) + $balance - $request->amount;

        Payment::create([
            'user_id' => auth()->user()->id,
            'type' => 'fee',
            'status' => 'paid',
            'paid_by' => $request->student,
            'amount' => $request->amount,
            'discount' => $discount,
            'receipt_url' => $receipt,
            'fee_structure_id' => $feeStructure->id,
            'payment_for_month' => $request->month,
            'payment_for_year' => $year,
            // 'balance_payment' => $balance,
            'balance_payment' => 0
        ]);

        return response()->json([
            'status' => '200',
            'message' => 'Fee added successfully',
            'data' => ''
        ]);
    }

    public function addFeePreFill(Request $request,$id = '')
    {
        $students = Student::select(DB::raw("id as value,concat(name,' - ',father_name) as text"))->where('class', $id)->get();
        $classes = Classes::select('id','value')->get();
        $currentMonth = date('n');
        $classId = $id;

        $feeStructure = FeeStructure::where('class_id', $classId)->orderByDesc('id')->first();

        if (!$feeStructure) {
            $feeStructure = '';
        } else {
            $feeStructure = $feeStructure->toArray();
            if (date('n') !== 4) {
                $feeStructure['admission_fee'] = 0;
                $feeStructure['annual_fee'] = 0;
            }
        }

        if (!$students->count()) {
            $students = [];
        }

        if (!$classes->count()) {
            $classes = [];
        }

        $months = [
            ["value" => 1, "text" => "January"],
            ["value" => 2, "text" => "February"],
            ["value" => 3, "text" => "March"],
            ["value" => 4, "text" => "April"],
            ["value" => 5, "text" => "May"],
            ["value" => 6, "text" => "June"],
            ["value" => 7, "text" => "July"],
            ["value" => 8, "text" => "August"],
            ["value" => 9, "text" => "September"],        
            ["value" => 10, "text" => "October"],
            ["value" => 11, "text" => "November"],
            ["value" => 12, "text" => "December"],
        ];

        return response()->json([
            'status' => '200',
            'message' => 'Student List',
            'data' => compact('students', 'months', 'currentMonth','classes','classId','feeStructure')
        ]);
    }

    public function getPreviousFeeBalance($studentId)
    {
        if (!$studentId) {
            return response()->json([
                'status' => '200',
                'message' => 'Student Fee Balance',
                'data' => 0
            ]);
        }

        $lastPayment = [];
        $student = Student::whereStatus('active')->find($studentId);

        if (!$student) {
            return response()->json([
                'status' => '404',
                'message' => 'Student not found',
                'data' => 0
            ]);
        }

        $startingMonth = Carbon::parse($student->created_at)->month;
        $startingYear = Carbon::parse($student->created_at)->year;
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;
        $balance = 0;
        $payments = [];

        for (;$startingYear <= $thisYear;++$startingYear) {
            for (;$startingMonth <= 12;++$startingMonth) {
                $class = Classes::where('value',$student->class)->first();
                if (!$class) {
                    return response()->json([
                       'status' => '404',
                       'message' => 'Class not found',
                        'data' => 0
                    ]);
                }

                $feeStructure = FeeStructure::where('class_id',$class->id)
                    ->whereMonth('created_at','<=',$startingMonth)
                    ->orderByDesc('id')
                    ->first();

                $payments[$startingMonth.$startingYear]['sql'] = FeeStructure::where('class_id',$class->id)
                ->whereMonth('created_at','<=',$startingMonth)
                ->orderByDesc('id')->toSql();

                $payments[$startingMonth.$startingYear]['STDUENT_ID'] = $student->id;
                $payments[$startingMonth.$startingYear]['CLASS_ID'] = $class->id;
                $payments[$startingMonth.$startingYear]['MONTH'] = $startingMonth;
                $payments[$startingMonth.$startingYear]['YEAR'] = $startingYear;
                $payments[$startingMonth.$startingYear]['fee_structure'] = $feeStructure ? $this->getFeeStructureAmount($feeStructure,$startingMonth) : '';

                $payment = Payment::where('paid_by',$studentId)
                ->where('payment_for_month',$startingMonth)
                ->where('payment_for_year',$startingYear)
                ->get();

                $amount = 0;
                $discount = 0;
                $feeStructure = 0;

                foreach ($payment as $pay) {
                    $discount = $pay && $pay->discount ? $pay->discount : 0;
                    $feeStructure = $payments[$startingMonth.$startingYear]['fee_structure'];
                    $amount += ($pay && $pay->amount) ? $pay->amount : 0;
                    $discount += ($feeStructure / 100) * $discount;
                }

                $payments[$startingMonth.$startingYear]['amount'] = $amount;
                $payments[$startingMonth.$startingYear]['discount'] = $discount;
                $payments[$startingMonth.$startingYear]['balance'] = $feeStructure - $discount - $amount;
                $balance += $payments[$startingMonth.$startingYear]['balance'];

                if ($startingMonth === $thisMonth && $startingYear === $thisYear) {
                    break;
                }

                if ($startingMonth === 12) {
                    $startingMonth = 1;
                    break;
                }
            }
        }

        return response()->json([
            'status' => '200',
            'message' => 'Student Fee Balance',
            'data' => $balance
        ]);
    }

}