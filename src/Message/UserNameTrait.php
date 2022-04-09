<?php

declare(strict_types=1);

namespace App\Message;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;

trait UserNameTrait
{
    public function getUsername(): string
    {
        /** @var AttributeBagInterface $attributesBag */
        $attributesBag = $this->request->getSession()->getBag('attributes');
        return $attributesBag->get('_security.last_username');
    }
}
