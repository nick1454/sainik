<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function getFeeStructureAmount($feeStructureModel, $date = '')
    {
        $date = $date ?? date('n');
        $feeStructure = $feeStructureModel->toArray();

        if (!$feeStructure) {
            return 0;
        }

        if ($date !== 4 && $feeStructure) {
            $feeStructure['admission_fee'] = 0;
            $feeStructure['annual_fee'] = 0;
        }

        return  $feeStructure['admission_fee'] 
        + $feeStructure['annual_fee']
        + $feeStructure['tution_fee']
        + $feeStructure['transport_fee'];
    }

    
}
