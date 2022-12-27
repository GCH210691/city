<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $cityName = null;

    #[ORM\Column(length: 255)]
    private ?string $cityPicture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    public function setCityName(string $cityName): self
    {
        $this->cityName = $cityName;

        return $this;
    }

    public function getCityPicture(): ?string
    {
        return $this->cityPicture;
    }

    public function setCityPicture(string $cityPicture): self
    {
        $this->cityPicture = $cityPicture;

        return $this;
    }
}

