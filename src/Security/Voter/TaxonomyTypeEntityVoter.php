<?php

namespace App\Security\Voter;

use App\Entity\TaxonomyTypeEntity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class TaxonomyTypeEntityVoter extends Voter
{
    const TAXONOMY_TYPE_ENTITY_TAXONOMY_TYPE_ALIAS_VIEW = "taxonomyTypeEntity[taxonomyTypeAlias][view]";
    const TAXONOMY_TYPE_ENTITY_TAXONOMY_TYPE_ALIAS_NEW = "taxonomyTypeEntity[taxonomyTypeAlias][new]";
    const TAXONOMY_TYPE_ENTITY_TAXONOMY_TYPE_ALIAS_EDIT = "taxonomyTypeEntity[taxonomyTypeAlias][edit]";
    const TAXONOMY_TYPE_ENTITY_TAXONOMY_TYPE_ALIAS_DELETE = "taxonomyTypeEntity[taxonomyTypeAlias][delete]";

    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::TAXONOMY_TYPE_ENTITY_TAXONOMY_TYPE_ALIAS_VIEW, self::TAXONOMY_TYPE_ENTITY_TAXONOMY_TYPE_ALIAS_NEW,
                self::TAXONOMY_TYPE_ENTITY_TAXONOMY_TYPE_ALIAS_EDIT, self::TAXONOMY_TYPE_ENTITY_TAXONOMY_TYPE_ALIAS_DELETE])
            && $subject instanceof TaxonomyTypeEntity;
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

        //获取当前 $subject 所属的TaxonomyTypeEntity
        /** @var TaxonomyTypeEntity $subject **/
        $taxonomyTypeAlias = $subject->getTaxonomyAlias();

        switch ($attribute) {
            case self::TAXONOMY_TYPE_ENTITY_TAXONOMY_TYPE_ALIAS_VIEW:
                return array_key_exists("view", $userPermissionsJson["taxonomyTypeEntity"]["$taxonomyTypeAlias"])
                    && $userPermissionsJson["taxonomyTypeEntity"]["$taxonomyTypeAlias"]["view"] == "on";
                break;

            case self::TAXONOMY_TYPE_ENTITY_TAXONOMY_TYPE_ALIAS_NEW:
                return array_key_exists("new", $userPermissionsJson["taxonomyTypeEntity"]["$taxonomyTypeAlias"])
                    && $userPermissionsJson["taxonomyTypeEntity"]["$taxonomyTypeAlias"]["new"] == "on";
                break;

            case self::TAXONOMY_TYPE_ENTITY_TAXONOMY_TYPE_ALIAS_EDIT:
                return array_key_exists("edit", $userPermissionsJson["taxonomyTypeEntity"]["$taxonomyTypeAlias"])
                    && $userPermissionsJson["taxonomyTypeEntity"]["$taxonomyTypeAlias"]["edit"] == "on";
                break;

            case self::TAXONOMY_TYPE_ENTITY_TAXONOMY_TYPE_ALIAS_DELETE:
                return array_key_exists("delete", $userPermissionsJson["taxonomyTypeEntity"]["$taxonomyTypeAlias"])
                    && $userPermissionsJson["taxonomyTypeEntity"]["$taxonomyTypeAlias"]["delete"] == "on";
                break;
        }

        return false;
    }
}
