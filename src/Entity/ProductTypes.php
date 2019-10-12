<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProductTypes
 *
 * @ORM\Table(name="product_types", indexes={@ORM\Index(name="IDX_ProductTypesName", columns={"str_type_name"})})
 * @ORM\Entity
 */
class ProductTypes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="str_type_name", type="string", length=50, nullable=false)
     */
    private $strTypeName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tblproductdata", mappedBy="idProductType")
     */
    private $tblproductdatas;

    public function __construct()
    {
        $this->tblproductdatas = new ArrayCollection();
    }

    /**
     * @return Collection|Tblproductdata[]
     */
    public function getTblproductdatas(): Collection
    {
        return $this->tblproductdatas;
    }

    public function addTblproductdata(Tblproductdata $tblproductdata): self
    {
        if (!$this->tblproductdatas->contains($tblproductdata)) {
            $this->tblproductdatas[] = $tblproductdata;
            $tblproductdata->setIdProductType($this);
        }

        return $this;
    }

    public function removeTblproductdata(Tblproductdata $tblproductdata): self
    {
        if ($this->tblproductdatas->contains($tblproductdata)) {
            $this->tblproductdatas->removeElement($tblproductdata);
            // set the owning side to null (unless already changed)
            if ($tblproductdata->getIdProductType() === $this) {
                $tblproductdata->setIdProductType(null);
            }
        }

        return $this;
    }


}
