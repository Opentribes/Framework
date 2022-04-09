<?php

declare(strict_types=1);

namespace App\Message;

use OpenTribes\Core\Message\CreateFirstCityMessage;
use OpenTribes\Core\Message\ViewMapMessage;
use OpenTribes\Core\Utils\Location;
use OpenTribes\Core\Utils\Viewport;
use OpenTribes\Core\View\CityView;
use OpenTribes\Core\View\MapView;
use Symfony\Component\HttpFoundation\Request;

final class HttpMapMessage implements CreateFirstCityMessage, ViewMapMessage
{
    use UserNameTrait;

    public CityView $city;
    public MapView $map;
    public Location $location;
    private bool $cityCreated = false;
    private bool $userHasCities = false;

    public function __construct(private Request $request)
    {
        $this->location = new Location();
    }

    public function getViewortWidth(): int
    {
        return (int)$this->request->server->get('VIEWPORT_WIDTH', 0);
    }
    public function getViewortHeight(): int
    {
        return (int)$this->request->server->get('VIEWPORT_HEIGHT', 0);
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

    public function getViewport(): Viewport
    {
        return new Viewport($this->location, $this->getViewortWidth(), $this->getViewortHeight());
    }

    public function setMap(MapView $map): void
    {
        $this->map = $map;
    }

}
