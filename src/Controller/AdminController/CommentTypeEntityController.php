<?php

namespace App\Controller\AdminController;

use App\Entity\CommentTypeEntity;
use App\Form\CommentTypeEntityFormType;
use App\Repository\CommentTypeEntityRepository;
use App\Security\Voter\UserPermissionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/comment/type/entity")
 */
class CommentTypeEntityController extends AbstractController
{
    /**
     * @Route("/list", name="comment_type_entity")
     */
    public function index(CommentTypeEntityRepository $repository)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::COMMENT_TYPE_ENTITY_VIEW);

        $commentTypeEntitys = $repository->findAll();

        return $this->render('admin_pages/comment_type_entity/index.html.twig', [
            'commentTypeEntitys' => $commentTypeEntitys,
        ]);
    }

    /**
     * @Route("/new", name="comment_type_new")
     */
    public function newCommentType(Request $request, EntityManagerInterface $em){

        $this->denyAccessUnlessGranted(UserPermissionVoter::COMMENT_TYPE_ENTITY_NEW);

        $commentTypeEntityForm = $this->createForm(CommentTypeEntityFormType::class);

        $commentTypeEntityForm->handleRequest($request);
        if ($commentTypeEntityForm->isSubmitted() && $commentTypeEntityForm->isValid()) {

            /**
             * @var CommentTypeEntity $commentTypeEntity
             */
            $commentTypeEntity = $commentTypeEntityForm->getData();

            $em->persist($commentTypeEntity);
            $em->flush();

            return $this->redirectToRoute('comment_type_entity');
        }

        return $this->render("admin_pages/comment_type_entity/new.html.twig",[
            "form" => $commentTypeEntityForm->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="comment_type_edit")
     */
    public function editCommentType(CommentTypeEntity $commentTypeEntity){

        $this->denyAccessUnlessGranted(UserPermissionVoter::COMMENT_TYPE_ENTITY_EDIT);

        $commentTypeEntityForm = $this->createForm(CommentTypeEntityFormType::class,$commentTypeEntity);

        return $this->render("admin_pages/comment_type_entity/edit.html.twig",[
            "form" => $commentTypeEntityForm->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="comment_type_delete")
     */
    public function deleteCommentType(CommentTypeEntity $commentTypeEntity, EntityManagerInterface $em){

        $this->denyAccessUnlessGranted(UserPermissionVoter::COMMENT_TYPE_ENTITY_DELETE);

        $comments = $commentTypeEntity->getComments();
        foreach($comments as $comment){
            $em->remove($comment);
        }

        $em->remove($commentTypeEntity);
        $em->flush();

        return $this->redirectToRoute("comment_type_entity");
    }
}

