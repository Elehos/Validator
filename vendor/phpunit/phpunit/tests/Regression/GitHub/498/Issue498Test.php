<?php

class Issue498Test extends PHPUnit_Framework_TestCase
{

    /**
     * @tests
     * @dataProvider shouldBeTrueDataProvider
     * @group falseOnly
     */
    public function shouldBeTrue($testData)
    {
        $this->assertTrue(true);
    }


    /**
     * @tests
     * @dataProvider shouldBeFalseDataProvider
     * @group trueOnly
     */
    public function shouldBeFalse($testData)
    {
        $this->assertFalse(false);
    }

    public function shouldBeTrueDataProvider()
    {

        //throw new Exception("Can't create the data");
        return array(
            array(true),
            array(false)
        );
    }

    public function shouldBeFalseDataProvider()
    {

        throw new Exception("Can't create the data");
        return array(
            array(true),
            array(false)
        );
    }
}
