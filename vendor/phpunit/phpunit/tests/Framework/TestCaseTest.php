<?php
/**
 * PHPUnit
 *
 * Copyright (c) 2001-2014, Sebastian Bergmann <sebastian@phpunit.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    PHPUnit
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2001-2014 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.phpunit.de/
 * @since      File available since Release 2.0.0
 */

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'NoArgTestCaseTest.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'Singleton.php';

$GLOBALS['a']  = 'a';
$_ENV['b']     = 'b';
$_POST['c']    = 'c';
$_GET['d']     = 'd';
$_COOKIE['e']  = 'e';
$_SERVER['f']  = 'f';
$_FILES['g']   = 'g';
$_REQUEST['h'] = 'h';
$GLOBALS['i']  = 'i';

/**
 *
 *
 * @package    PHPUnit
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2001-2014 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 2.0.0
 * @covers     PHPUnit_Framework_TestCase
 */
class Framework_TestCaseTest extends PHPUnit_Framework_TestCase
{
    protected $backupGlobalsBlacklist = array('i', 'singleton');

    /**
     * Used be testStaticAttributesBackupPre
     */
    protected static $_testStatic = 0;

    public function testCaseToString()
    {
        $this->assertEquals(
            'Framework_TestCaseTest::testCaseToString',
            $this->toString()
        );
    }

    public function testSuccess()
    {
        $test   = new Success;
        $result = $test->run();

        $this->assertEquals(0, $result->errorCount());
        $this->assertEquals(0, $result->failureCount());
        $this->assertEquals(1, count($result));
    }

    public function testFailure()
    {
        $test   = new Failure;
        $result = $test->run();

        $this->assertEquals(0, $result->errorCount());
        $this->assertEquals(1, $result->failureCount());
        $this->assertEquals(1, count($result));
    }

    public function testError()
    {
        $test   = new Error;
        $result = $test->run();

        $this->assertEquals(1, $result->errorCount());
        $this->assertEquals(0, $result->failureCount());
        $this->assertEquals(1, count($result));
    }

    public function testExceptionInSetUp()
    {
        $test   = new ExceptionInSetUpTest('testSomething');
        $result = $test->run();

        $this->assertTrue($test->setUp);
        $this->assertFalse($test->assertPreConditions);
        $this->assertFalse($test->testSomething);
        $this->assertFalse($test->assertPostConditions);
        $this->assertTrue($test->tearDown);
    }

    public function testExceptionInAssertPreConditions()
    {
        $test   = new ExceptionInAssertPreConditionsTest('testSomething');
        $result = $test->run();

        $this->assertTrue($test->setUp);
        $this->assertTrue($test->assertPreConditions);
        $this->assertFalse($test->testSomething);
        $this->assertFalse($test->assertPostConditions);
        $this->assertTrue($test->tearDown);
    }

    public function testExceptionInTest()
    {
        $test   = new ExceptionInTest('testSomething');
        $result = $test->run();

        $this->assertTrue($test->setUp);
        $this->assertTrue($test->assertPreConditions);
        $this->assertTrue($test->testSomething);
        $this->assertFalse($test->assertPostConditions);
        $this->assertTrue($test->tearDown);
    }

    public function testExceptionInAssertPostConditions()
    {
        $test   = new ExceptionInAssertPostConditionsTest('testSomething');
        $result = $test->run();

        $this->assertTrue($test->setUp);
        $this->assertTrue($test->assertPreConditions);
        $this->assertTrue($test->testSomething);
        $this->assertTrue($test->assertPostConditions);
        $this->assertTrue($test->tearDown);
    }

    public function testExceptionInTearDown()
    {
        $test   = new ExceptionInTearDownTest('testSomething');
        $result = $test->run();

        $this->assertTrue($test->setUp);
        $this->assertTrue($test->assertPreConditions);
        $this->assertTrue($test->testSomething);
        $this->assertTrue($test->assertPostConditions);
        $this->assertTrue($test->tearDown);
    }

    public function testNoArgTestCasePasses()
    {
        $result = new PHPUnit_Framework_TestResult;
        $t      = new PHPUnit_Framework_TestSuite('NoArgTestCaseTest');

        $t->run($result);

        $this->assertEquals(1, count($result));
        $this->assertEquals(0, $result->failureCount());
        $this->assertEquals(0, $result->errorCount());
    }

    public function testWasRun()
    {
        $test = new WasRun;
        $test->run();

        $this->assertTrue($test->wasRun);
    }

    public function testException()
    {
        $test = new ThrowExceptionTestCase('tests');
        $test->setExpectedException('RuntimeException');

        $result = $test->run();

        $this->assertEquals(1, count($result));
        $this->assertTrue($result->wasSuccessful());
    }

    public function testExceptionWithMessage()
    {
        $test = new ThrowExceptionTestCase('tests');
        $test->setExpectedException('RuntimeException', 'A runtime error occurred');

        $result = $test->run();

        $this->assertEquals(1, count($result));
        $this->assertTrue($result->wasSuccessful());
    }

    public function testExceptionWithWrongMessage()
    {
        $test = new ThrowExceptionTestCase('tests');
        $test->setExpectedException('RuntimeException', 'A logic error occurred');

        $result = $test->run();

        $this->assertEquals(1, $result->failureCount());
        $this->assertEquals(1, count($result));
        $this->assertEquals(
            "Failed asserting that exception message 'A runtime error occurred' contains 'A logic error occurred'.",
            $test->getStatusMessage()
        );
    }

    public function testExceptionWithRegexpMessage()
    {
        $test = new ThrowExceptionTestCase('tests');
        $test->setExpectedExceptionRegExp('RuntimeException', '/runtime .*? occurred/');

        $result = $test->run();

        $this->assertEquals(1, count($result));
        $this->assertTrue($result->wasSuccessful());
    }

    public function testExceptionWithWrongRegexpMessage()
    {
        $test = new ThrowExceptionTestCase('tests');
        $test->setExpectedExceptionRegExp('RuntimeException', '/logic .*? occurred/');

        $result = $test->run();

        $this->assertEquals(1, $result->failureCount());
        $this->assertEquals(1, count($result));
        $this->assertEquals(
            "Failed asserting that exception message 'A runtime error occurred' matches '/logic .*? occurred/'.",
            $test->getStatusMessage()
        );
    }

    /**
     * @covers PHPUnit_Framework_Constraint_ExceptionMessageRegExp
     */
    public function testExceptionWithInvalidRegexpMessage()
    {
        $test = new ThrowExceptionTestCase('tests');
        $test->setExpectedExceptionRegExp('RuntimeException', '#runtime .*? occurred/'); // wrong delimiter

        $result = $test->run();

        $this->assertEquals(
            "Invalid expected exception message regex given: '#runtime .*? occurred/'",
            $test->getStatusMessage()
        );
    }

    public function testNoException()
    {
        $test = new ThrowNoExceptionTestCase('tests');
        $test->setExpectedException('RuntimeException');

        $result = $test->run();

        $this->assertEquals(1, $result->failureCount());
        $this->assertEquals(1, count($result));
    }

    public function testWrongException()
    {
        $test = new ThrowExceptionTestCase('tests');
        $test->setExpectedException('InvalidArgumentException');

        $result = $test->run();

        $this->assertEquals(1, $result->failureCount());
        $this->assertEquals(1, count($result));
    }

    /**
     * @backupGlobals enabled
     */
    public function testGlobalsBackupPre()
    {
        global $a;
        global $i;

        $this->assertEquals('a', $a);
        $this->assertEquals('a', $GLOBALS['a']);
        $this->assertEquals('b', $_ENV['b']);
        $this->assertEquals('c', $_POST['c']);
        $this->assertEquals('d', $_GET['d']);
        $this->assertEquals('e', $_COOKIE['e']);
        $this->assertEquals('f', $_SERVER['f']);
        $this->assertEquals('g', $_FILES['g']);
        $this->assertEquals('h', $_REQUEST['h']);
        $this->assertEquals('i', $i);
        $this->assertEquals('i', $GLOBALS['i']);

        $GLOBALS['a']   = 'aa';
        $GLOBALS['foo'] = 'bar';
        $_ENV['b']      = 'bb';
        $_POST['c']     = 'cc';
        $_GET['d']      = 'dd';
        $_COOKIE['e']   = 'ee';
        $_SERVER['f']   = 'ff';
        $_FILES['g']    = 'gg';
        $_REQUEST['h']  = 'hh';
        $GLOBALS['i']   = 'ii';

        $this->assertEquals('aa', $a);
        $this->assertEquals('aa', $GLOBALS['a']);
        $this->assertEquals('bar', $GLOBALS['foo']);
        $this->assertEquals('bb', $_ENV['b']);
        $this->assertEquals('cc', $_POST['c']);
        $this->assertEquals('dd', $_GET['d']);
        $this->assertEquals('ee', $_COOKIE['e']);
        $this->assertEquals('ff', $_SERVER['f']);
        $this->assertEquals('gg', $_FILES['g']);
        $this->assertEquals('hh', $_REQUEST['h']);
        $this->assertEquals('ii', $i);
        $this->assertEquals('ii', $GLOBALS['i']);
    }

    public function testGlobalsBackupPost()
    {
        global $a;
        global $i;

        $this->assertEquals('a', $a);
        $this->assertEquals('a', $GLOBALS['a']);
        $this->assertEquals('b', $_ENV['b']);
        $this->assertEquals('c', $_POST['c']);
        $this->assertEquals('d', $_GET['d']);
        $this->assertEquals('e', $_COOKIE['e']);
        $this->assertEquals('f', $_SERVER['f']);
        $this->assertEquals('g', $_FILES['g']);
        $this->assertEquals('h', $_REQUEST['h']);
        $this->assertEquals('ii', $i);
        $this->assertEquals('ii', $GLOBALS['i']);

        $this->assertArrayNotHasKey('foo', $GLOBALS);
    }

    /**
     * @backupGlobals enabled
     * @backupStaticAttributes enabled
     */
    public function testStaticAttributesBackupPre()
    {
        $GLOBALS['singleton'] = Singleton::getInstance();
        self::$_testStatic = 123;
    }

    /**
     * @depends testStaticAttributesBackupPre
     */
    public function testStaticAttributesBackupPost()
    {
        $this->assertNotSame($GLOBALS['singleton'], Singleton::getInstance());
        $this->assertSame(123, self::$_testStatic);
    }

    public function testIsInIsolationReturnsFalse()
    {
        $test   = new IsolationTest('testIsInIsolationReturnsFalse');
        $result = $test->run();

        $this->assertEquals(1, count($result));
        $this->assertTrue($result->wasSuccessful());
    }

    public function testIsInIsolationReturnsTrue()
    {
        $test   = new IsolationTest('testIsInIsolationReturnsTrue');
        $test->setRunTestInSeparateProcess(true);
        $result = $test->run();

        $this->assertEquals(1, count($result));
        $this->assertTrue($result->wasSuccessful());
    }

    public function testExpectOutputStringFooActualFoo()
    {
        $test   = new OutputTestCase('testExpectOutputStringFooActualFoo');
        $result = $test->run();

        $this->assertEquals(1, count($result));
        $this->assertTrue($result->wasSuccessful());
    }

    public function testExpectOutputStringFooActualBar()
    {
        $test   = new OutputTestCase('testExpectOutputStringFooActualBar');
        $result = $test->run();

        $this->assertEquals(1, count($result));
        $this->assertFalse($result->wasSuccessful());
    }

    public function testExpectOutputRegexFooActualFoo()
    {
        $test   = new OutputTestCase('testExpectOutputRegexFooActualFoo');
        $result = $test->run();

        $this->assertEquals(1, count($result));
        $this->assertTrue($result->wasSuccessful());
    }

    public function testExpectOutputRegexFooActualBar()
    {
        $test   = new OutputTestCase('testExpectOutputRegexFooActualBar');
        $result = $test->run();

        $this->assertEquals(1, count($result));
        $this->assertFalse($result->wasSuccessful());
    }

    public function testSkipsIfRequiresHigherVersionOfPHPUnit()
    {
        $test   = new RequirementsTest('testAlwaysSkip');
        $result = $test->run();

        $this->assertEquals(1, $result->skippedCount());
        $this->assertEquals(
            'PHPUnit 1111111 (or later) is required.',
            $test->getStatusMessage()
        );
    }

    public function testSkipsIfRequiresHigherVersionOfPHP()
    {
        $test   = new RequirementsTest('testAlwaysSkip2');
        $result = $test->run();

        $this->assertEquals(1, $result->skippedCount());
        $this->assertEquals(
            'PHP 9999999 (or later) is required.',
            $test->getStatusMessage()
        );
    }

    public function testSkipsIfRequiresNonExistingOs()
    {
        $test   = new RequirementsTest('testAlwaysSkip3');
        $result = $test->run();

        $this->assertEquals(1, $result->skippedCount());
        $this->assertEquals(
            'Operating system matching /DOESNOTEXIST/i is required.',
            $test->getStatusMessage()
        );
    }

    public function testSkipsIfRequiresNonExistingFunction()
    {
        $test   = new RequirementsTest('testNine');
        $result = $test->run();

        $this->assertEquals(1, $result->skippedCount());
        $this->assertEquals(
            'Function testFunc is required.',
            $test->getStatusMessage()
        );
    }

    public function testSkipsIfRequiresNonExistingExtension()
    {
        $test   = new RequirementsTest('testTen');
        $result = $test->run();

        $this->assertEquals(
            'Extension testExt is required.',
            $test->getStatusMessage()
        );
    }

    public function testSkipsProvidesMessagesForAllSkippingReasons()
    {
        $test   = new RequirementsTest('testAllPossibleRequirements');
        $result = $test->run();

        $this->assertEquals(
            'PHP 99-dev (or later) is required.' . PHP_EOL .
            'PHPUnit 9-dev (or later) is required.' . PHP_EOL .
            'Operating system matching /DOESNOTEXIST/i is required.' . PHP_EOL .
            'Function testFuncOne is required.' . PHP_EOL .
            'Function testFuncTwo is required.' . PHP_EOL .
            'Extension testExtOne is required.' . PHP_EOL .
            'Extension testExtTwo is required.',
            $test->getStatusMessage()
        );
    }

    public function testRequiringAnExistingMethodDoesNotSkip()
    {
        $test   = new RequirementsTest('testExistingMethod');
        $result = $test->run();
        $this->assertEquals(0, $result->skippedCount());
    }

    public function testRequiringAnExistingFunctionDoesNotSkip()
    {
        $test   = new RequirementsTest('testExistingFunction');
        $result = $test->run();
        $this->assertEquals(0, $result->skippedCount());
    }

    public function testRequiringAnExistingExtensionDoesNotSkip()
    {
        $test   = new RequirementsTest('testExistingExtension');
        $result = $test->run();
        $this->assertEquals(0, $result->skippedCount());
    }

    public function testRequiringAnExistingOsDoesNotSkip()
    {
        $test   = new RequirementsTest('testExistingOs');
        $result = $test->run();
        $this->assertEquals(0, $result->skippedCount());
    }

    public function testCurrentWorkingDirectoryIsRestored()
    {
        $expectedCwd = getcwd();

        $test = new ChangeCurrentWorkingDirectoryTest('testSomethingThatChangesTheCwd');
        $test->run();

        $this->assertSame($expectedCwd, getcwd());
    }
}
