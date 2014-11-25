<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 23/11/2014
 * Time: 22:35
 */

namespace Web1\Validator;

class ValidatorInt
{
    public static function intEqual ($a,$b)
    {
        return $a == $b;
    }
    public static function intInf ($a,$b)
    {
        return $a < $b;
    }
    public static function intSup ($a,$b)
    {
        return $a > $b;
    }
    public static function intBetween ($number,$value1,$value2)
    {
        if ($number >= $value1 && $number <= $value2){
            return true;
        } else{
            return false;
        }
    }
    public static function intNegative ($negative)
    {
        if ($negative <= 0){
            return true;
        } else{
            return false;
        }
    }
    public static function intPositive ($positive)
    {
        if ($positive >= 0){
            return true;
        } else{
            return false;
        }
    }
} 