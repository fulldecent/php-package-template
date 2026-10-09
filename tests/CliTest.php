<?php

declare(strict_types=1);

namespace FullDecent\Cents\Tests;

use PHPUnit\Framework\TestCase;

final class CliTest extends TestCase
{
    public function testPrintsTheResult(): void
    {
        $result = $this->runCli(['1.50', '+', '0.75']);

        self::assertSame(0, $result['status']);
        self::assertSame("2.25\n", $result['stdout']);
        self::assertSame('', $result['stderr']);
    }

    public function testPrintsAnErrorAndExits1OnBadInput(): void
    {
        $result = $this->runCli(['1.00', '*', '2.00']);

        self::assertSame(1, $result['status']);
        self::assertSame('', $result['stdout']);
        self::assertStringContainsString('amounts, spaces, + and -', $result['stderr']);
    }

    /**
     * @param list<string> $arguments
     * @return array{status: int, stdout: string, stderr: string}
     */
    private function runCli(array $arguments): array
    {
        $command = array_merge([PHP_BINARY, dirname(__DIR__) . '/bin/cents'], $arguments);
        $descriptor = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];
        $process = proc_open($command, $descriptor, $pipes, dirname(__DIR__));
        self::assertIsResource($process);

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $status = proc_close($process);

        self::assertIsString($stdout);
        self::assertIsString($stderr);

        return [
            'status' => $status,
            'stdout' => $stdout,
            'stderr' => $stderr,
        ];
    }
}
