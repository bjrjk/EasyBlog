<?php

namespace App\Controller;
use App\Entity\Setting;
use App\Entity\Article;
use Doctrine\DBAL\Schema\Index;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    private static int $articleItemCounts = 10;

    /**
     * @param int $pageid
     * @return Response
     * @Route("/{pageid}", name="index")
     */
    public function indexPage(int $pageid = 1): Response
    {
        $settingRepository = $this->getDoctrine()->getRepository(Setting::class);
        $title = $settingRepository->getTitle();
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $articleArray = $articleRepository->getArticlesByRange(
            ($pageid - 1) * IndexController::$articleItemCounts,
            IndexController::$articleItemCounts
        );
        return $this->render('index.html.twig',[
            'pageid' => $pageid,
            'itemcounts' => IndexController::$articleItemCounts,
            'title' => $title,
            'articles' => $articleArray,
        ]);
    }
}