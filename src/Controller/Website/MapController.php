<?php
declare(strict_types=1);

namespace App\Controller\Website;
use App\Message\HttpMapMessage;
use OpenTribes\Core\UseCase\CreateFirstCityUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sulu\Bundle\CommunityBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class MapController extends AbstractController
{
    public function __construct(
        private CreateFirstCityUseCase $createNewCityUseCase
    ){

    }
    #[Route(path: '/map')]
    public function indexAction(Request $request):Response
    {

        $message = new HttpMapMessage($request);
        $this->createNewCityUseCase->process($message);

        return $this->render('pages/map.html.twig',[]);
    }
}