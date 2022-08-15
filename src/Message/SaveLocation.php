<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\Location;

class SaveLocation
{
    public function __construct(private readonly Location $location)
    {
    }

    public function getLocation(): Location
    {
        return $this->location;
    }
}
