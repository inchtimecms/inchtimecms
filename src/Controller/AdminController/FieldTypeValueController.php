<?php

namespace App\Controller\AdminController;

use App\Entity\FieldTypeValueEntity;
use App\Form\FieldTypeValueType;
use App\Repository\FieldTypeValueEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/field/type/value")
 */
class FieldTypeValueController extends AbstractController
{
    /**
     * @Route("/findall",name="field_type_value_findAll",methods="GET")
     */
    public function findAllValue_JSON(FieldTypeValueEntityRepository $fieldTypeValueRepository): Response
    {
        $fieldTypeValueObjs = $fieldTypeValueRepository->findBy(array(), ['fieldTypeValueType' => 'DESC']);

        $results = [];
        foreach ($fieldTypeValueObjs as $fieldTypeValueObj) {
            $results[] = [
                'id' => $fieldTypeValueObj->getId(),
                'fieldTypeValueType' => $fieldTypeValueObj->getFieldTypeValueType(),
                'fieldValueTypeName' => $fieldTypeValueObj->getFieldValueTypeName(),
                'fieldTypeInSQL' => $fieldTypeValueObj->getFieldTypeInSQL()
            ];
        }

        $json_response = new JsonResponse($results);
        $json_response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $json_response;
    }


}
