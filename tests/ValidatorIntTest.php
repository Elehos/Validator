<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 25/11/2014
 * Time: 10:40
 */

namespace Tests\Web1\Validator;

use Web1\Validator\ValidatorInt;

class ValidatorIntTest extends \PHPUnit_Framework_TestCase
{

    public function testIntEqual()
    {
        $a = mt_rand(1, 100);
        $test = ValidatorInt::intEqual(is_int($a),is_int($a));
        $this->assertTrue($test);
    }

    public function testIntInf()
    {
        $a = mt_rand(1, 49);
        $b = mt_rand(50, 100);
        $test = ValidatorInt::intInf(is_int($a),is_int($b));
        $this->assertTrue($test);
    }
    public function testIntSup()
    {
        $a = mt_rand(50, 100);
        $b = mt_rand(1, 49);
        $test = ValidatorInt::intSup(is_int($a),is_int($b));
        $this->assertTrue($test);
    }
    public function testIntBetween()
    {
        $number = mt_rand(20, 50);
        $value1 = mt_rand(10, 19);
        $value2 = mt_rand(51, 80);
        $test = ValidatorInt::intBetween($number,$value1,$value2);
        $this->assertTrue($test);
    }
    public function testNegative()
    {
        $negative = mt_rand(-15,0);
        $test = ValidatorInt::intNegative($negative);
        $this->assertTrue($test);
    }
    public function testPostive()
    {
        $positive = mt_rand(0,15);
        $test = ValidatorInt::intPositive($positive);
        $this->assertTrue($test);
    }
} 