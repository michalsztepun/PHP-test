<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


class PrintCommand extends Command
{
    protected static $defaultName = 'sourcetoad:print';

    protected function configure(): void
    {
        $this
            ->setDescription('Print array data from file')
            ->setHelp('This command allows you to print array from the php file. Path argument is required and 
            file must return array of guests')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to your data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = include($input->getArgument('path'));

        if(!is_array($data)) {
            $output->writeln('File needs to return array of guests');
            return Command::FAILURE;
        }

        $this->print($output, 'guest', $data);

        return Command::SUCCESS;
    }

    /**
     * Prints given data to console. Array keys are capitalized and underscores are replaced with spaces.
     * True/false values are replaced with Yes/No. Null values are ignored.
     */
    private function print(OutputInterface $output, $name, $data): void
    {
        if(is_array($data)){
            $i=0;
            foreach ($data as $k => $v){
                ++$i;
                if(!is_numeric($name)){
                    $output->writeln(['', ucwords(str_replace("_", " ", $name)) . ' ' . $i . ':']);
                }
                $this->print($output, $k, $v);
            }
        } else if($data !== null) {
            if($data === true) $data = 'Yes';
            if($data === false) $data = 'No';
            $output->writeln(ucwords(str_replace("_", " ", $name)) . ' - ' . $data);
        }
    }
}