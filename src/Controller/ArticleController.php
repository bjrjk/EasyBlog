<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\Setting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ArticleController extends AbstractController
{
    /**
     * @param int $articleid
     * @return Response
     * @Route("/article/{articleid}", name="article")
     */
    public function articlePage(int $articleid): Response
    {
        $settingRepository = $this->getDoctrine()->getRepository(Setting::class);
        $title = $settingRepository->getTitle();
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleRepository->getArticleByID($articleid);
        return $this->render('article.html.twig',[
            'title' => $title,
            'article' => $article,
        ]);
    }

    /**
     * @param int $articleid
     * @return Response
     * @Route("/edit/{articleid}", name="edit", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function articleEdit(int $articleid): Response
    {
        $settingRepository = $this->getDoctrine()->getRepository(Setting::class);
        $title = $settingRepository->getTitle();
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleRepository->getArticleByID($articleid);
        return $this->render('edit.html.twig',[
            'title' => $title,
            'article' => $article,
        ]);
    }

    /**
     * @param int $articleid
     * @param string $title
     * @param string $content
     * @return Response
     * @Route("/edit/{articleid}", name="editApply", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function articleEditApply(
        int $articleid,
    ):Response
    {
        $request = Request::createFromGlobals();
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $articleRepository->updateArticleByID(
            $articleid,
            $request->request->get('title'),
            $request->request->get('content')
        );
        return $this->redirectToRoute('article', [
            'articleid' => $articleid,
        ]);
    }

    /**
     * @return Response
     * @Route("/create", name="create", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function articleCreate(): Response
    {
        $settingRepository = $this->getDoctrine()->getRepository(Setting::class);
        $title = $settingRepository->getTitle();
        return $this->render('create.html.twig', [
            'title' => $title,
        ]);
    }

    /**
     * @return Response
     * @Route("/create", name="createApply", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function articleCreateApply():Response
    {
        $request = Request::createFromGlobals();
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $newArticleID = $articleRepository->createArticle(
            $request->request->get('title'),
            $request->request->get('content')
        );
        return $this->redirectToRoute('article', [
            'articleid' => $newArticleID,
        ]);
    }

    /**
     * @param int $articleid
     * @return Response
     * @Route("/delete/{articleid}", name="delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function articleDelete(int $articleid):Response
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $articleRepository->deleteArticle($articleid);
        return $this->redirectToRoute('index');
    }
}