<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


class SortCommand extends Command
{
    protected static $defaultName = 'sourcetoad:sort';

    protected function configure(): void
    {
        $this
            ->setDescription('Sort array data from file')
            ->setHelp('This command allows you to sort array from the php file by given key or keys. 
            Path and keys arguments are required and given file must return array')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to your data.')
            ->addArgument('keys', InputArgument::REQUIRED, 'Key or comma separated key list');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = include($input->getArgument('path'));

        if(!is_array($data)) {
            $output->writeln('File needs to return array');
            return Command::FAILURE;
        }

        $keys = explode(',',$input->getArgument('keys'));

        usort($data, function ($a, $b) use ($keys) {
            foreach($keys as $key){
                if ($this->findValue($a, $key) != $this->findValue($b, $key)) {
                    return $this->findValue($a, $key) <=> $this->findValue($b, $key);
                }
            }
            return false;
        });

        $output->writeln('Sorted array:');
        print_r($data);

        return Command::SUCCESS;
    }

    /**
     * Search for value in the array by given key. Returns string if found, null if not
     */
    private function findValue(array $array, string $key, $result = null): ?string
    {
        if(is_null($result)){
            foreach ($array as $k => $v) {
                if($key === $k){
                    return (string) $v;
                } else if(is_array($v)) {
                    $result = $this->findValue($v, $key, $result);
                }
            }
        }

        return $result;
    }
}