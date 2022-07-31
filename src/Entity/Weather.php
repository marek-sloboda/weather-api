<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WeatherRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: "App\Repository\WeatherRepository")]
#[ApiResource(
    collectionOperations: [],
    itemOperations: ['get']
)]
class Weather
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['location:output'])]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'weather')]
    #[ORM\JoinColumn(nullable: false)]
    private Location $location;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Groups(['location:output'])]
    private ?float $temperature1 = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Groups(['location:output'])]
    private ?float $temperature2 = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Groups(['location:output'])]
    private ?float $avgTemperature = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getTemperature1(): ?float
    {
        return $this->temperature1;
    }

    public function setTemperature1(float $temperature1): self
    {
        $this->temperature1 = $temperature1;

        return $this;
    }

    public function getTemperature2(): ?float
    {
        return $this->temperature2;
    }

    public function setTemperature2(?float $temperature2): void
    {
        $this->temperature2 = $temperature2;
    }

    public function getAvgTemperature(): ?float
    {
        return $this->avgTemperature;
    }

    public function setAvgTemperature(?float $avgTemperature): void
    {
        $this->avgTemperature = $avgTemperature;
    }
}
