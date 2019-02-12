<?php
/**
 * Created by PhpStorm.
 * User: weiwei
 * Date: 2018/8/7
 * Time: 08:18
 */
namespace App\Utils;

use Symfony\Component\Yaml\Yaml;

class YamlReader
{

    //获取一个文件的mime类型，参数为：yaml文件
    public static function getFileMime(string $yamlPath){

        try {
            $value = Yaml::parse(file_get_contents( $yamlPath ));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }
        return $value["filemime"];

    }
}