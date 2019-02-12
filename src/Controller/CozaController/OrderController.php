<?php

namespace App\Controller\CozaController;


use App\Entity\CartEntity;
use App\Entity\OrderEntity;
use App\Entity\UserEntity;
use App\Repository\ContentEntityRepository;
use App\Repository\UserAddressEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 订单Controller
 */
class OrderController extends BaseController
{
    /**
     * 前台添加到购物车功能,使用Ajax添加商品到购物车
     * @Route("/order/check", name="check_order", methods={"POST"})
     */
    public function checkOrder(Request $request, EntityManagerInterface $em, UserAddressEntityRepository $addressEntityRepository)
    {
//        return $this->json($request->request->all(), 200);

        /**@var UserEntity $user * */
        $user = $this->getUser();
        if (!$user) {
            return $this->json(array("message" => "用户未登录", "loginPath" => $this->generateUrl("fos_user_security_login")), 403);
        }

        if ($this->isCsrfTokenValid("check_order", $request->request->get("csrf_token"))) {
            $user = $this->getUser();

            $addressEntity = $addressEntityRepository->find($request->request->get("address-id"));

//            $orderEntity = new OrderEntity();
//            $orderEntity->setBuyer($user);
//            $now = new \DateTime();
//            $orderEntity->setCreateAt($now);
//            $orderEntity->setExpireAt($now->add(new \DateInterval('P3D')));

//            $em->persist();
//            $em->flush();

            return $this->json(array("message" => "商品已成功结算！"), 200);
        } else {
            return $this->json(array("message" => "token错误,请刷新页面后再次结算。"), 403);
        }
    }

}
