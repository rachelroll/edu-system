<?php
namespace App\Utils;
class Utils
{
    // 截取文章的一部分内容
    public static function cutArticle($data, $str="....")
    {
        $data=strip_tags($data);//去除html标记
        $pattern = "/&[a-zA-Z]+;/";//去除特殊符号
        $data=preg_replace($pattern,'',$data);

        // 设置只加载三分之一的内容
        $cut = strlen($data) * 3 / 10;

        $data = mb_strimwidth($data,0, $cut, $str);

        return $data;
    }

}