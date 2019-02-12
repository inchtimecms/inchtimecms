<?php

namespace App\Security\Voter;

use App\Entity\ContentEntity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * 各个不同内容类型的内容的权限
 */
class ContentEntityVoter extends Voter
{

    const CONTENT_ENTITY_CONTENT_TYPE_ALIAS_VIEW = "contentEntity[contentTypeAlias][view]";
    const CONTENT_ENTITY_CONTENT_TYPE_ALIAS_NEW = "contentEntity[contentTypeAlias][new]";
    const CONTENT_ENTITY_CONTENT_TYPE_ALIAS_EDIT = "contentEntity[contentTypeAlias][edit]";
    const CONTENT_ENTITY_CONTENT_TYPE_ALIAS_DELETE = "contentEntity[contentTypeAlias][delete]";

    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::CONTENT_ENTITY_CONTENT_TYPE_ALIAS_VIEW, self::CONTENT_ENTITY_CONTENT_TYPE_ALIAS_NEW,
                self::CONTENT_ENTITY_CONTENT_TYPE_ALIAS_EDIT, self::CONTENT_ENTITY_CONTENT_TYPE_ALIAS_DELETE])
            && $subject instanceof ContentEntity;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // ROLE_SUPER_ADMIN can do anything! The power!
        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            return true;
        }

        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        //获取当前用户所有权限
        $userPermissionsJson = $user->getUserPermissionGroupEntity()->getPermissionJson();

        //获取当前 $subject 所属的内容类型
        /** @var ContentEntity $subject **/
        $contentTypeAlias = $subject->getContentTypeEntity()->getContentTypeMachineAlias();
        switch ($attribute) {
            case self::CONTENT_ENTITY_CONTENT_TYPE_ALIAS_VIEW:
                return array_key_exists("view", $userPermissionsJson["contentEntity"]["$contentTypeAlias"])
                    && $userPermissionsJson["contentEntity"]["$contentTypeAlias"]["view"] == "on";
                break;

            case self::CONTENT_ENTITY_CONTENT_TYPE_ALIAS_NEW:
                return array_key_exists("new", $userPermissionsJson["contentEntity"]["$contentTypeAlias"])
                    && $userPermissionsJson["contentEntity"]["$contentTypeAlias"]["new"] == "on";
                break;

            case self::CONTENT_ENTITY_CONTENT_TYPE_ALIAS_EDIT:
                return array_key_exists("edit", $userPermissionsJson["contentEntity"]["$contentTypeAlias"])
                    && $userPermissionsJson["contentEntity"]["$contentTypeAlias"]["edit"] == "on";
                break;

            case self::CONTENT_ENTITY_CONTENT_TYPE_ALIAS_DELETE:
                return array_key_exists("delete", $userPermissionsJson["contentEntity"]["$contentTypeAlias"])
                    && $userPermissionsJson["contentEntity"]["$contentTypeAlias"]["delete"] == "on";
                break;
        }

        return false;
    }
}
