<?php

declare(strict_types=1);

namespace App\Controller\Website;

use App\Message\HttpCityMessage;
use OpenTribes\Core\UseCase\DisplayBuildingSlots;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sulu\Bundle\CommunityBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class CityController extends AbstractController
{
    public function __construct(private DisplayBuildingSlots $displayBuildingSlots)
    {
    }

    #[Route(path: '/city/{locationX<\d+>}/{locationY<\d+>}')]
    public function viewAction(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            throw new HttpException(403);
        }
        $message = new HttpCityMessage($request, $user);


        $this->displayBuildingSlots->execute($message);


        return $this->render(
            'pages/city.html.twig',
            ['slots' => $message->slots, 'cityDataOnly' => $message->cityDataOnly]
        );
    }
}