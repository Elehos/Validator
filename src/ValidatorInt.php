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
    /**
     * @param $a
     * @param $b
     * @return bool
     */
    public static function intEqual ($a,$b)
    {
        return $a == $b;
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    public static function intInf ($a,$b)
    {
        return $a < $b;
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    public static function intSup ($a,$b)
    {
        return $a > $b;
    }

    /**
     * @param $number
     * @param $value1
     * @param $value2
     * @return bool
     */
    public static function intBetween ($number,$value1,$value2)
    {
       return $number >= $value1 && $number <= $value2;
    }

    /**
     * @param $negative
     * @return bool
     */
    public static function intNegative ($negative)
    {
        return $negative <= 0;
    }

    /**
     * @param $positive
     * @return bool
     */
    public static function intPositive ($positive)
    {
        return $positive >= 0;
    }
}
