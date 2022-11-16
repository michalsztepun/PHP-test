<?php

namespace App\Service;

use App\Model\Cart;
use App\Service\ShippingCalculator;


class CartCalculator
{
    private $taxPercent;

    private ShippingCalculator $shippingCalculator;

    public function __construct(int $taxPercent, ShippingCalculator $shippingCalculator)
    {
        $this->taxPercent = $taxPercent;
        $this->shippingCalculator = $shippingCalculator;
    }

    public function getCartSubtotal(Cart $cart): float
    {
        $subtotal = 0;
        $items = $cart->getItems();
        foreach ($items as $item){
            $subtotal += floatval($item->getPrice() * $item->getQuantity());
        }

        return floatval($subtotal);
    }

    public function getCartTaxTotal(Cart $cart): float
    {
        $subtotal = 0;
        $items = $cart->getItems();
        foreach ($items as $item){
            $subtotal += ($item->getPrice() * ($this->taxPercent/100)) * $item->getQuantity();
        }

        return floatval($subtotal);
    }

    public function getCartShippingPrice(Cart $cart): float
    {
        $cost = 0;
        $address = $cart->getAddress();
        if($address){
            $cost = $this->shippingCalculator->calculateShippingCost($address->getZip());
        }

        return floatval($cost);
    }

    public function getTaxPercent(): int
    {
        return intval($this->taxPercent);
    }
}