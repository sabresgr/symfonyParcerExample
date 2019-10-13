<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tblproductdata
 *
 * @ORM\Table(name="tblProductData", uniqueConstraints={@ORM\UniqueConstraint(name="strProductCode", columns={"strProductCode"})})
 * @ORM\Entity
 */
class Tblproductdata
{
    /**
     * @var int
     *
     * @ORM\Column(name="intProductDataId", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $intproductdataid;


    /**
     * @var string
     *
     * @ORM\Column(name="strProductDesc", type="string", length=255, nullable=false)
     */
    private $strproductdesc;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductCode", type="string", length=10, nullable=false)
     */
    private $strproductcode;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dtmAdded", type="datetime",options={"default"="CURRENT_TIMESTAMP"})
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


    public function getIdProductType(): ?ProductTypes
    {

        return $this->idProductType;
    }

    public function setIdProductType(?ProductTypes $idProductType): self
    {
        $this->idProductType = $idProductType;

        return $this;
    }

    public function getIntStock(): ?int
    {
        return $this->intStock;
    }

    public function setIntStock(int $intStock): self
    {
        $this->intStock = $intStock;

        return $this;
    }

    public function getFloatCost(): ?float
    {
        return $this->floatCost;
    }

    public function getProductId(): int
    {
        return $this->intproductdataid;
    }

    public function setFloatCost(float $floatCost): self
    {
        $this->floatCost = $floatCost;

        return $this;
    }

    public function setStrProductCode(string $productCode): self
    {
        $this->strproductcode = $productCode;

        return $this;
    }
    public function setStrProductDescription(string $productDescription): self
    {
        $this->strproductdesc = $productDescription;

        return $this;
    }

    public function setDtmDiscontinued($dis): self
    {
       // if($dis=="yes")
      //      $this->dtmdiscontinued = 'CURRENT_TIMESTAMP';

        return $this;
    }


}
