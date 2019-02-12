<?php

namespace App\Controller\CozaController;

use App\Entity\CartEntity;
use App\Entity\ContentEntity;
use App\Entity\ProductTypeEntity;
use App\Repository\MenuEntityRepository;
use App\Repository\ProductTypeEntityRepository;
use App\Repository\SystemEntityRepository;
use App\Repository\TaxonomyTypeEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    private $systemEntity;

    private $mainMenuEntity;

    private $taxonomyTypeEntityRepo;

    private $em;

    private $productTypeEntityRepository;

    public function __construct(SystemEntityRepository $systemEntityRepository,
                                TaxonomyTypeEntityRepository $taxonomyTypeEntityRepository,
                                ProductTypeEntityRepository $productTypeEntityRepository,
                                EntityManagerInterface $em,
                                MenuEntityRepository $menuEntityRepository)
    {
        $this->taxonomyTypeEntityRepo = $taxonomyTypeEntityRepository;

        $this->em = $em;

        $this->productTypeEntityRepository = $productTypeEntityRepository;

        $this->systemEntity = $systemEntityRepository->find(1);

        $this->mainMenuEntity = $menuEntityRepository->findOneBy(array("menuAlias" => "main"));
    }

    /**
     * @return \App\Entity\SystemEntity|null
     */
    public function getSystemEntity(): ?\App\Entity\SystemEntity
    {
        return $this->systemEntity;
    }

    /**
     * @return \App\Entity\MenuEntity|null
     */
    public function getMainMenuEntity(): ?\App\Entity\MenuEntity
    {
        return $this->mainMenuEntity;
    }

    /**
     * 查找某个分类标签下的所有分类词汇,先通过别名找到TaxonomyTypeEntity再获取所有的TaxonomyEntity;
     * 参数：
     * $taxonomyTypeAlias: TaxonomyTypeEntity 的别名
     */
    public function getTaxonomyEntities(string $taxonomyTypeAlias){
        $taxonomyTypeEntity = $this->taxonomyTypeEntityRepo->findOneBy(array("taxonomyAlias" => $taxonomyTypeAlias));

        /**@var TaxonomyEntity[] $taxonomyEntities**/
        $taxonomyEntities = $taxonomyTypeEntity->getTaxonomyEntitys();

        return $taxonomyEntities;
    }

    /**
     * 随机查找指定数量的商品
     */
    public function getRandomProducts(int $number){

        /** @var ContentEntity[] $productContentEntities **/
        $productContentEntities = $this->em->createQuery("SELECT c FROM App\Entity\ContentEntity c JOIN c.contentTypeEntity t JOIN t.productTypeEntity p WHERE p.id IS NOT NULL ORDER BY RAND()")
            ->setMaxResults($number)
            ->getResult();

        return $productContentEntities;
    }

    /**
     * 查找所有的商品类型，用于页面顶部过滤分类
     */
    public function getAllProductTypeEntities(){
        /**@var ProductTypeEntity[] $productTypeEntities**/
        $productTypeEntities = $this->productTypeEntityRepository->findAll();

        return $productTypeEntities;
    }

    /**
     * 获取当前用户购物车中商品数量
     */
    public function getCurrUserCartItemNumber(){
        $user = $this->getUser();
        //过滤已结算过的商品
        /**@var CartEntity[] $cartEntities**/
        $cartEntities = $this->em->createQuery("SELECT c FROM App\Entity\CartEntity c WHERE c.buyer = :user AND c.boolChecked = false ORDER BY c.id DESC")
            ->setParameter("user", $user)
            ->getResult();

        return sizeof($cartEntities);
    }
}
