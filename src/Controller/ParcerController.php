<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;




/**
 * Pricessed with file data
 * Class ParcerController
 * @package App\Controller
 */
class ParcerController extends AbstractController
{


    /**
     * Make result array
     * @param $fileName
     * @return array
     */
    public static function processFile($fileName)
    {

        $data=self::readFile($fileName);
        $arrResult=array("valid"=>array(),"invalid"=>array());
        foreach ($data as $item) {
            $errors=self::validateProduct($item);
            if(!count($errors))
                $arrResult['valid'][]['values']=$item;
            else {
                $arrResult['invalid'][]['values'] = $item;
                $lastItem=count($arrResult['invalid'])-1;
                foreach($errors as $error)
                    $arrResult['invalid'][$lastItem]['errors'][$error->getPropertyPath()]=$error->getMessage();

            }
        }
        return $arrResult;
    }

    /**
     * Read file and convert to PHP data
     * @param $fileName
     * @return mixed
     */
    protected static function readFile($fileName)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $data = $serializer->decode(file_get_contents($fileName), 'csv');
        if(isset($data[FN_STOCK]))
            $data=array(0=>$data);
        return $data;
    }

    /**
     * Make data validation
     * @param $data
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    protected static function validateProduct($data)
    {
        $validator = Validation::createValidator();
        $constraint = new Constraints\Collection(array(

            FN_PRODUCT_CODE => new Constraints\Length([
                'min' => 1,
                'max' => 10,
                'minMessage' => ' must be at least {{ limit }} characters long',
                'maxMessage' => ' cannot be longer than {{ limit }} characters',
            ]),
            FN_PRODUCT_NAME => new Constraints\Length([
                'min' => 1,
                'max' => 50,
                'minMessage' => ' must be at least {{ limit }} characters long',
                'maxMessage' => ' cannot be longer than {{ limit }} characters',
            ]),
            FN_PRODUCT_DESCRIPTION => new Constraints\Length([
                'min' => 1,
                'max' => 255,
                'minMessage' => ' must be at least {{ limit }} characters long',
                'maxMessage' => ' cannot be longer than {{ limit }} characters',
            ]),
            FN_STOCK =>[
                new Constraints\Type([
                'type' => 'numeric',
                'message' => 'The value {{ value }} is not a valid {{ type }}.',
                ]),
                new Constraints\GreaterThanOrEqual(['value'=>MIN_STOCK])
            ],
            FN_COST => [
                new Constraints\Type([
                'type' => 'numeric',
                'message' => 'The value {{ value }} is not a valid {{ type }}.',
                ]),
                new Constraints\LessThanOrEqual(['value'=>MAX_COST]),
                new Constraints\GreaterThanOrEqual(['value'=>MIN_COST])
            ],
            FN_IS_DISCONT => new Constraints\Length([
                'min' => 0,
                'max' => 3,
                'minMessage' => ' must be at least {{ limit }} characters long, you have {{ value }}',
                'maxMessage' => ' cannot be longer than {{ limit }} characters, you have {{ value }}',
            ]),

        ));
        $violations = $validator->validate($data, $constraint);
        return $violations;
    }








}
