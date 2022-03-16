<?php

declare(strict_types=1);

namespace App\Controller\Website;

use App\Message\HttpCityMessage;
use OpenTribes\Core\UseCase\DisplayBuildingSlots;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sulu\Bundle\CommunityBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class CityController extends AbstractController
{
    public function __construct(private DisplayBuildingSlots $displayBuildingSlots)
    {
    }

    #[Route(path: '/city/{locationX<\d+>}/{locationY<\d+>}')]
    public function viewAction(Request $request): Response
    {
        $message = new HttpCityMessage($request);

        $this->displayBuildingSlots->execute($message);


        return $this->render(
            'pages/city.html.twig',
            ['slots' => $message->slots, 'cityDataOnly' => $message->cityDataOnly]
        );
    }
}