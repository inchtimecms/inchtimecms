<?php

namespace App\DataFixtures;

use App\Entity\CommentTypeEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class InitCommentTypeEntityFixtures extends Fixture
{
    public const COMMENT_TYPE_REFERENCE = "comment_type";
    public function load(ObjectManager $manager)
    {
        $commentTypeEntity = new CommentTypeEntity();
        $commentTypeEntity->setCommentTypeName("纯文本");
        $commentTypeEntity->setCommentTypeAlias("plain");
        $commentTypeEntity->setCommentFilter("");
        $manager->persist($commentTypeEntity);

        $commentTypeEntity2 = new CommentTypeEntity();
        $commentTypeEntity2->setCommentTypeName("基本html标签");
        $commentTypeEntity2->setCommentTypeAlias("basic");
        $commentTypeEntity2->setCommentFilter("<a href hreflang> <em> <strong> <cite> <blockquote cite> <code> <ul type> <ol start type> <li> <dl> <dt> <dd> <h2 id> <h3 id> <h4 id> <h5 id> <h6 id>");
        $manager->persist($commentTypeEntity2);

        $manager->flush();

        $this->addReference(self::COMMENT_TYPE_REFERENCE, $commentTypeEntity);

    }
}
