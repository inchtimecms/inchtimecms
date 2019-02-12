<?php

namespace App\Controller\CozaController;

use App\Entity\ContentEntity;
use App\Repository\ContentEntityRepository;
use App\Repository\ContentTypeEntityRepository;
use App\Repository\ProductTypeEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends BaseController
{
    /**
     * @Route("/", name="coza_index")
     */
    public function index(ContentTypeEntityRepository $contentTypeEntityRepository, ProductTypeEntityRepository $productTypeEntityRepository,
                          ContentEntityRepository $contentEntityRepository, EntityManagerInterface $em)
    {
        //获取首页顶部slider 5 个内容
        $contentTypeEntity = $contentTypeEntityRepository->findOneBy(array("contentTypeMachineAlias" => "slider" ));
        $indexSliderContents = $contentEntityRepository->findBy(array("contentTypeEntity" => $contentTypeEntity),array("id" => "DESC"),5);

        //获取16个商品类型的内容
        /** @var ContentEntity[] $productContentEntities **/
        $productContentEntities = $em->createQuery("SELECT c FROM App\Entity\ContentEntity c JOIN c.contentTypeEntity t JOIN t.productTypeEntity p WHERE p.id IS NOT NULL ORDER BY c.id DESC")
            ->setMaxResults(16)
            ->getResult();

        return $this->render('themes/cozastore/pages/index.html.twig', [
            "system" => $this->getSystemEntity(),
            "mainMenu" => $this->getMainMenuEntity(),
            "sliderContentEntities" => $indexSliderContents,
            "productContentEntities" => $productContentEntities,
            "baseController" => $this,
        ]);
    }
}
