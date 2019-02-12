<?php

namespace App\Controller\AdminController;

use App\Repository\OrderEntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin/order/entity")
 */
class AdminOrderEntityController extends AbstractController
{
    /**
     * @Route("/", name="admin_order_entity_list")
     */
    public function index(OrderEntityRepository $orderEntityRepository, PaginatorInterface $paginator, Request $request)
    {
        $orderEntitys = $orderEntityRepository->findBy(array(),array("id"=>"DESC"));

        $pagination = $paginator->paginate(
            $orderEntitys, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            15/*limit per page*/
        );

        return $this->render('admin_pages/admin_order_entity/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }


}
