<?php

namespace App\Controller\CozaController;

use App\Entity\UserAddressEntity;
use App\Repository\UserAddressEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
    /**
     * @Route("/user", name="user")
     */
    public function userIndex(Request $request, EntityManagerInterface $em)
    {
        return $this->render("themes/cozastore/pages/user.html.twig",[
            "baseController" => $this,

        ]);
    }

    /**
     * @Route("/user/profile", name="user_profile")
     */
    public function userProfile(Request $request, EntityManagerInterface $em)
    {
        return $this->render("themes/cozastore/pages/user_profile.html.twig",[
            "baseController" => $this,

        ]);
    }

    /**
     * @Route("/user/address", name="user_address")
     */
    public function userAddress(UserAddressEntityRepository $addressEntityRepository)
    {
        /**@var UserAddressEntity[] $addressEntities **/
        $addressEntities = $addressEntityRepository->findBy(array("userEntity" => $this->getUser()));

        return $this->render("themes/cozastore/pages/user_address.html.twig",[
            "baseController" => $this,
            "addressEntities" => $addressEntities,
        ]);
    }

    /**
     * 用户添加收货地址
     * @Route("/user/address/add", name="user_address_add")
     */
    public function userAddressAdd(Request $request, EntityManagerInterface $em, UserAddressEntityRepository $addressEntityRepository)
    {
        $token = $request->request->get("csrf_token");
        if ($this->isCsrfTokenValid("add_address",$token)){
            $province = $request->request->get("province");
            $city = $request->request->get("city");
            $district = $request->request->get("district");
            $address = $request->request->get("address");
            $zipcode = $request->request->get("zipcode") == "" ? "000000" : $request->request->get("zipcode");
            $name = $request->request->get("name");
            $phone = $request->request->get("phone");
            $default = $request->request->get("set_default") == "on" ? true : false;

            if ($default){
                $addresses = $addressEntityRepository->findBy(array("userEntity" => $this->getUser()));
                foreach ($addresses as $address){
                    $address->setBoolDefault(false);
                    $em->persist($address);
                }
            }

            $addressEntity = new UserAddressEntity();
            $addressEntity->setProvince($province);
            $addressEntity->setCity($city);
            $addressEntity->setDistrict($district);
            $addressEntity->setAddressInfo($address);
            $addressEntity->setZipcode($zipcode);
            $addressEntity->setConsigneeName($name);
            $addressEntity->setConsigeneePhone($phone);
            $addressEntity->setBoolDefault($default);
            $addressEntity->setUserEntity($this->getUser());


            $em->persist($addressEntity);

            $em->flush();

        }
        return $this->redirectToRoute("user_address");
    }
}
