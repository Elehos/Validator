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
 * @subpackage TextUI
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2001-2014 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.phpunit.de/
 * @since      File available since Release 2.0.0
 */

/**
 * Prints the result of a TextUI TestRunner run.
 *
 * @package    PHPUnit
 * @subpackage TextUI
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2001-2014 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 2.0.0
 */
class PHPUnit_TextUI_ResultPrinter extends PHPUnit_Util_Printer implements PHPUnit_Framework_TestListener
{
    const EVENT_TEST_START      = 0;
    const EVENT_TEST_END        = 1;
    const EVENT_TESTSUITE_START = 2;
    const EVENT_TESTSUITE_END   = 3;

    /**
     * @var array
     */
    private static $ansiCodes = array(
      'bold'       => 1,
      'fg-black'   => 30,
      'fg-red'     => 31,
      'fg-yellow'  => 33,
      'fg-cyan'    => 36,
      'fg-white'   => 37,
      'bg-red'     => 41,
      'bg-green'   => 42,
      'bg-yellow'  => 43
    );

    /**
     * @var integer
     */
    protected $column = 0;

    /**
     * @var integer
     */
    protected $maxColumn;

    /**
     * @var boolean
     */
    protected $lastTestFailed = false;

    /**
     * @var integer
     */
    protected $numAssertions = 0;

    /**
     * @var integer
     */
    protected $numTests = -1;

    /**
     * @var integer
     */
    protected $numTestsRun = 0;

    /**
     * @var integer
     */
    protected $numTestsWidth;

    /**
     * @var boolean
     */
    protected $colors = false;

    /**
     * @var boolean
     */
    protected $debug = false;

    /**
     * @var boolean
     */
    protected $verbose = false;

    /**
     * Constructor.
     *
     * @param  mixed                       $out
     * @param  boolean                     $verbose
     * @param  boolean                     $colors
     * @param  boolean                     $debug
     * @throws PHPUnit_Framework_Exception
     * @since  Method available since Release 3.0.0
     */
    public function __construct($out = null, $verbose = false, $colors = false, $debug = false)
    {
        parent::__construct($out);

        if (is_bool($verbose)) {
            $this->verbose = $verbose;
        } else {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'boolean');
        }

        if (is_bool($colors)) {
            $this->colors = $colors;
        } else {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(3, 'boolean');
        }

        if (is_bool($debug)) {
            $this->debug = $debug;
        } else {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(4, 'boolean');
        }
    }

    /**
     * @param PHPUnit_Framework_TestResult $result
     */
    public function printResult(PHPUnit_Framework_TestResult $result)
    {
        $this->printHeader();

        $this->printErrors($result);
        $printSeparator = $result->errorCount() > 0;

        if ($printSeparator && $result->failureCount() > 0) {
            $this->write("\n--\n\n");
        }

        $printSeparator = $printSeparator || $result->failureCount() > 0;
        $this->printFailures($result);

        if ($this->verbose) {
            if ($printSeparator && $result->riskyCount() > 0) {
                $this->write("\n--\n\n");
            }

            $printSeparator = $printSeparator ||
                              $result->riskyCount() > 0;

            $this->printRisky($result);

            if ($printSeparator && $result->notImplementedCount() > 0) {
                $this->write("\n--\n\n");
            }

            $printSeparator = $printSeparator ||
                              $result->notImplementedCount() > 0;

            $this->printIncompletes($result);

            if ($printSeparator && $result->skippedCount() > 0) {
                $this->write("\n--\n\n");
            }

            $this->printSkipped($result);
        }

        $this->printFooter($result);
    }

    /**
     * @param array  $defects
     * @param string $type
     */
    protected function printDefects(array $defects, $type)
    {
        $count = count($defects);

        if ($count == 0) {
            return;
        }

        $this->write(
            sprintf(
                "There %s %d %s%s:\n",
                ($count == 1) ? 'was' : 'were',
                $count,
                $type,
                ($count == 1) ? '' : 's'
            )
        );

        $i = 1;

        foreach ($defects as $defect) {
            $this->printDefect($defect, $i++);
        }
    }

    /**
     * @param PHPUnit_Framework_TestFailure $defect
     * @param integer                       $count
     */
    protected function printDefect(PHPUnit_Framework_TestFailure $defect, $count)
    {
        $this->printDefectHeader($defect, $count);
        $this->printDefectTrace($defect);
    }

    /**
     * @param PHPUnit_Framework_TestFailure $defect
     * @param integer                       $count
     */
    protected function printDefectHeader(PHPUnit_Framework_TestFailure $defect, $count)
    {
        $this->write(
            sprintf(
                "\n%d) %s\n",
                $count,
                $defect->getTestName()
            )
        );
    }

    /**
     * @param PHPUnit_Framework_TestFailure $defect
     */
    protected function printDefectTrace(PHPUnit_Framework_TestFailure $defect)
    {
        $e = $defect->thrownException();
        $this->write((string) $e);

        while ($e = $e->getPrevious()) {
            $this->write("\nCaused by\n" . $e);
        }
    }

    /**
     * @param PHPUnit_Framework_TestResult $result
     */
    protected function printErrors(PHPUnit_Framework_TestResult $result)
    {
        $this->printDefects($result->errors(), 'error');
    }

    /**
     * @param PHPUnit_Framework_TestResult $result
     */
    protected function printFailures(PHPUnit_Framework_TestResult $result)
    {
        $this->printDefects($result->failures(), 'failure');
    }

    /**
     * @param PHPUnit_Framework_TestResult $result
     */
    protected function printIncompletes(PHPUnit_Framework_TestResult $result)
    {
        $this->printDefects($result->notImplemented(), 'incomplete tests');
    }

    /**
     * @param PHPUnit_Framework_TestResult $result
     * @since  Method available since Release 4.0.0
     */
    protected function printRisky(PHPUnit_Framework_TestResult $result)
    {
        $this->printDefects($result->risky(), 'risky tests');
    }

    /**
     * @param PHPUnit_Framework_TestResult $result
     * @since  Method available since Release 3.0.0
     */
    protected function printSkipped(PHPUnit_Framework_TestResult $result)
    {
        $this->printDefects($result->skipped(), 'skipped tests');
    }

    protected function printHeader()
    {
        $this->write("\n\n" . PHP_Timer::resourceUsage() . "\n\n");
    }

    /**
     * @param PHPUnit_Framework_TestResult $result
     */
    protected function printFooter(PHPUnit_Framework_TestResult $result)
    {
        if (count($result) === 0) {
            $this->writeWithColor(
                'fg-black, bg-yellow',
                'No tests executed!'
            );
        } elseif ($result->wasSuccessful() &&
                 $result->allHarmless() &&
                 $result->allCompletelyImplemented() &&
                 $result->noneSkipped()) {
            $this->writeWithColor(
                'fg-black, bg-green',
                sprintf(
                    'OK (%d tests%s, %d assertion%s)',
                    count($result),
                    (count($result) == 1) ? '' : 's',
                    $this->numAssertions,
                    ($this->numAssertions == 1) ? '' : 's'
                )
            );
        } elseif ((!$result->allCompletelyImplemented() ||
                  !$result->allHarmless() ||
                  !$result->noneSkipped()) &&
                 $result->wasSuccessful()) {
            $this->writeWithColor(
                'fg-black, bg-yellow',
                sprintf(
                    "%sOK, but incomplete, skipped, or risky tests!\n" .
                    'Tests: %d, Assertions: %d%s%s%s.',
                    $this->verbose ? "\n" : '',
                    count($result),
                    $this->numAssertions,
                    $this->getCountString(
                        $result->notImplementedCount(), 'Incomplete'
                    ),
                    $this->getCountString(
                        $result->skippedCount(), 'Skipped'
                    ),
                    $this->getCountString(
                        $result->riskyCount(), 'Risky'
                    )
                )
            );
        } else {
            $this->writeWithColor(
                'fg-white, bg-red',
                sprintf(
                    "\nFAILURES!\n" .
                    'Tests: %d, Assertions: %s%s%s%s%s.',
                    count($result),
                    $this->numAssertions,
                    $this->getCountString($result->failureCount(), 'Failures'),
                    $this->getCountString($result->errorCount(), 'Errors'),
                    $this->getCountString(
                        $result->notImplementedCount(), 'Incomplete'
                    ),
                    $this->getCountString($result->skippedCount(), 'Skipped')
                )
            );
        }
    }

    /**
     * @param  integer $count
     * @param  string  $name
     * @return string
     * @since  Method available since Release 3.0.0
     */
    protected function getCountString($count, $name)
    {
        $string = '';

        if ($count > 0) {
            $string = sprintf(
                ', %s: %d',
                $name,
                $count
            );
        }

        return $string;
    }

    /**
     */
    public function printWaitPrompt()
    {
        $this->write("\n<RETURN> to continue\n");
    }

    /**
     * An error occurred.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->writeProgressWithColor('fg-red, bold', 'E');
        $this->lastTestFailed = true;
    }

    /**
     * A failure occurred.
     *
     * @param PHPUnit_Framework_Test                 $test
     * @param PHPUnit_Framework_AssertionFailedError $e
     * @param float                                  $time
     */
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        $this->writeProgressWithColor('bg-red, fg-white', 'F');
        $this->lastTestFailed = true;
    }

    /**
     * Incomplete tests.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->writeProgressWithColor('fg-yellow, bold', 'I');
        $this->lastTestFailed = true;
    }

    /**
     * Risky tests.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     * @since  Method available since Release 4.0.0
     */
    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->writeProgressWithColor('fg-yellow, bold', 'R');
        $this->lastTestFailed = true;
    }

    /**
     * Skipped tests.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     * @since  Method available since Release 3.0.0
     */
    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->writeProgressWithColor('fg-cyan, bold', 'S');
        $this->lastTestFailed = true;
    }

    /**
     * A testsuite started.
     *
     * @param PHPUnit_Framework_TestSuite $suite
     * @since  Method available since Release 2.2.0
     */
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        if ($this->numTests == -1) {
            $this->numTests      = count($suite);
            $this->numTestsWidth = strlen((string) $this->numTests);
            $this->maxColumn     = 69 - (2 * $this->numTestsWidth);
        }
    }

    /**
     * A testsuite ended.
     *
     * @param PHPUnit_Framework_TestSuite $suite
     * @since  Method available since Release 2.2.0
     */
    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
    }

    /**
     * A tests started.
     *
     * @param PHPUnit_Framework_Test $test
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        if ($this->debug) {
            $this->write(
                sprintf(
                    "\nStarting tests '%s'.\n", PHPUnit_Util_Test::describe($test)
                )
            );
        }
    }

    /**
     * A tests ended.
     *
     * @param PHPUnit_Framework_Test $test
     * @param float                  $time
     */
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        if (!$this->lastTestFailed) {
            $this->writeProgress('.');
        }

        if ($test instanceof PHPUnit_Framework_TestCase) {
            $this->numAssertions += $test->getNumAssertions();
        } elseif ($test instanceof PHPUnit_Extensions_PhptTestCase) {
            $this->numAssertions++;
        }

        $this->lastTestFailed = false;

        if ($test instanceof PHPUnit_Framework_TestCase) {
            if (!$test->hasExpectationOnOutput()) {
                $this->write($test->getActualOutput());
            }
        }
    }

    /**
     * @param string $progress
     */
    protected function writeProgress($progress)
    {
        $this->write($progress);
        $this->column++;
        $this->numTestsRun++;

        if ($this->column == $this->maxColumn) {
            $this->write(
                sprintf(
                    ' %' . $this->numTestsWidth . 'd / %' .
                    $this->numTestsWidth . 'd (%3s%%)',
                    $this->numTestsRun,
                    $this->numTests,
                    floor(($this->numTestsRun / $this->numTests) * 100)
                )
            );

            $this->writeNewLine();
        }
    }

    protected function writeNewLine()
    {
        $this->column = 0;
        $this->write("\n");
    }

    /**
     * Formats a buffer with a specified ANSI color sequence if colors are
     * enabled.
     *
     * @param  string $color
     * @param  string $buffer
     * @return string
     * @since  Method available since Release 4.0.0
     */
    protected function formatWithColor($color, $buffer)
    {
        if (!$this->colors) {
            return $buffer;
        }

        $codes = array_map('trim', explode(',', $color));
        $lines = explode("\n", $buffer);
        $padding = max(array_map('strlen', $lines));

        $styles = array();
        foreach ($codes as $code) {
            $styles[] = self::$ansiCodes[$code];
        }
        $style = sprintf("\x1b[%sm", implode(';', $styles));

        $styledLines = array();
        foreach ($lines as $line) {
            $styledLines[] = $style . str_pad($line, $padding) . "\x1b[0m";
        }

        return implode("\n", $styledLines);
    }

    /**
     * Writes a buffer out with a color sequence if colors are enabled.
     *
     * @param string $color
     * @param string $buffer
     * @since  Method available since Release 4.0.0
     */
    protected function writeWithColor($color, $buffer)
    {
        $buffer = $this->formatWithColor($color, $buffer);
        $this->write($buffer . "\n");
    }

    /**
     * Writes progress with a color sequence if colors are enabled.
     *
     * @param string $color
     * @param string $buffer
     * @since  Method available since Release 4.0.0
     */
    protected function writeProgressWithColor($color, $buffer)
    {
        $buffer = $this->formatWithColor($color, $buffer);
        $this->writeProgress($buffer);
    }
}
