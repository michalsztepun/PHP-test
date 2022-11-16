<?php

namespace App\Command;

use App\Controller\CartController;
use App\Model\Cart;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;


class CartCommand extends Command
{
    protected static $defaultName = 'sourcetoad:cart';

    private Cart $cart;

    private CartController $cartController;

    public function __construct(CartController $cartController)
    {
        $this->cart = new Cart();
        $this->cartController = $cartController;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Cart builder')
            ->setHelp('This command allows you to create cart instance with the customer, items and address');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $output->writeln('Creating cart...');

        while(true){
            $question = new ChoiceQuestion(
                'What do you want to do?',
                array(
                    "Set customer's name",
                    "Add customer's address",
                    "Display customer's addresses",
                    "Add item to the cart",
                    "Set order shipment address",
                    "Display cart details",
                    "Exit"
                ),
                6
            );
            $selected = $helper->ask($input, $output, $question);

            if($selected === "Set customer's name"){
                $this->cartController->setCustomerAction($input, $output, $helper, $this->cart);
            } else if($selected === "Add customer's address") {
                $this->cartController->addCustomerAddressAction($input, $output, $helper, $this->cart);
            } else if($selected === "Display customer's addresses") {
                $this->cartController->displayCustomerAddressesAction($output, $this->cart);
            } else if($selected === "Add item to the cart") {
                $this->cartController->addItemAction($input, $output, $helper, $this->cart);
            } else if($selected === "Set order shipment address") {
                $this->cartController->setCartAddressAction($input, $output, $helper, $this->cart);
            } else if($selected === "Display cart details") {
                $this->cartController->displayCartAction($input, $output, $helper, $this->cart);
            } else if($selected === "Exit") {
                $output->writeln('Goodbye!');
                break;
            }
        }

        return Command::SUCCESS;
    }
}