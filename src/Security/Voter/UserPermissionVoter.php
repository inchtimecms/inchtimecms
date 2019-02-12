<?php

namespace App\Security\Voter;

use App\Entity\UserEntity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * 用户权限Voter,用于防止用户使用路径绕过权限限制,只对基本的不传参数的的权限
 */
class UserPermissionVoter extends Voter
{
    const CONTENT_ENTITY_VIEW = "contentEntity[view]";
    const CONTENT_ENTITY_NEW = "contentEntity[new]";

    const CONTENT_TYPE_ENTITY_VIEW = "contentTypeEntity[view]";
    const CONTENT_TYPE_ENTITY_NEW = "contentTypeEntity[new]";
    const CONTENT_TYPE_ENTITY_EDIT = "contentTypeEntity[edit]";
    const CONTENT_TYPE_ENTITY_DELETE = "contentTypeEntity[delete]";

    const TAXONOMY_TYPE_ENTITY_VIEW = "taxonomyTypeEntity[view]";
    const TAXONOMY_TYPE_ENTITY_NEW = "taxonomyTypeEntity[new]";
    const TAXONOMY_TYPE_ENTITY_EDIT = "taxonomyTypeEntity[edit]";
    const TAXONOMY_TYPE_ENTITY_DELETE = "taxonomyTypeEntity[delete]";

    const FILE_MANAGER_ENTITY_VIEW = "fileManageEntity[view]";
    const FILE_MANAGER_ENTITY_NEW = "fileManageEntity[new]";
    const FILE_MANAGER_ENTITY_EDIT = "fileManageEntity[edit]";
    const FILE_MANAGER_ENTITY_DELETE = "fileManageEntity[delete]";

    const PRODUCT_CONTENT_ENTITY_VIEW = "productContentEntity[view]";
    const PRODUCT_CONTENT_ENTITY_NEW = "productContentEntity[new]";

    const PRODUCT_TYPE_ENTITY_VIEW = "productTypeEntity[view]";
    const PRODUCT_TYPE_ENTITY_NEW = "productTypeEntity[new]";
    const PRODUCT_TYPE_ENTITY_EDIT = "productTypeEntity[edit]";
    const PRODUCT_TYPE_ENTITY_DELETE = "productTypeEntity[delete]";

    const SHIP_FEE_TEMPLATE_VIEW = "shipFeeTemplate[view]";
    const SHIP_FEE_TEMPLATE_NEW = "shipFeeTemplate[new]";
    const SHIP_FEE_TEMPLATE_EDIT = "shipFeeTemplate[edit]";
    const SHIP_FEE_TEMPLATE_DELETE = "shipFeeTemplate[delete]";

    const PAY_METHOD_ENTITY_VIEW = "payMethodEntity[view]";
    const PAY_METHOD_ENTITY_NEW = "payMethodEntity[new]";
    const PAY_METHOD_ENTITY_EDIT = "payMethodEntity[edit]";
    const PAY_METHOD_ENTITY_DELETE = "payMethodEntity[delete]";

    const ORDER_ENTITY_VIEW = "orderEntity[view]";
    const ORDER_ENTITY_EDIT = "orderEntity[edit]";

    const MENU_ENTITY_VIEW = "menuEntity[view]";
    const MENU_ENTITY_NEW = "menuEntity[new]";
    const MENU_ENTITY_EDIT = "menuEntity[edit]";
    const MENU_ENTITY_DELETE = "menuEntity[delete]";

    const CONTACT_FORM_TYPE_ENTITY_VIEW = "contactFormTypeEntity[view]";
    const CONTACT_FORM_TYPE_ENTITY_NEW = "contactFormTypeEntity[new]";
    const CONTACT_FORM_TYPE_ENTITY_EDIT = "contactFormTypeEntity[edit]";
    const CONTACT_FORM_TYPE_ENTITY_DELETE = "contactFormTypeEntity[delete]";

    const CONTACT_FORM_ENTITY_VIEW = "contactFormEntity[view]";
    const CONTACT_FORM_ENTITY_EDIT = "contactFormEntity[edit]";
    const CONTACT_FORM_ENTITY_DELETE = "contactFormEntity[delete]";

    const COMMENT_TYPE_ENTITY_VIEW = "commentTypeEntity[view]";
    const COMMENT_TYPE_ENTITY_NEW = "commentTypeEntity[new]";
    const COMMENT_TYPE_ENTITY_EDIT = "commentTypeEntity[edit]";
    const COMMENT_TYPE_ENTITY_DELETE = "commentTypeEntity[delete]";

    const COMMENT_ENTITY_VIEW = "commentEntity[view]";
    const COMMENT_ENTITY_EDIT = "commentEntity[edit]";
    const COMMENT_ENTITY_DELETE = "commentEntity[delete]";

    const USER_ENTITY_VIEW = "userEntity[view]";
    const USER_ENTITY_NEW = "userEntity[new]";
    const USER_ENTITY_EDIT = "userEntity[edit]";
    const USER_ENTITY_DELETE = "userEntity[delete]";

    const USER_GROUP_ENTITY_VIEW = "userGroupEntity[view]";
    const USER_GROUP_ENTITY_NEW = "userGroupEntity[new]";
    const USER_GROUP_ENTITY_EDIT = "userGroupEntity[edit]";
    const USER_GROUP_ENTITY_DELETE = "userGroupEntity[delete]";

    const SYSTEM_ENTITY_VIEW = "systemEntity[view]";
    const SYSTEM_ENTITY_EDIT = "systemEntity[edit]";

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        $permissionArray = [self::CONTENT_ENTITY_VIEW, self::CONTENT_ENTITY_NEW,
            self::CONTENT_TYPE_ENTITY_VIEW, self::CONTENT_TYPE_ENTITY_NEW, self::CONTENT_TYPE_ENTITY_EDIT, self::CONTENT_TYPE_ENTITY_DELETE,
            self::TAXONOMY_TYPE_ENTITY_VIEW, self::TAXONOMY_TYPE_ENTITY_NEW, self::TAXONOMY_TYPE_ENTITY_EDIT, self::TAXONOMY_TYPE_ENTITY_DELETE,
            self::FILE_MANAGER_ENTITY_VIEW, self::FILE_MANAGER_ENTITY_NEW, self::FILE_MANAGER_ENTITY_EDIT, self::FILE_MANAGER_ENTITY_DELETE,
            self::PRODUCT_CONTENT_ENTITY_VIEW, self::PRODUCT_CONTENT_ENTITY_NEW,
            self::PRODUCT_TYPE_ENTITY_VIEW, self::PRODUCT_TYPE_ENTITY_NEW, self::PRODUCT_TYPE_ENTITY_EDIT, self::PRODUCT_TYPE_ENTITY_DELETE,
            self::SHIP_FEE_TEMPLATE_VIEW, self::SHIP_FEE_TEMPLATE_NEW, self::SHIP_FEE_TEMPLATE_EDIT, self::SHIP_FEE_TEMPLATE_DELETE,
            self::PAY_METHOD_ENTITY_VIEW, self::PAY_METHOD_ENTITY_NEW, self::PAY_METHOD_ENTITY_EDIT, self::PAY_METHOD_ENTITY_DELETE,
            self::ORDER_ENTITY_VIEW, self::ORDER_ENTITY_EDIT,
            self::MENU_ENTITY_VIEW, self::MENU_ENTITY_NEW, self::MENU_ENTITY_EDIT, self::MENU_ENTITY_DELETE,
            self::CONTACT_FORM_TYPE_ENTITY_VIEW, self::CONTACT_FORM_TYPE_ENTITY_NEW, self::CONTACT_FORM_TYPE_ENTITY_EDIT, self::CONTACT_FORM_TYPE_ENTITY_DELETE,
            self::CONTACT_FORM_ENTITY_VIEW, self::CONTACT_FORM_ENTITY_EDIT, self::CONTACT_FORM_ENTITY_DELETE,
            self::COMMENT_TYPE_ENTITY_VIEW, self::COMMENT_TYPE_ENTITY_NEW, self::COMMENT_TYPE_ENTITY_EDIT, self::COMMENT_TYPE_ENTITY_DELETE,
            self::COMMENT_ENTITY_VIEW, self::COMMENT_ENTITY_EDIT, self::COMMENT_ENTITY_DELETE,
            self::USER_ENTITY_VIEW, self::USER_ENTITY_NEW, self::USER_ENTITY_EDIT, self::USER_ENTITY_DELETE,
            self::USER_GROUP_ENTITY_VIEW, self::USER_GROUP_ENTITY_NEW, self::USER_GROUP_ENTITY_EDIT, self::USER_GROUP_ENTITY_DELETE,
            self::SYSTEM_ENTITY_VIEW, self::SYSTEM_ENTITY_EDIT];
        return in_array($attribute, $permissionArray);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // ROLE_SUPER_ADMIN can do anything! The power!
        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            return true;
        }

        /** @var UserEntity $user**/
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        //获取用户所有权限
        $userPermissionsJson = $user->getUserPermissionGroupEntity()->getPermissionJson();

        switch ($attribute) {
            case self::CONTENT_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["contentEntity"])
                    && $userPermissionsJson["contentEntity"]["view"] == "on";
                break;
            case self::CONTENT_ENTITY_NEW:
                return array_key_exists("new", $userPermissionsJson["contentEntity"])
                    && $userPermissionsJson["contentEntity"]["new"] == "on";
                break;
            case self::CONTENT_TYPE_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["contentTypeEntity"])
                    && $userPermissionsJson["contentTypeEntity"]["view"] == "on";
                break;
            case self::CONTENT_TYPE_ENTITY_NEW:
                return array_key_exists("new", $userPermissionsJson["contentTypeEntity"])
                    && $userPermissionsJson["contentTypeEntity"]["new"] == "on";
                break;
            case self::CONTENT_TYPE_ENTITY_EDIT:
                return array_key_exists("edit", $userPermissionsJson["contentTypeEntity"])
                    && $userPermissionsJson["contentTypeEntity"]["edit"] == "on";
                break;
            case self::CONTENT_TYPE_ENTITY_DELETE:
                return array_key_exists("delete", $userPermissionsJson["contentTypeEntity"])
                    && $userPermissionsJson["contentTypeEntity"]["delete"] == "on";
                break;
            case self::TAXONOMY_TYPE_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["taxonomyTypeEntity"])
                    && $userPermissionsJson["taxonomyTypeEntity"]["view"] == "on";
                break;
            case self::TAXONOMY_TYPE_ENTITY_NEW:
                return array_key_exists("new", $userPermissionsJson["taxonomyTypeEntity"])
                    && $userPermissionsJson["taxonomyTypeEntity"]["new"] == "on";
                break;
            case self::TAXONOMY_TYPE_ENTITY_EDIT:
                return array_key_exists("edit", $userPermissionsJson["taxonomyTypeEntity"])
                    && $userPermissionsJson["taxonomyTypeEntity"]["edit"] == "on";
                break;
            case self::TAXONOMY_TYPE_ENTITY_DELETE:
                return array_key_exists("delete", $userPermissionsJson["taxonomyTypeEntity"])
                    && $userPermissionsJson["taxonomyTypeEntity"]["delete"] == "on";
                break;
            case self::FILE_MANAGER_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["fileManageEntity"])
                    && $userPermissionsJson["fileManageEntity"]["view"] == "on";
                break;
            case self::FILE_MANAGER_ENTITY_NEW:
                return array_key_exists("new", $userPermissionsJson["fileManageEntity"])
                    && $userPermissionsJson["fileManageEntity"]["new"] == "on";
                break;
            case self::FILE_MANAGER_ENTITY_EDIT:
                return array_key_exists("edit", $userPermissionsJson["fileManageEntity"])
                    && $userPermissionsJson["fileManageEntity"]["edit"] == "on";
                break;
            case self::FILE_MANAGER_ENTITY_DELETE:
                return array_key_exists("delete", $userPermissionsJson["fileManageEntity"])
                    && $userPermissionsJson["fileManageEntity"]["delete"] == "on";
                break;
            case self::PRODUCT_CONTENT_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["productContentEntity"])
                    && $userPermissionsJson["productContentEntity"]["view"] == "on";
                break;
            case self::PRODUCT_CONTENT_ENTITY_NEW:
                return array_key_exists("new", $userPermissionsJson["productContentEntity"])
                    && $userPermissionsJson["productContentEntity"]["new"] == "on";
                break;
            case self::PRODUCT_TYPE_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["productTypeEntity"])
                    && $userPermissionsJson["productTypeEntity"]["view"] == "on";
                break;
            case self::PRODUCT_TYPE_ENTITY_NEW:
                return array_key_exists("new", $userPermissionsJson["productTypeEntity"])
                    && $userPermissionsJson["productTypeEntity"]["new"] == "on";
                break;
            case self::PRODUCT_TYPE_ENTITY_EDIT:
                return array_key_exists("edit", $userPermissionsJson["productTypeEntity"])
                    && $userPermissionsJson["productTypeEntity"]["edit"] == "on";
                break;
            case self::PRODUCT_TYPE_ENTITY_DELETE:
                return array_key_exists("delete", $userPermissionsJson["productTypeEntity"])
                    && $userPermissionsJson["productTypeEntity"]["delete"] == "on";
                break;
            case self::SHIP_FEE_TEMPLATE_VIEW:
                return array_key_exists("view", $userPermissionsJson["shipFeeTemplate"])
                    && $userPermissionsJson["shipFeeTemplate"]["view"] == "on";
                break;
            case self::SHIP_FEE_TEMPLATE_NEW:
                return array_key_exists("new", $userPermissionsJson["shipFeeTemplate"])
                    && $userPermissionsJson["shipFeeTemplate"]["new"] == "on";
                break;
            case self::SHIP_FEE_TEMPLATE_EDIT:
                return array_key_exists("edit", $userPermissionsJson["shipFeeTemplate"])
                    && $userPermissionsJson["shipFeeTemplate"]["edit"] == "on";
                break;
            case self::SHIP_FEE_TEMPLATE_DELETE:
                return array_key_exists("delete", $userPermissionsJson["shipFeeTemplate"])
                    && $userPermissionsJson["shipFeeTemplate"]["delete"] == "on";
                break;
            case self::PAY_METHOD_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["payMethodEntity"])
                    && $userPermissionsJson["payMethodEntity"]["view"] == "on";
                break;
            case self::PAY_METHOD_ENTITY_NEW:
                return array_key_exists("new", $userPermissionsJson["payMethodEntity"])
                    && $userPermissionsJson["payMethodEntity"]["new"] == "on";
                break;
            case self::PAY_METHOD_ENTITY_EDIT:
                return array_key_exists("edit", $userPermissionsJson["payMethodEntity"])
                    && $userPermissionsJson["payMethodEntity"]["edit"] == "on";
                break;
            case self::PAY_METHOD_ENTITY_DELETE:
                return array_key_exists("delete", $userPermissionsJson["payMethodEntity"])
                    && $userPermissionsJson["payMethodEntity"]["delete"] == "on";
                break;
            case self::ORDER_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["orderEntity"])
                    && $userPermissionsJson["orderEntity"]["view"] == "on";
                break;
            case self::ORDER_ENTITY_EDIT:
                return array_key_exists("edit", $userPermissionsJson["orderEntity"])
                    && $userPermissionsJson["orderEntity"]["edit"] == "on";
                break;
            case self::MENU_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["menuEntity"])
                    && $userPermissionsJson["menuEntity"]["view"] == "on";
                break;
            case self::MENU_ENTITY_NEW:
                return array_key_exists("new", $userPermissionsJson["menuEntity"])
                    && $userPermissionsJson["menuEntity"]["new"] == "on";
                break;
            case self::MENU_ENTITY_EDIT:
                return array_key_exists("edit", $userPermissionsJson["menuEntity"])
                    && $userPermissionsJson["menuEntity"]["edit"] == "on";
                break;
            case self::MENU_ENTITY_DELETE:
                return array_key_exists("delete", $userPermissionsJson["menuEntity"])
                    && $userPermissionsJson["menuEntity"]["delete"] == "on";
                break;
            case self::CONTACT_FORM_TYPE_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["contactFormTypeEntity"])
                    && $userPermissionsJson["contactFormTypeEntity"]["view"] == "on";
                break;
            case self::CONTACT_FORM_TYPE_ENTITY_NEW:
                return array_key_exists("new", $userPermissionsJson["contactFormTypeEntity"])
                    && $userPermissionsJson["contactFormTypeEntity"]["view"] == "on";
                break;
            case self::CONTACT_FORM_TYPE_ENTITY_EDIT:
                return array_key_exists("edit", $userPermissionsJson["contactFormTypeEntity"])
                    && $userPermissionsJson["contactFormTypeEntity"]["view"] == "on";
                break;
            case self::CONTACT_FORM_TYPE_ENTITY_DELETE:
                return array_key_exists("delete", $userPermissionsJson["contactFormTypeEntity"])
                    && $userPermissionsJson["contactFormTypeEntity"]["view"] == "on";
                break;
            case self::CONTACT_FORM_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["contactFormEntity"])
                    && $userPermissionsJson["contactFormEntity"]["view"] == "on";
                break;
            case self::CONTACT_FORM_ENTITY_EDIT:
                return array_key_exists("edit", $userPermissionsJson["contactFormEntity"])
                    && $userPermissionsJson["contactFormEntity"]["edit"] == "on";
                break;
            case self::CONTACT_FORM_ENTITY_DELETE:
                return array_key_exists("delete", $userPermissionsJson["contactFormEntity"])
                    && $userPermissionsJson["contactFormEntity"]["delete"] == "on";
                break;
            case self::COMMENT_TYPE_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["commentTypeEntity"])
                    && $userPermissionsJson["commentTypeEntity"]["view"] == "on";
                break;
            case self::COMMENT_TYPE_ENTITY_NEW:
                return array_key_exists("new", $userPermissionsJson["commentTypeEntity"])
                    && $userPermissionsJson["commentTypeEntity"]["new"] == "on";
                break;
            case self::COMMENT_TYPE_ENTITY_EDIT:
                return array_key_exists("edit", $userPermissionsJson["commentTypeEntity"])
                    && $userPermissionsJson["commentTypeEntity"]["edit"] == "on";
                break;
            case self::COMMENT_TYPE_ENTITY_DELETE:
                return array_key_exists("delete", $userPermissionsJson["commentTypeEntity"])
                    && $userPermissionsJson["commentTypeEntity"]["delete"] == "on";
                break;
            case self::COMMENT_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["commentEntity"])
                    && $userPermissionsJson["commentEntity"]["view"] == "on";
                break;
            case self::COMMENT_ENTITY_EDIT:
                return array_key_exists("edit", $userPermissionsJson["commentEntity"])
                    && $userPermissionsJson["commentEntity"]["edit"] == "on";
                break;
            case self::COMMENT_ENTITY_DELETE:
                return array_key_exists("delete", $userPermissionsJson["commentEntity"])
                    && $userPermissionsJson["commentEntity"]["delete"] == "on";
                break;
            case self::USER_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["userEntity"])
                    && $userPermissionsJson["userEntity"]["view"] == "on";
                break;
            case self::USER_ENTITY_NEW:
                return array_key_exists("new", $userPermissionsJson["userEntity"])
                    && $userPermissionsJson["userEntity"]["new"] == "on";
                break;
            case self::USER_ENTITY_EDIT:
                return array_key_exists("edit", $userPermissionsJson["userEntity"])
                    && $userPermissionsJson["userEntity"]["edit"] == "on";
                break;
            case self::USER_ENTITY_DELETE:
                return array_key_exists("delete", $userPermissionsJson["userEntity"])
                    && $userPermissionsJson["userEntity"]["delete"] == "on";
                break;
            case self::USER_GROUP_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["userGroupEntity"])
                    && $userPermissionsJson["userGroupEntity"]["view"] == "on";
                break;
            case self::USER_GROUP_ENTITY_NEW:
                return array_key_exists("new", $userPermissionsJson["userGroupEntity"])
                    && $userPermissionsJson["userGroupEntity"]["new"] == "on";
                break;
            case self::USER_GROUP_ENTITY_EDIT:
                return array_key_exists("edit", $userPermissionsJson["userGroupEntity"])
                    && $userPermissionsJson["userGroupEntity"]["edit"] == "on";
                break;
            case self::USER_GROUP_ENTITY_DELETE:
                return array_key_exists("delete", $userPermissionsJson["userGroupEntity"])
                    && $userPermissionsJson["userGroupEntity"]["delete"] == "on";
                break;
            case self::SYSTEM_ENTITY_VIEW:
                return array_key_exists("view", $userPermissionsJson["systemEntity"])
                    && $userPermissionsJson["systemEntity"]["view"] == "on";
                break;
            case self::SYSTEM_ENTITY_EDIT:
                return array_key_exists("edit", $userPermissionsJson["systemEntity"])
                    && $userPermissionsJson["systemEntity"]["edit"] == "on";
                break;

        }

        return false;
    }
}
