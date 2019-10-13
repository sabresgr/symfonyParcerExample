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
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class ParserCommand
 * @package App\Command
 */
class ParserCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:parser';
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * ParserCommand constructor.
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setDescription('Test parser')
            ->addArgument('filename', InputArgument::OPTIONAL, 'path to csv file')
            ->addOption('test', null, InputOption::VALUE_NONE, 'Test without db push')
        ;
    }

    /**
     * Method with main logic of command
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $fileName = $input->getArgument('filename');
        self::fileNameCheck($fileName,$io);
        $data=ParcerController::processFile($fileName);
        $counter=array('insert'=>0,'update'=>0);
        if(!$input->getOption('test'))
            if(count($data['valid']))
            {

                foreach ($data['valid'] as $record) {
                    $prodType = $this->getProductTypeId($record['values']["Product Name"]);
                    $this->setProduct($record,$prodType,$counter);
                }
            }
        $data['dbcount']=$counter;
        self::printInformation($data,$io);

}

    /**
     * Method for insertion or update(if productCode exists in table) row for table Tblproductdata.
     * @param array $record
     * @param \App\Entity\ProductTypes $prodType
     * @param array $counter param for count inserts and updates
     */
    protected function setProduct($record, $prodType, array &$counter)
    {
        $em = $this->container->get('doctrine')->getManager();
        $repositoryProduct = $em->getRepository(Tblproductdata::class);
        $product=$repositoryProduct->find($record['values']['Product Code']);
        if(is_null($product)) {
            $product = new Tblproductdata();
            $product->setStrProductCode($record['values']['Product Code']);
            $counter['insert']++;
        }
        else
            $counter['update']++;
        $product->setIdProductType($prodType);
        $product->setFloatCost($record['values']['Cost in GBP']);
        $product->setIntStock($record['values']['Stock']);
        $product->setStrProductDescription($record['values']['Product Description']);
        $product->setDtmDiscontinued($record['values']['Discontinued']);
        $em->persist($product);
        $em->flush();

    }

    /**
     * Get ProductType entity object by strTypeName
     * @param array $prodType Name of type
     * @return \App\Entity\ProductTypes
     */
    protected function getProductTypeId($prodType)
    {
            $em = $this->container->get('doctrine')->getManager();
            $repositoryProdType = $em->getRepository(ProductTypes::class);
            $result=$repositoryProdType->findOneBy(["strTypeName"=>$prodType]);
            if(is_null($result))
            {
                $pt=new ProductTypes();
                $pt->setStrTypeName($prodType);
                $em->persist($pt);
                $em->flush();
                return $pt;
            }
            else
                return $result;


    }

    /**
     * Printing result data
     * @param $data
     * @param \Symfony\Component\Console\Style\SymfonyStyle $io
     */
    protected static function printInformation($data, SymfonyStyle &$io)
    {
        $info=self::createOutputInformation($data);
        $io->text("Number of records: ".$info["count"]["all"]);
        $io->success("Number of successful: ".$info["count"]["success"]."\n  Inserts:".$info["count"]["db"]['insert']."  Updates: ".$info["count"]["db"]['update']);
        $io->warning("Number of faild: ".$info["count"]["faild"]);
        $io->text($info['text']);
    }

    /**
     * Create output information for printing
     * @param $data
     * @return array
     */
    protected static function createOutputInformation($data): array
    {
        $arrResult=array();
        $arrResult["count"]["success"]=count($data['valid']);
        $arrResult["count"]["faild"]=count($data['invalid']);
        $arrResult["count"]["db"]=$data['dbcount'];
        $arrResult["count"]["all"]=$arrResult["count"]["success"]+$arrResult["count"]["faild"];
        $text="";
        $charCount=exec("tput cols");
        if(!is_numeric($charCount))
            $charCount=30;
        foreach($data['invalid'] as $item)
        {
            $text.=implode(',', $item['values'])."\n";
            $text.=implode("\n", array_map(
                function ($v, $k) {
                    return $k.':'.$v;
                },
                $item['errors'],
                array_keys($item['errors'])))
                ."\n".str_repeat ("-",$charCount)."\n";
        }
        $arrResult['text']=$text;
        return $arrResult;
    }


    /**
     * Check input file
     * @param $fileName
     * @param \Symfony\Component\Console\Style\SymfonyStyle $io
     */
    protected static function fileNameCheck($fileName, SymfonyStyle &$io)
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
