<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ApiResource(
    collectionOperations: ['post'],
    itemOperations: ['get'],
    denormalizationContext: ["groups" => ['location:input']],
    normalizationContext: ["groups" => ['location:output']],
)]
class Location
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private null|int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['location:output','location:input'])]
    #[NotBlank]
    private string $country ;

    #[ORM\Column(length: 255)]
    #[Groups(['location:output','location:input'])]
    #[NotBlank]
    private string $locality;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Weather::class, orphanRemoval: true)]
    #[ApiSubresource]
    #[Groups(['location:output'])]
    private Collection $weather;

    #[ORM\Column]
    #[Groups(['location:output'])]
    private null|float $lat = null;

    #[ORM\Column]
    #[Groups(['location:output'])]
    private null|float $lon = null;

    public function __construct()
    {
        $this->weather = new ArrayCollection();
    }

    public function getId(): null|int
    {
        return $this->id;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getLocality(): string
    {
        return $this->locality;
    }

    public function setLocality(string $locality): self
    {
        $this->locality = $locality;

        return $this;
    }

    public function getWeather(): Collection
    {
        return $this->weather;
    }

    public function addWeather(Weather $weather): self
    {
        if (!$this->weather->contains($weather)) {
            $this->weather->add($weather);
            $weather->setLocation($this);
        }

        return $this;
    }

    public function removeWeather(Weather $weather): self
    {
        if ($this->weather->removeElement($weather)) {
            // set the owning side to null (unless already changed)
            if ($weather->getLocation() === $this) {
                $weather->setLocation(null);
            }
        }

        return $this;
    }

    public function getLat(): null|float
    {
        return $this->lat;
    }

    public function setLat(null|float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): null|float
    {
        return $this->lon;
    }

    public function setLon(null|float $lon): self
    {
        $this->lon = $lon;

        return $this;
    }
}
