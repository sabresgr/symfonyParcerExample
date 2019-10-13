<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Tblproductdata
 *
 * @ORM\Table(
 *     name="tblProductData",

 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="strProductCode", columns={"strProductCode"})
 *     }
 * )
 * @ORM\Entity
 */
class Tblproductdata
{


    /**
     * @var string
     *
     * @ORM\Column(name="strProductDesc", type="string", length=255, nullable=false)
     */
    private $strproductdesc;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="strProductCode", type="string", length=10, nullable=false)
     */
    private $strproductcode;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dtmAdded", type="datetime",options={"default"="CURRENT_TIMESTAMP"})
     *
     */
    private $dtmadded;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    private $dtmdiscontinued;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="stmTimestamp", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $stmtimestamp = 'CURRENT_TIMESTAMP';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductTypes", inversedBy="tblproductdatas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idProductType;

    /**
     * @ORM\Column(type="integer",nullable=false)
     */
    private $intStock;

    /**
     * @ORM\Column(type="float",nullable=false)
     */
    private $floatCost;

    /**
     * Tblproductdata constructor.
     * @throws \Exception
     */
    public function __construct()
    {

        $this->stmtimestamp=new \DateTime();
        $this->dtmadded=new \DateTime();
    }

    /**
     * @return \App\Entity\ProductTypes|null
     */
    public function getIdProductType(): ?ProductTypes
    {

        return $this->idProductType;
    }

    /**
     * @param \App\Entity\ProductTypes|null $idProductType
     * @return \App\Entity\Tblproductdata
     */
    public function setIdProductType(?ProductTypes $idProductType): self
    {
        $this->idProductType = $idProductType;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getIntStock(): ?int
    {
        return $this->intStock;
    }

    /**
     * @param int $intStock
     * @return \App\Entity\Tblproductdata
     */
    public function setIntStock(int $intStock): self
    {
        $this->intStock = $intStock;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getFloatCost(): ?float
    {
        return $this->floatCost;
    }


    /**
     * @param float $floatCost
     * @return \App\Entity\Tblproductdata
     */
    public function setFloatCost(float $floatCost): self
    {
        $this->floatCost = $floatCost;

        return $this;
    }

    /**
     * @param string $productCode
     * @return \App\Entity\Tblproductdata
     */
    public function setStrProductCode(string $productCode): self
    {
        $this->strproductcode = $productCode;

        return $this;
    }

    /**
     * @param string $productDescription
     * @return \App\Entity\Tblproductdata
     */
    public function setStrProductDescription(string $productDescription): self
    {
        $this->strproductdesc = $productDescription;

        return $this;
    }

    /**
     * @param $dis
     * @return \App\Entity\Tblproductdata
     * @throws \Exception
     */
    public function setDtmDiscontinued($dis): self
    {
        if($dis=="yes")
            $this->dtmdiscontinued = new \DateTime();
        else
            $this->dtmdiscontinued = null;

        return $this;
    }


}
