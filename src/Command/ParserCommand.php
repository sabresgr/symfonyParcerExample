<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Controller\ParcerController;


class ParserCommand extends Command
{
    protected static $defaultName = 'app:parser';

    protected function configure()
    {
        $this
            ->setDescription('Test parser')
            ->addArgument('filename', InputArgument::OPTIONAL, 'path to csv file')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $fileName = $input->getArgument('filename');

        self::fileNameCheck($fileName,$io);
        print_r(ParcerController::processFile($fileName));





/*

        if ($input->getOption('option1')) {
            $io->comment("option");
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
*/  }


    protected static function fileNameCheck($fileName,SymfonyStyle &$io)
    {

        if (!$fileName) {
            $io->error('Enter input file path');
            exit(-1);
        }
        if(!file_exists($fileName)) {
            $io->error('File not exists');
            exit(-2);
        }

    }
}
