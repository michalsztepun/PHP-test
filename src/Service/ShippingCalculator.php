<?php

namespace App\Service;

class ShippingCalculator
{
    public function calculateShippingCost($zip): float
    {
        $cost = 0;
        if($zip){
            // Call shipping api to get cost
            $cost = 15;
        }
        return floatval($cost);
    }
}