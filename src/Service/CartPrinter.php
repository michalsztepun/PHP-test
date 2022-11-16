<?php

namespace App\Service;

use App\Model\Address;
use App\Model\Cart;
use App\Model\Item;
use Symfony\Component\Console\Output\OutputInterface;


class CartPrinter
{
    private $cartCalculator;

    public function __construct(CartCalculator $cartCalculator)
    {
        $this->cartCalculator = $cartCalculator;
    }

    public function displayCustomerAddresses(Cart $cart, OutputInterface $output): void
    {
        $addresses = $this->getCustomerAddresses($cart);
        if(!empty($addresses)) {
            $i = 0;
            foreach ($addresses as $address) {
                ++$i;
                $output->writeln(['', 'Address ' . $i . ':']);
                $this->displaySingleAddress($address, $output);
            }
        } else {
            $output->writeln('Customer has no addresses');
        }
    }

    public function getCustomerAddresses(Cart $cart): array
    {
        $addresses = array();
        $customer = $cart->getCustomer();
        if($customer){
            $addresses = $customer->getAddresses();
        }

        return $addresses;
    }

    public function displayCartCustomer(Cart $cart, OutputInterface $output): void
    {
        $customer = $cart->getCustomer();
        if($customer){
            $output->writeln('Customer - ' . $customer->getFirstName() . ' ' . $customer->getLastName());
        } else {
            $output->writeln('No customer set');
        }
    }

    public function displayCartAddress(Cart $cart, OutputInterface $output): void
    {
        $address = $cart->getAddress();
        if($address){
            $output->writeln('Address:');
            $this->displaySingleAddress($address, $output);
        } else {
            $output->writeln('No address set');
        }
    }

    public function displayCartItems(Cart $cart, OutputInterface $output): void
    {
        $items = $cart->getItems();
        if($items){
            $output->writeln('Items:');
            $i = 0;
            foreach($items as $item){
                ++$i;
                $output->writeln(['','Item ' . $i]);
                $this->displaySingleItem($item, $output);
            }
        } else {
            $output->writeln('No items in cart');
        }
    }

    public function displayCartTotals(Cart $cart, OutputInterface $output): void
    {
        $output->writeln(['']);
        $output->writeln('Subtotal $' . number_format($this->cartCalculator->getCartSubtotal($cart),2));
        $output->writeln('Tax ' . $this->cartCalculator->getTaxPercent() . '%');
        $output->writeln('Tax Value $' . number_format($this->cartCalculator->getCartTaxTotal($cart),2));
        $output->writeln('Shipping $' . number_format($this->cartCalculator->getCartShippingPrice($cart),2));

        $total =
            $this->cartCalculator->getCartSubtotal($cart) +
            $this->cartCalculator->getCartTaxTotal($cart) +
            $this->cartCalculator->getCartShippingPrice($cart);

        $output->writeln('Total $' . number_format($total,2));

    }

    private function displaySingleAddress(Address $address, OutputInterface $output): void
    {
        $output->writeln('Line 1 - ' . $address->getLine1());
        if ($address->getLine2()) $output->writeln('Line 2 - ' . $address->getLine2());
        $output->writeln('City - ' . $address->getCity());
        $output->writeln('State - ' . $address->getState());
        $output->writeln('Zip Code - ' . $address->getZip());
    }

    private function displaySingleItem(Item $item, OutputInterface $output): void
    {
        $output->writeln('ID - ' . $item->getId());
        $output->writeln('Name - ' . $item->getName());
        $output->writeln('Quantity - ' . $item->getQuantity());
        $output->writeln('Item Price - ' . '$' . number_format($item->getPrice(),2));
        $total = $item->getPrice() * $item->getQuantity();
        $output->writeln('Items Total - ' . '$' . number_format($total,2));
    }
}