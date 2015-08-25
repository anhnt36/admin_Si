<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Process\Tests;

use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * @author Sebastian Marek <proofek@gmail.com>
 */
class ProcessFailedExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests ProcessFailedException throws exception if the process was successful.
     */
    public function testProcessFailedExceptionThrowsException()
    {
        $process = $this->getMock(
            'Symfony\Component\Process\Process',
            array('isSuccessful'),
            array('php')
        );
        $process->expects($this->once())
            ->method('isSuccessful')
            ->will($this->returnValue(true));

        $this->setExpectedException(
            '\InvalidArgumentException',
            'Expected a failed process, but the given process was successful.'
        );

        new ProcessFailedException($process);
    }

    /**
     * Tests ProcessFailedException uses information from process output
     * to generate exception message.
     */
    public function testProcessFailedExceptionPopulatesInformationFromProcessOutput()
    {
        $cmd = 'php';
        $exitCode = 1;
        $exitText = 'General error';
        $output = 'Command output';
        $errorOutput = 'FATAL: Unexpected error';

        $process = $this->getMock(
            'Symfony\Component\Process\Process',
            array('isSuccessful', 'getOutput', 'getErrorOutput', 'getExitCode', 'getExitCodeText', 'isOutputDisabled'),
            array($cmd)
        );
        $process->expects($this->once())
            ->method('isSuccessful')
            ->will($this->returnValue(false));

        $process->expects($this->once())
            ->method('getOutput')
            ->will($this->returnValue($output));

        $process->expects($this->once())
            ->method('getErrorOutput')
            ->will($this->returnValue($errorOutput));

        $process->expects($this->once())
            ->method('getExitCode')
            ->will($this->returnValue($exitCode));

        $process->expects($this->once())
            ->method('getExitCodeText')
            ->will($this->returnValue($exitText));

        $process->expects($this->once())
            ->method('isOutputDisabled')
            ->will($this->returnValue(false));

        $exception = new ProcessFailedException($process);

        $this->assertEquals(
            "The command \"$cmd\" failed.\nExit Code: $exitCode($exitText)\n\nOutput:\n================\n{$output}\n\nError Output:\n================\n{$errorOutput}",
            $exception->getMessage()
        );
    }

    /**
     * Tests that ProcessFailedException does not extract information from
     * process output if it was previously disabled
     */
    public function testDisabledOutputInFailedExceptionDoesNotPopulateOutput()
    {
        $cmd = 'php';
        $exitCode = 1;
        $exitText = 'General error';

        $process = $this->getMock(
            'Symfony\Component\Process\Process',
            array('isSuccessful', 'isOutputDisabled', 'getExitCode', 'getExitCodeText', 'getOutput', 'getErrorOutput'),
            array($cmd)
        );
        $process->expects($this->once())
            ->method('isSuccessful')
            ->will($this->returnValue(false));

        $process->expects($this->never())
            ->method('getOutput');

        $process->expects($this->never())
            ->method('getErrorOutput');

        $process->expects($this->once())
            ->method('getExitCode')
            ->will($this->returnValue($exitCode));

        $process->expects($this->once())
            ->method('getExitCodeText')
            ->will($this->returnValue($exitText));

        $process->expects($this->once())
            ->method('isOutputDisabled')
            ->will($this->returnValue(true));

        $exception = new ProcessFailedException($process);

        $this->assertEquals(
            "The command \"$cmd\" failed.\nExit Code: $exitCode($exitText)",
            $exception->getMessage()
        );
    }
}
