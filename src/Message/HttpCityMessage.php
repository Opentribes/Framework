<?php

declare(strict_types=1);

namespace App\Message;

use OpenTribes\Core\Message\DisplayBuildingSlotsMessage;
use OpenTribes\Core\View\SlotView;
use OpenTribes\Core\View\SlotViewCollection;
use Symfony\Component\HttpFoundation\Request;

final class HttpCityMessage implements DisplayBuildingSlotsMessage
{
    use UserNameTrait;
    public SlotViewCollection $slots;

    public bool $cityDataOnly;
    public function __construct(private Request $request)
    {
        $this->slots = new SlotViewCollection();
        $this->cityDataOnly = false;
    }
    public function getSlots(): SlotViewCollection
    {
        return $this->slots;
    }

    public function addSlot(SlotView $slotView): void
    {
        $this->slots[] = $slotView;
    }

    public function getLocationX(): int
    {
        return (int) $this->request->attributes->get('locationX');
    }

    public function getLocationY(): int
    {
        return (int) $this->request->attributes->get('locationY');
    }

    public function enableCityDataOnly(): void
    {
        $this->cityDataOnly = true;
    }
}
