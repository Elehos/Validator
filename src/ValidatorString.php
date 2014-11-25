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
        if ($value == mb_strlen($string)){
            return true;
        } else{
            return false;
        }
    }
    public static function stringLenInf ($value,$string)
    {
        if ($value > mb_strlen($string)) {
            return true;
        } else {
            return false;
        }
    }
    public static function stringLenSup ($value,$string)
    {
        if ($value < mb_strlen($string)){
            return true;
        } else{
            return false;
        }
    }
    public static function stringLenBetween ($string, $value1,$value2)
    {
        if (mb_strlen($string) >= $value1 && mb_strlen($string) <= $value2){
            return true;
        } else{
            return false;
        }
    }
    public static function stringNoSpaceStartEnd ($string)
    {
        if (strrpos($string, ' ', 0) == false && strrpos($string, ' ', mb_strlen($string)+1) == false){
            return true;
        } else {
            return false;
        }
    }
}
