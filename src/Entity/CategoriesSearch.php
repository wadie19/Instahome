<?php

namespace App\Entity;

use App\Entity\Categories;
use Doctrine\ORM\Mapping as ORM;

class CategoriesSearch
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categories")
     */
    private $Categories;


    public function getCategories(): ?Categories
    {
        return $this->Categories;
    }

    public function setCategories(?Categories $Categories): self
    {
        $this->Categories = $Categories;

        return $this;
    }



}