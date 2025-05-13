<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Payment;
use Auth;

class FeeController extends Controller
{
    public function addPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student' => 'required|integer',
            'amount' => 'required|decimal:0,2',
            'discount' => 'required|decimal:0,2',
            'receipt' => 'required|file',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'message' => $validator->errors()->first(),
                'data' => ''
            ]);
        }

        $amount = $request->amount;
        $discount = $request->discount;
        $latestId = Payment::max('id') ?? 1 ;
        $receipt = '';

        if ($request->has('receipt') && $request->receipt) {
            $receipt = $request->receipt;
            $file = $request->file('receipt');
            $fileName = $latestId . '_' . str_replace(' ','_',$file->getClientOriginalName());
            $receipt = $file->storeAs('/fee-receipt', $fileName);
        }

        Payment::create([
            'user_id' => auth()->user()->id,
            'type' => 'fee',
            'status' => 'paid',
            'paid_by' => $request->student,
            'amount' => $amount,
            'discount' => $discount,
            'receipt_url' => $receipt
        ]);

        return response()->json([
            'status' => '200',
            'message' => 'Fee added successfully',
            'data' => ''
        ]);
    }

    public function getPaymentList(Request $request)
    {   

        if (auth()->user()->role == 'student') {
            $payments = Payment::with('student_info')->where('paid_by', auth()->user()->id)->get();
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
