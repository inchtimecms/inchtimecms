<?php

namespace App\Controller\AdminController;

use App\Repository\FieldContentTableEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class FieldContentTableController extends AbstractController
{
    /**
     * 用于内容编辑页面，ajax删除引入的内容
     * @Route("/field/content/table/delete", name="field_content_table_delete", methods="POST")
     */
    public function delete(Request $request, FieldContentTableEntityRepository $contentTableEntityRepository, EntityManagerInterface $em)
    {
        $id = $request->request->get("fieldTableId");
        $contentTableEntity = $contentTableEntityRepository->find($id);
        //$em = $this->getDoctrine()->getManager();
        $em->remove($contentTableEntity);
        $em->flush();
        return new Response("1");
    }
}
