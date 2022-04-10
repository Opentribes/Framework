<?php

declare(strict_types=1);

namespace App\Controller\Website;

use App\Message\HttpMapMessage;
use OpenTribes\Core\UseCase\CreateFirstCityUseCase;
use OpenTribes\Core\UseCase\ViewMapUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sulu\Bundle\CommunityBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class MapController extends AbstractController
{
    public function __construct(
        private CreateFirstCityUseCase $createNewCityUseCase,
        private ViewMapUseCase $viewMapUseCase,
        private SerializerInterface $serializer
    ) {
    }
    #[Route(path: '/map/{locationX<\d+>?0}/{locationY<\d+>?0}')]
    public function indexAction(Request $request): Response
    {

        $message = new HttpMapMessage($request);
        $this->createNewCityUseCase->process($message);
        $this->viewMapUseCase->process($message);
        $jsonMapData = $this->serializer->serialize($message->map,'json');

        return $this->render('pages/map.html.twig', ['jsonMapData'=>$jsonMapData,'map'=>$message->map]);
    }
}
