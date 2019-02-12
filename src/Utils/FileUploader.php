<?php
/**
 * 文件上传Service
 * User: weiwei
 * Date: 2018/8/7
 * Time: 10:29
 */

namespace App\Utils;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{

    private $targetDir;
    private $fileDir;

    public function __construct($targetDir)
    {
        $this->fileDir = '/'.date("Y-m-d");
        $this->targetDir = $targetDir.$this->fileDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileSize = $file->getSize();
        //防止文件名重复
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        //把文件存入文件路径
        if (!file_exists($this->targetDir)){
            mkdir($this->targetDir);
        }
        $file->move($this->targetDir, $fileName);

        return array("fileName"=> $fileName,
            "fileDir" => $this->fileDir,
            "fileSize" => $fileSize,
            "targetDir" => $this->targetDir
            );
    }


    public function delete(string $filePath)
    {
        $absFilePath = $this->targetDir."/".$filePath;
        unlink($absFilePath);
    }

}