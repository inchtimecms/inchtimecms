<?php

namespace App\Controller\AdminController;

use App\Entity\CommentEntity;
use App\Form\CommentEntityType;
use App\Repository\CommentEntityRepository;
use App\Security\Voter\UserPermissionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/comment/entity")
 */

class CommentEntityController extends AbstractController
{
    /**
     * @Route("/list", name="comment_entity")
     */
    public function index(CommentEntityRepository $commentEntityRepository)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::COMMENT_ENTITY_VIEW);
        $commentEntitys = $commentEntityRepository->findBy(array(),array("createAt"=>"DESC"));

        return $this->render('admin_pages/comment_entity/index.html.twig', [
            'commentEntitys' => $commentEntitys,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="comment_delete")
     */
    public function deleteComment(CommentEntity $comment, EntityManagerInterface $em){

        $this->denyAccessUnlessGranted(UserPermissionVoter::COMMENT_ENTITY_DELETE);

        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute("comment_entity");
    }

}
