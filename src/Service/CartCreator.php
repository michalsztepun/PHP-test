<?php

namespace App\Service;

use App\Model\Address;
use App\Model\Cart;
use App\Model\Customer;
use App\Model\Item;


class CartCreator
{
    public function setCustomerName(Cart $cart, string $firstName, string $lastName): void
    {
        $customer = $cart->getCustomer();
        if(!$customer)$customer = new Customer();
        $customer->setFirstName($firstName);
        $customer->setLastName($lastName);
        $cart->setCustomer($customer);
    }

    public function addCustomerAddress(Cart $cart, array $addressArray): void
    {
        $address = new Address();
        $address->setLine1($addressArray['line1']);
        if($addressArray['line2'])$address->setLine2($addressArray['line2']);
        $address->setCity($addressArray['city']);
        $address->setState($addressArray['state']);
        $address->setZip($addressArray['zip']);

        $customer = $cart->getCustomer();
        if(!$customer){
            $customer = new Customer();
            $cart->setCustomer($customer);
        }
        $customer->addAddress($address);
    }

    public function addItem(Cart $cart, array $itemArray): void
    {
        $item = new Item();
        $item->setId(intval($itemArray['id']));
        $item->setName($itemArray['name']);
        $item->setQuantity(intval($itemArray['quantity']));
        $item->setPrice(floatval($itemArray['price']));
        $cart->addItem($item);
    }

    public function setAddress(Cart $cart, Address $address): void
    {
        $cart->setAddress($address);
    }
}