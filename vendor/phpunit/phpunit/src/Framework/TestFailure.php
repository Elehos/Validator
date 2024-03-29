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
 * @subpackage Framework
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2001-2014 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.phpunit.de/
 * @since      File available since Release 2.0.0
 */

/**
 * A TestFailure collects a failed tests together with the caught exception.
 *
 * @package    PHPUnit
 * @subpackage Framework
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2001-2014 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 2.0.0
 */
class PHPUnit_Framework_TestFailure
{
    /**
     * @var string
     */
    private $testName;

    /**
     * @var PHPUnit_Framework_Test|null
     */
    protected $failedTest;

    /**
     * @var    Exception
     */
    protected $thrownException;

    /**
     * Constructs a TestFailure with the given tests and exception.
     *
     * @param PHPUnit_Framework_Test $failedTest
     * @param Exception              $thrownException
     */
    public function __construct(PHPUnit_Framework_Test $failedTest, Exception $thrownException)
    {
        if ($failedTest instanceof PHPUnit_Framework_SelfDescribing) {
            $this->testName = $failedTest->toString();
        } else {
            $this->testName = get_class($failedTest);
        }
        if (!$failedTest instanceof PHPUnit_Framework_TestCase || !$failedTest->isInIsolation()) {
            $this->failedTest = $failedTest;
        }
        $this->thrownException = $thrownException;
    }

    /**
     * Returns a short description of the failure.
     *
     * @return string
     */
    public function toString()
    {
        return sprintf(
            '%s: %s',
            $this->testName,
            $this->thrownException->getMessage()
        );
    }

    /**
     * Returns a description for the thrown exception.
     *
     * @return string
     * @since  Method available since Release 3.4.0
     */
    public function getExceptionAsString()
    {
        return self::exceptionToString($this->thrownException);
    }

    /**
     * Returns a description for an exception.
     *
     * @param  Exception $e
     * @return string
     * @since  Method available since Release 3.2.0
     */
    public static function exceptionToString(Exception $e)
    {
        if ($e instanceof PHPUnit_Framework_SelfDescribing) {
            $buffer = $e->toString();

            if ($e instanceof PHPUnit_Framework_ExpectationFailedException && $e->getComparisonFailure()) {
                $buffer = $buffer . $e->getComparisonFailure()->getDiff();
            }

            if (!empty($buffer)) {
                $buffer = trim($buffer) . "\n";
            }
        } elseif ($e instanceof PHPUnit_Framework_Error) {
            $buffer = $e->getMessage() . "\n";
        } elseif ($e instanceof PHPUnit_Framework_ExceptionWrapper) {
            $buffer = $e->getClassname() . ': ' . $e->getMessage() . "\n";
        } else {
            $buffer = get_class($e) . ': ' . $e->getMessage() . "\n";
        }

        return $buffer;
    }

    /**
     * Returns the name of the failing tests (including data set, if any).
     *
     * @return string
     * @since  Method available since Release 4.3.0
     */
    public function getTestName()
    {
        return $this->testName;
    }

    /**
     * Returns the failing tests.
     *
     * Note: The tests object is not set when the tests is executed in process
     * isolation.
     *
     * @see PHPUnit_Framework_Exception
     *
     * @return PHPUnit_Framework_Test|null
     */
    public function failedTest()
    {
        return $this->failedTest;
    }

    /**
     * Gets the thrown exception.
     *
     * @return Exception
     */
    public function thrownException()
    {
        return $this->thrownException;
    }

    /**
     * Returns the exception's message.
     *
     * @return string
     */
    public function exceptionMessage()
    {
        return $this->thrownException()->getMessage();
    }

    /**
     * Returns true if the thrown exception
     * is of type AssertionFailedError.
     *
     * @return boolean
     */
    public function isFailure()
    {
        return ($this->thrownException() instanceof PHPUnit_Framework_AssertionFailedError);
    }
}
