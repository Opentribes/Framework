<?php
declare(strict_types=1);

namespace App\Message;

use OpenTribes\Core\Message\DisplayBuildingSlotsMessage;
use OpenTribes\Core\View\SlotView;
use OpenTribes\Core\View\SlotViewCollection;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

final class HttpCityMessage implements DisplayBuildingSlotsMessage
{
    public SlotViewCollection $slots;

    public bool $cityDataOnly;
    public function __construct(private Request $request){
        $this->slots = new SlotViewCollection();
        $this->cityDataOnly = false;
    }
    public function getSlots(): SlotViewCollection
    {
      return  $this->slots;
    }

    public function addSlot(SlotView $slotView): void
    {
        $this->slots[]=$slotView;
    }

    public function getLocationX(): int
    {
        return  (int)$this->request->get('locationX');
    }

    public function getLocationY(): int
    {
        return  (int)$this->request->get('locationY');
    }

    public function getUserName(): string
    {
        return $this->request->getSession()->getBag('attributes')->get('_security.last_username');
    }

    public function enableCityDataOnly(): void
    {
        $this->cityDataOnly = true;
    }

}