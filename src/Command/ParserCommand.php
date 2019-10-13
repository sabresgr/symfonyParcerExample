<?php

namespace App\Command;

use App\Entity\ProductTypes;
use App\Entity\Tblproductdata;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Controller\ParcerController;
use App\Controller\PushController;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ParserCommand extends Command
{
    protected static $defaultName = 'app:parser';
    private $container;
    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setDescription('Test parser')
            ->addArgument('filename', InputArgument::OPTIONAL, 'path to csv file')
            ->addOption('test', null, InputOption::VALUE_NONE, 'Test without db push')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $fileName = $input->getArgument('filename');
        self::fileNameCheck($fileName,$io);
        $data=ParcerController::processFile($fileName);
        $em = $this->container->get('doctrine')->getManager();
        if(!$input->getOption('test'))
            if(count($data['valid']))
            {
                $countInsert=0;
                $countUpdate=0;
                foreach ($data['valid'] as $record) {
                    $prodType = $this->getProductTypeId($record);
                    $repositoryProduct = $em->getRepository(Tblproductdata::class);
                    $product=$repositoryProduct->find($record['values']['Product Code']);
                    if(is_null($product)) {
                        $product = new Tblproductdata();
                        $product->setStrProductCode($record['values']['Product Code']);
                        $countInsert++;
                    }
                    else
                        $countUpdate++;
                    $product->setIdProductType($prodType);
                    $product->setFloatCost($record['values']['Cost in GBP']);
                    $product->setIntStock($record['values']['Stock']);
                    $product->setStrProductDescription($record['values']['Product Description']);
                    $product->setDtmDiscontinued($record['values']['Discontinued']);
                    $em->persist($product);
                    $em->flush();

                }
                $data['dbcount']['insert']=$countInsert;
                $data['dbcount']['update']=$countUpdate;
            }
        self::printInformation($data,$io);




/*

        if ($input->getOption('option1')) {
            $io->comment("option");
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
*/  }

    protected function getProductTypeId($record)
    {
            $em = $this->container->get('doctrine')->getManager();
            $repositoryProdType = $em->getRepository(ProductTypes::class);

            $prodType=$record['values']["Product Name"];
            $result=$repositoryProdType->findOneBy(["strTypeName"=>$prodType]);
            if(is_null($result))
            {
                $pt=new ProductTypes();
                $pt->setStrTypeName($record['values']["Product Name"]);
                $em->persist($pt);
                $em->flush();
                return $pt;
            }
            else
                return $result;


    }
    protected static function printInformation($data,SymfonyStyle &$io)
    {
        $info=self::createOutputInformation($data);
        $io->text("Number of records: ".$info["count"]["all"]);
        $io->success("Number of successful: ".$info["count"]["success"]."\n  Inserts:".$info["count"]["db"]['insert']."  Updates: ".$info["count"]["db"]['update']);
        $io->warning("Number of faild: ".$info["count"]["faild"]);
        $io->text($info['text']);
    }
    protected static function createOutputInformation($data): array
    {
        $arrResult=array();
        $arrResult["count"]["success"]=count($data['valid']);
        $arrResult["count"]["faild"]=count($data['invalid']);
        $arrResult["count"]["db"]=$data['dbcount'];
        $arrResult["count"]["all"]=$arrResult["count"]["success"]+$arrResult["count"]["faild"];
        $text="";
        foreach($data['invalid'] as $item)
        {
            $text.=implode(',', $item['values'])."\n";
            //$text.=implode("\n",$item['errors'])."\n".str_repeat ("-",30)."\n";
            $text.=implode("\n", array_map(
                function ($v, $k) {
                    return $k.':'.$v;
                },
                $item['errors'],
                array_keys($item['errors'])))
                ."\n".str_repeat ("-",30)."\n";
        }
        $arrResult['text']=$text;
        return $arrResult;
    }


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
