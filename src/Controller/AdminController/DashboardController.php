<?php

namespace App\Controller\AdminController;

use App\Repository\CommentEntityRepository;
use App\Repository\ContactFormEntityRepository;
use App\Repository\ContentEntityRepository;
use App\Repository\ContentTypeEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="admin_index")
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function index(ContentEntityRepository $contentEntityRepository,
                          ContentTypeEntityRepository $contentTypeEntityRepository,
                          ContactFormEntityRepository $contactFormEntityRepository,
                          CommentEntityRepository $commentEntityRepository,Request $request)
    {

        $contentEntitys = $contentEntityRepository->findByDeletedField("0",6);

        $contentTypeEntitys = $contentTypeEntityRepository->findByDeletedField("0",6);

        $contactFormEntitys = $contactFormEntityRepository->findBy(array(),array("id"=>"DESC"),6);

        $commentEntitys = $commentEntityRepository->findBy(array(),array("id"=>"DESC"),6);
        return $this->render('admin_pages/dashboard/index.html.twig', [
            "lastContentEntitys" => $contentEntitys,
            "contentTypeEntitys" => $contentTypeEntitys,
            "contactFormEntitys" => $contactFormEntitys,
            "commentEntitys" => $commentEntitys
        ]);
    }
}
