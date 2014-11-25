<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 23/11/2014
 * Time: 23:34
 */

namespace Web1\Validator;

class ValidatorString
{
    public static function stringLenEqual ($value,$string)
    {
        return $value == mb_strlen($string);
    }
    public static function stringLenInf ($value,$string)
    {
        return $value > mb_strlen($string);
    }
    public static function stringLenSup ($value,$string)
    {
        return $value < mb_strlen($string);
    }
    public static function stringLenBetween ($string, $value1,$value2)
    {
        return mb_strlen($string) >= $value1 && mb_strlen($string) <= $value2;
    }
    public static function stringNoSpaceStartEnd ($string)
    {
        return strrpos($string, ' ', 0) === false && strrpos($string, ' ', mb_strlen($string) + 1) == false;
    }
}
