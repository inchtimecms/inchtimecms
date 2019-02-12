<?php

namespace App\Controller\AdminController;

use App\Repository\FieldTaxonomyTableEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */

class FieldTaxonomyTableController extends AbstractController
{
    /**
     * 用于内容编辑页面，ajax删除引用词汇
     * @Route("/field/taxonomy/table/delete", name="field_taxonomy_table_delete",methods="POST")
     *
     */
    public function delete(Request $request, FieldTaxonomyTableEntityRepository $fieldTaxonomyTableEntityRepository)
    {
        $id = $request->request->get("fieldTableId");
        $fieldTaxonomyTableEntity = $fieldTaxonomyTableEntityRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($fieldTaxonomyTableEntity);
        $em->flush();

        return new Response("1");
    }
}
