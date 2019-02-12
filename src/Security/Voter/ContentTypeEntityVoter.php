<?php

namespace App\Security\Voter;

use App\Entity\ContentTypeEntity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ContentTypeEntityVoter extends Voter
{
    const CONTENT_TYPE_ENTITY_CONTENT_TYPE_ALIAS_VIEW = "contentTypeEntity[contentTypeAlias][view]";
    const CONTENT_TYPE_ENTITY_CONTENT_TYPE_ALIAS_NEW = "contentTypeEntity[contentTypeAlias][new]";
    const CONTENT_TYPE_ENTITY_CONTENT_TYPE_ALIAS_EDIT = "contentTypeEntity[contentTypeAlias][edit]";
    const CONTENT_TYPE_ENTITY_CONTENT_TYPE_ALIAS_DELETE = "contentTypeEntity[contentTypeAlias][delete]";

    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::CONTENT_TYPE_ENTITY_CONTENT_TYPE_ALIAS_VIEW, self::CONTENT_TYPE_ENTITY_CONTENT_TYPE_ALIAS_NEW,
                self::CONTENT_TYPE_ENTITY_CONTENT_TYPE_ALIAS_EDIT, self::CONTENT_TYPE_ENTITY_CONTENT_TYPE_ALIAS_DELETE])
            && $subject instanceof ContentTypeEntity;
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

        //获取当前 $subject 所属的ContentTypeEntity
        /** @var ContentTypeEntity $subject **/
        $contentTypeAlias = $subject->getContentTypeMachineAlias();

        switch ($attribute) {
            case self::CONTENT_TYPE_ENTITY_CONTENT_TYPE_ALIAS_VIEW:
                return array_key_exists("view", $userPermissionsJson["contentTypeEntity"]["$contentTypeAlias"])
                    && $userPermissionsJson["contentTypeEntity"]["$contentTypeAlias"]["view"] == "on";
                break;

            case self::CONTENT_TYPE_ENTITY_CONTENT_TYPE_ALIAS_NEW:
                return array_key_exists("new", $userPermissionsJson["contentTypeEntity"]["$contentTypeAlias"])
                    && $userPermissionsJson["contentTypeEntity"]["$contentTypeAlias"]["new"] == "on";
                break;

            case self::CONTENT_TYPE_ENTITY_CONTENT_TYPE_ALIAS_EDIT:
                return array_key_exists("edit", $userPermissionsJson["contentTypeEntity"]["$contentTypeAlias"])
                    && $userPermissionsJson["contentTypeEntity"]["$contentTypeAlias"]["edit"] == "on";
                break;

            case self::CONTENT_TYPE_ENTITY_CONTENT_TYPE_ALIAS_DELETE:
                return array_key_exists("delete", $userPermissionsJson["contentTypeEntity"]["$contentTypeAlias"])
                    && $userPermissionsJson["contentTypeEntity"]["$contentTypeAlias"]["delete"] == "on";
                break;
        }

        return false;
    }
}
