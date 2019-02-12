<?php
/**
 * Created by PhpStorm.
 * User: quan
 * Date: 18-9-11
 * Time: 下午12:27
 */

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class JsonDecodeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('json_decode', array($this, 'jsonDecodeFilter')),
            new TwigFilter('json_decode_str', array($this, 'jsonDecodeFilterStr')),
        );
    }

    public function jsonDecodeFilter($string)
    {
        return json_decode($string,true);
    }

    public function jsonDecodeFilterStr($string)
    {
        return json_decode($string);
    }
}