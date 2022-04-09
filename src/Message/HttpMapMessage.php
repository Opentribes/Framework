<?php

declare(strict_types=1);

namespace App\Message;

use OpenTribes\Core\Message\CreateFirstCityMessage;
use OpenTribes\Core\View\CityView;
use Symfony\Component\HttpFoundation\Request;

final class HttpMapMessage implements CreateFirstCityMessage
{
    use UserNameTrait;
    public CityView $city;
    private bool $cityCreated = false;
    private bool $userHasCities = false;

    public function __construct(private Request $request)
    {
    }

    public function hasCities(): bool
    {
        return $this->userHasCities === true;
    }

    public function enableResult(): void
    {
        $this->userHasCities = true;
    }

    public function cityCreated(): bool
    {
        return $this->cityCreated === true;
    }

    public function activateCreated(): void
    {
        $this->cityCreated = true;
    }

    public function setCity(CityView $city): void
    {
        $this->city = $city;
    }
}
