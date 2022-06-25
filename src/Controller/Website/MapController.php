<?php

declare(strict_types=1);

namespace App\Controller\Website;

use App\Message\HttpMapMessage;
use OpenTribes\Core\UseCase\CreateFirstCityUseCase;
use OpenTribes\Core\UseCase\ViewMapUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sulu\Bundle\CommunityBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Stopwatch\Stopwatch;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class MapController extends AbstractController
{
    public function __construct(
        private readonly CreateFirstCityUseCase $createNewCityUseCase,
        private readonly ViewMapUseCase $viewMapUseCase,
        private readonly Stopwatch $stopwatch
    ) {
    }

    #[Route(path: '/map/{locationX<\d+>?0}/{locationY<\d+>?0}')]
    public function indexAction(Request $request): Response
    {
        $message = new HttpMapMessage($request);

        $this->stopwatch->start('create-new-city');
        $this->createNewCityUseCase->process($message);
        $this->stopwatch->stop('create-new-city');
        $this->stopwatch->start('view-map');
        $this->viewMapUseCase->process($message);
        $this->stopwatch->stop('view-map');


        $this->stopwatch->start('serialize-map');
        $jsonMapData = json_encode($message->map);
        $this->stopwatch->stop('serialize-map');

        $responseData = [
            'jsonCenterLocation' => json_encode(
                ['x' => $message->location->getX(), 'y' => $message->location->getY()]
            ),
            'jsonMapData' => $jsonMapData
        ];

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($responseData);
        }

        return $this->render(
            'pages/map.html.twig',
            $responseData
        );
    }

}
