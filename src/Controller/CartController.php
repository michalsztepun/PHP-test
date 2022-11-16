<?php

namespace App\Controller;

use App\Model\Cart;
use App\Model\Customer;
use App\Service\CartCreator;
use App\Service\CartPrinter;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use App\Service\DataValidator;


class CartController
{
    private CartCreator $cartCreator;

    private CartPrinter $cartPrinter;

    private DataValidator $dataValidator;

    public function __construct(CartCreator $cartCreator, CartPrinter $cartPrinter, DataValidator $dataValidator)
    {
        $this->cartCreator = $cartCreator;
        $this->cartPrinter = $cartPrinter;
        $this->dataValidator = $dataValidator;
    }
    public function setCustomerAction(InputInterface $input, OutputInterface $output, Helper $helper, Cart $cart): void
    {
        $question = new Question("Please enter customer's first name: ");
        $question = $this->dataValidator->addQuestionValidation($question, 'string');
        $firstName = $helper->ask($input, $output, $question);

        $question = new Question("Please enter customer's last name: ");
        $question = $this->dataValidator->addQuestionValidation($question, 'string');
        $lastName = $helper->ask($input, $output, $question);
        
        $this->cartCreator->setCustomerName($cart, $firstName, $lastName);

        $output->writeln('Customer set successfully!');
    }

    public function addCustomerAddressAction(InputInterface $input, OutputInterface $output, Helper $helper, Cart $cart): void
    {
        $address = array();
        $question = new Question("Please enter address line 1: ");
        $question = $this->dataValidator->addQuestionValidation($question, 'string');
        $address['line1'] = $helper->ask($input, $output, $question);

        $question = new Question("Please enter address line 2(optional - press enter to continue): ");
        $address['line2'] = $helper->ask($input, $output, $question);

        $question = new Question("Please enter city: ");
        $question = $this->dataValidator->addQuestionValidation($question, 'string');
        $address['city'] = $helper->ask($input, $output, $question);

        $question = new Question("Please enter state: ");
        $question = $this->dataValidator->addQuestionValidation($question, 'string');
        $address['state'] = $helper->ask($input, $output, $question);

        $question = new Question("Please enter zip code: ");
        $question = $this->dataValidator->addQuestionValidation($question, 'string');
        $address['zip'] = $helper->ask($input, $output, $question);

        $this->cartCreator->addCustomerAddress($cart, $address);
        $output->writeln('Address added!');
    }

    public function displayCustomerAddressesAction(OutputInterface $output, Cart $cart): void
    {
        $this->cartPrinter->displayCustomerAddresses($cart, $output);
    }

    public function addItemAction(InputInterface $input, OutputInterface $output, Helper $helper, Cart $cart): void
    {
        $item = array();
        $question = new Question("Please enter item ID: ");
        $question = $this->dataValidator->addQuestionValidation($question, 'numeric');
        $item['id'] = $helper->ask($input, $output, $question);

        $question = new Question("Please enter item name: ");
        $question = $this->dataValidator->addQuestionValidation($question, 'string');
        $item['name'] = $helper->ask($input, $output, $question);

        $question = new Question("Please enter item quantity: ");
        $question = $this->dataValidator->addQuestionValidation($question, 'numeric');
        $item['quantity'] = $helper->ask($input, $output, $question);

        $question = new Question("Please enter item price: ");
        $question = $this->dataValidator->addQuestionValidation($question, 'numeric');
        $item['price'] = $helper->ask($input, $output, $question);

        $this->cartCreator->additem($cart, $item);
        $output->writeln('Item added!');
    }

    public function setCartAddressAction(InputInterface $input, OutputInterface $output, Helper $helper, Cart $cart): void
    {
        $addresses = $this->cartPrinter->getCustomerAddresses($cart);
        if(!empty($addresses)){
            $question = new ChoiceQuestion('Which address you want to use?',$addresses);
            $selected = $helper->ask($input, $output, $question);
            $this->cartCreator->setAddress($cart, $selected);
        } else {
            $output->writeln('Customer has no addresses');
        }
    }

    public function displayCartAction(InputInterface $input, OutputInterface $output, Helper $helper, Cart $cart): void
    {
        $output->writeln(['','Cart details:']);
        $this->cartPrinter->displayCartCustomer($cart, $output);
        $output->writeln('');
        $this->cartPrinter->displayCartAddress($cart, $output);
        $output->writeln('');
        $this->cartPrinter->displayCartItems($cart, $output);
        $output->writeln('');
        $this->cartPrinter->displayCartTotals($cart, $output);
        $output->writeln('');
    }
}