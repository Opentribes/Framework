<?php
declare(strict_types=1);

namespace App\Controller\Website;

use App\Repository\DBALBuildingRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sulu\Bundle\CommunityBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class CityController extends AbstractController
{
    public function __construct(private DBALBuildingRepository $buildingRepository)
    {
    }

    #[Route(path: '/city/{locationX<\d+>}/{locationY<\d+>}')]
    public function viewAction(Request $request):Response
    {
    $collection =   $this->buildingRepository->findAllAtLocation(2,2);
    // dump($collection);
        return $this->render('pages/city.html.twig',['page'=>[]]);
    }
}