<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeeStructure;
use App\Models\Classes;

class FeeStructureController extends Controller
{
    public function index(Request $request,$id = '')
    {
        $feeStructure = FeeStructure::all();
        $fee = '';

        if ($id != '') {
            $fee = FeeStructure::find($id);
        }

        $classes = Classes::get();
        return view('feestructure.form', compact('feeStructure','classes','fee'));
    }

    public function saveFeeStructure(Request $request,$id = '')
    {
        $request->validate([
            'class' => ['required'],
            'admission_fee' => ['required'],
            'annual_fee' => ['required'],
            'tution_fee' => ['required'],
            'transport_fee' => ['required'],
        ]);

        if ($id) {
            $exam = FeeStructure::find($id);
            if ($exam) {
                $exam = $exam->update([
                    'class_id' => $request->class,
                    'admission_fee' => $request->admission_fee,
                    'annual_fee' => $request->annual_fee,
                    'tution_fee' => $request->tution_fee,
                    'transport_fee' => $request->transport_fee,
                ]);

                return back()->with('success','Updated Successfully.');
            }

        }

        $exam = FeeStructure::create([
            'class_id' => $request->class,
            'admission_fee' => $request->admission_fee,
            'annual_fee' => $request->annual_fee,
            'tution_fee' => $request->tution_fee,
            'transport_fee' => $request->transport_fee,
        ]);

        return back()->with('success','Added Successfully.');
    }

    public function deleteFeeStructure($id)
    {
        if (strtolower(auth()->user()->role->name) != 'admin') {
            return back()->with('error','Unauthorized Operation.');
        }

        if (!$id) {
            return back()->with('error','Cannot not Delete.');
        }

        $test = FeeStructure::find($id);

        if ($test && $test->delete()) {
            return redirect()->route('admin.feestructure.form')->with('success','deleted sucessfully');
        }

        return back()->with('success','Unable to Delete');
    }
}
