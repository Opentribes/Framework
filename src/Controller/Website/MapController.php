<?php
declare(strict_types=1);

namespace App\Controller\Website;
use App\Message\HttpMapMessage;
use OpenTribes\Core\UseCase\CheckPlayerHasCity;
use OpenTribes\Core\UseCase\CreateNewCityUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sulu\Bundle\CommunityBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class MapController extends AbstractController
{
    public function __construct(
        private CheckPlayerHasCity $checkPlayerHasCity,
        private CreateNewCityUseCase $createNewCityUseCase
    ){

    }
    #[Route(path: '/map')]
    public function indexAction(Request $request):Response
    {
        $message = new HttpMapMessage($request);

        $this->checkPlayerHasCity->process($message);
        if(!$message->hasCities()){
            $this->createNewCityUseCase->process($message);
        }

        return $this->render('pages/map.html.twig',[]);
    }
}