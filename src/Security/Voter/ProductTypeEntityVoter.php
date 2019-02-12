<?php

namespace App\Security\Voter;

use App\Entity\ProductTypeEntity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductTypeEntityVoter extends Voter
{
    const PRODUCT_TYPE_ENTITY_PRODUCT_TYPE_ALIAS_VIEW = "productTypeEntity[productTypeAlias][view]";
    const PRODUCT_TYPE_ENTITY_PRODUCT_TYPE_ALIAS_NEW = "productTypeEntity[productTypeAlias][new]";
    const PRODUCT_TYPE_ENTITY_PRODUCT_TYPE_ALIAS_EDIT = "productTypeEntity[productTypeAlias][edit]";
    const PRODUCT_TYPE_ENTITY_PRODUCT_TYPE_ALIAS_DELETE = "productTypeEntity[productTypeAlias][delete]";

    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::PRODUCT_TYPE_ENTITY_PRODUCT_TYPE_ALIAS_VIEW, self::PRODUCT_TYPE_ENTITY_PRODUCT_TYPE_ALIAS_NEW,
                self::PRODUCT_TYPE_ENTITY_PRODUCT_TYPE_ALIAS_EDIT, self::PRODUCT_TYPE_ENTITY_PRODUCT_TYPE_ALIAS_DELETE])
            && $subject instanceof ProductTypeEntity;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // ROLE_SUPER_ADMIN can do anything! The power!
        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            return true;
        }

        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        //获取当前用户所有权限
        $userPermissionsJson = $user->getUserPermissionGroupEntity()->getPermissionJson();

        //获取当前 $subject 所属的TaxonomyTypeEntity
        /** @var ProductTypeEntity $subject **/
        $productTypeAlias = $subject->getProductAlias();

        switch ($attribute) {
            case self::PRODUCT_TYPE_ENTITY_PRODUCT_TYPE_ALIAS_VIEW:
                return array_key_exists("view", $userPermissionsJson["productTypeEntity"]["$productTypeAlias"])
                    && $userPermissionsJson["productTypeEntity"]["$productTypeAlias"]["view"] == "on";
                break;

            case self::PRODUCT_TYPE_ENTITY_PRODUCT_TYPE_ALIAS_NEW:
                return array_key_exists("new", $userPermissionsJson["productTypeEntity"]["$productTypeAlias"])
                    && $userPermissionsJson["productTypeEntity"]["$productTypeAlias"]["new"] == "on";
                break;

            case self::PRODUCT_TYPE_ENTITY_PRODUCT_TYPE_ALIAS_EDIT:
                return array_key_exists("edit", $userPermissionsJson["productTypeEntity"]["$productTypeAlias"])
                    && $userPermissionsJson["productTypeEntity"]["$productTypeAlias"]["edit"] == "on";
                break;

            case self::PRODUCT_TYPE_ENTITY_PRODUCT_TYPE_ALIAS_DELETE:
                return array_key_exists("delete", $userPermissionsJson["productTypeEntity"]["$productTypeAlias"])
                    && $userPermissionsJson["productTypeEntity"]["$productTypeAlias"]["delete"] == "on";
                break;
        }

        return false;
    }
}
