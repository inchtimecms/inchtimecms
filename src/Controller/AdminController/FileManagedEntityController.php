<?php

namespace App\Controller\AdminController;

use App\Entity\FileManagedEntity;
use App\Repository\FileManagerEntityRepository;
use App\Security\Voter\UserPermissionVoter;
use App\Utils\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
/**
 * @Route("/admin/file/managed/entity")
 */
class FileManagedEntityController extends AbstractController
{
    /**
     * @Route("/", name="file_managed_entity_index", methods="GET")
     */
    public function index(Request $request, PaginatorInterface $paginator,EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::FILE_MANAGER_ENTITY_VIEW);

        $fileManagerEntitys = $em->createQuery("SELECT c FROM App\Entity\FileManagedEntity c JOIN c.fieldImageTableEntity f WHERE c.deleted = 0 ORDER BY c.id DESC")->getResult();

        $pagination = $paginator->paginate(
            $fileManagerEntitys,
            $request->query->getInt('page', 1)/*page number*/,
            24/*limit per page*/
        );
        return $this->render("admin_pages/file_managed_entity/index.html.twig", [
            "file_managed_entities" => $fileManagerEntitys,
            "baseUrl" => $request->getSchemeAndHttpHost(),
            "pagination" => $pagination
        ]);
    }

    /**
     * 文件上传： 选中文件后，自动上传文件，并回显为文件
     * @Route("/file_upload", name="file_upload", methods="POST")
     */
    public function fileUpload(Request $request, FileUploader $fileUploader, EntityManagerInterface $em, CacheManager $cacheManager): Response
    {
        $file = $request->files->get("fileUpload");

        //获取允许的文件MIME
        $allowFileExtensionStr = $request->request->get("file_extensions");
        //获取当前文件的MIME
        $currentFileMime = $file->getClientMimeType();

        //如果允许的后缀为空，或者允许的后缀不空并且上传的文件后缀在允许列表内，则上传文件到服务器，其他情况直接返回-1
        if($allowFileExtensionStr == "" || ($allowFileExtensionStr !="" && stripos($allowFileExtensionStr, $currentFileMime) !== false))
        {
            $fileArray= $fileUploader->upload($file);
            //文件的URI全路径
            $uploadFileUri = "/files/fileUpload".$fileArray["fileDir"]."/".$fileArray["fileName"];
            //文件的原始名称
            $originFileName = $file->getClientOriginalName();

            //创建一个文件对象
            $fileManagedEntity = new FileManagedEntity();
            $fileManagedEntity->setUuidName($fileArray["fileName"]);
            $fileManagedEntity->setUri($uploadFileUri);
            $fileManagedEntity->setFileName($originFileName);
            $fileManagedEntity->setFilemime($currentFileMime);
            $fileManagedEntity->setFileSize($fileArray["fileSize"]);
            $fileManagedEntity->setCreatedAt(new \DateTime());
            $fileManagedEntity->setChangedAt(new \DateTime());
            $fileManagedEntity->setDeleted(0);

            //把当前的对象保存到库
            $em->persist($fileManagedEntity);
            $em->flush();

            $fileSrcPath = $fileOriginSrc = $fileManagedEntity->getUri();
            //如果当前上传的是销售属性图片
            if ($request->request->get("bool_Sale_Prop_Img") == true){
                $fileSrcPath = $cacheManager->getBrowserPath($fileManagedEntity->getUri(), "product_saleprops_thumbnail");
                $fileOriginSrc = $cacheManager->getBrowserPath($fileManagedEntity->getUri(), "product_mainpic_thumbnail");
            }
            //如果当前上传的是内容BODY中的图片
            if ($request->request->get("bool_Content_Body_Img") == true){
                $fileSrcPath = $cacheManager->getBrowserPath($fileManagedEntity->getUri(), "product_mainpic_thumbnail");
            }
            //如果当前文件mime是image 并且是 字段上传的图片
            if ($request->request->get("bool_Field_Upload_File") == true && strpos($currentFileMime,"image") != -1){
                $fileSrcPath = $cacheManager->getBrowserPath($fileManagedEntity->getUri(), "fileManager_thumbnail");
            }
            $ajaxFileResult = array(
                "fileEntityId" => $fileManagedEntity->getId(),
                "fileSrc" => $fileSrcPath,
                "fileOriginSrc" => $fileOriginSrc,
                "fileName" => $fileManagedEntity->getFileName()
            );

            //把$fileManagedEntity以json串返回去。
            $json_response = new JsonResponse($ajaxFileResult);
            $json_response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            return $json_response;

        } else{
            return new Response("-1");
        }

    }

    /**
     * 删除上传的文件
     * @Route("/file_delete/{fileManaged_id}", name="file_delete", methods="GET|POST")
     * @ParamConverter("fileManagedEntity", class="App\Entity\FileManagedEntity", options={"id" = "fileManaged_id"})
     */
    public function deleteFile(FileManagedEntity $fileManagedEntity, EntityManagerInterface $em):Response
    {
        try{
            $fileManagedEntity->setDeleted(1);
            $em->persist($fileManagedEntity);
            //查找 fileManagedEntity 在 fieldFileTableEntity fieldImageTableEntity 中是否存在,如果存在也删除
            $fieldFileTableEntity = $fileManagedEntity->getFieldFileTableEntity();
            if($fieldFileTableEntity != null){
                $fieldFileTableEntity->setDeleted(1);
                $em->persist($fieldFileTableEntity);
            }
            $fieldImageTableEntity = $fileManagedEntity->getFieldImageTableEntity();
            if($fieldImageTableEntity != null){
                $fieldImageTableEntity->setDeleted(1);
                $em->persist($fieldImageTableEntity);
            }

            $em->flush();
        }
        catch (\Exception $e){
            return new Response("-1");
        }
        return new Response("0");
    }

}
