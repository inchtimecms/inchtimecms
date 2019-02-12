<?php

namespace App\Security\Voter;

use App\Entity\TaxonomyEntity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class TaxonomyEntityVoter extends Voter
{
    const TAXONOMY_ENTITY_TAXONOMY_TYPE_ALIAS_VIEW = "taxonomyEntity[taxonomyTypeAlias][view]";
    const TAXONOMY_ENTITY_TAXONOMY_TYPE_ALIAS_NEW = "taxonomyEntity[taxonomyTypeAlias][new]";
    const TAXONOMY_ENTITY_TAXONOMY_TYPE_ALIAS_EDIT = "taxonomyEntity[taxonomyTypeAlias][edit]";
    const TAXONOMY_ENTITY_TAXONOMY_TYPE_ALIAS_DELETE = "taxonomyEntity[taxonomyTypeAlias][delete]";

    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::TAXONOMY_ENTITY_TAXONOMY_TYPE_ALIAS_VIEW, self::TAXONOMY_ENTITY_TAXONOMY_TYPE_ALIAS_NEW,
                self::TAXONOMY_ENTITY_TAXONOMY_TYPE_ALIAS_EDIT, self::TAXONOMY_ENTITY_TAXONOMY_TYPE_ALIAS_DELETE])
            && $subject instanceof TaxonomyEntity;
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
        /** @var TaxonomyEntity $subject **/
        $taxonomyTypeAlias = $subject->getTaxonomyTypeEntity()->getTaxonomyAlias();

        switch ($attribute) {
            case self::TAXONOMY_ENTITY_TAXONOMY_TYPE_ALIAS_VIEW:
                return array_key_exists("view", $userPermissionsJson["taxonomyEntity"]["$taxonomyTypeAlias"])
                    && $userPermissionsJson["taxonomyEntity"]["$taxonomyTypeAlias"]["view"] == "on";
                break;

            case self::TAXONOMY_ENTITY_TAXONOMY_TYPE_ALIAS_NEW:
                return array_key_exists("new", $userPermissionsJson["taxonomyEntity"]["$taxonomyTypeAlias"])
                    && $userPermissionsJson["taxonomyEntity"]["$taxonomyTypeAlias"]["new"] == "on";
                break;

            case self::TAXONOMY_ENTITY_TAXONOMY_TYPE_ALIAS_EDIT:
                return array_key_exists("edit", $userPermissionsJson["taxonomyEntity"]["$taxonomyTypeAlias"])
                    && $userPermissionsJson["taxonomyEntity"]["$taxonomyTypeAlias"]["edit"] == "on";
                break;

            case self::TAXONOMY_ENTITY_TAXONOMY_TYPE_ALIAS_DELETE:
                return array_key_exists("delete", $userPermissionsJson["taxonomyEntity"]["$taxonomyTypeAlias"])
                    && $userPermissionsJson["taxonomyEntity"]["$taxonomyTypeAlias"]["delete"] == "on";
                break;
        }

        return false;
    }
}
