<?php

namespace App\Entity;

use App\Entity\City;
use Doctrine\ORM\Mapping as ORM;

class CitySearch
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City")
     */
    private $City;


    public function getCity(): ?City
    {
        return $this->City;
    }

    public function setCity(?City $City): self
    {
        $this->City = $City;

        return $this;
    }
}