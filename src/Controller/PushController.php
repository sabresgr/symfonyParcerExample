<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ProductTypes;
use App\Entity\Tblproductdata;

class PushController extends AbstractController
{



    public  function pushToDB($records)
    {

        $pType=new ProductTypes();
        $em = $this->getDoctrine()->getManager();
        foreach ($records as $record) {

            $prodType=$record['values']["Product Name"];

            //print_r($repositoryProdType->findOneBy(["str_type_name"=>$prodType]));

        }
    }





    /**
     * @Route("/push", name="push")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PushController.php',
        ]);
    }
}
