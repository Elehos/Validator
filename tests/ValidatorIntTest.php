<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 25/11/2014
 * Time: 10:40
 */

namespace tests\Web1\Validator;

use Web1\Validator\ValidatorInt;

class ValidatorIntTest extends \PHPUnit_Framework_TestCase
{
    public function testIntEqual()
    {
        $a = mt_rand(1, 100);
        $test = ValidatorInt::intEqual($a,$a);
        $this->assertTrue($test);
    }

    public function testIntInf($a,$b)
    {
        $test = ValidatorInt::intInf($a,$b);
        $this->assertTrue($test);

    }
} 