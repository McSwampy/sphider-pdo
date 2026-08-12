<?php

declare(strict_types=1);

namespace Framework\CLI;

/**
 * CLI Handler
 *
 * Provides access to the command-line environment and arguments.
 *
 * @author McSwampy <mcswampy@sylph.co.za>
 * @since 2.0.0
 * @copyright 2026 Sylph Syndicate
 * 
 * @example 
 * $cli = new \Framework\CLI\Handler();
 * $command = $cli->getCommand();
 * $args = $cli->getArgumentsAfterCommand();
 *
 */
class Handler
{
    /**
     * Raw command-line arguments.
     *
     * @var array<int, string>
     */
    private array $arguments;

    /**
     * Create a CLI handler.
     *
     * @param array<int, string>|null $arguments Command-line arguments.
     */
    public function __construct(?array $arguments = null)
    {
        $this->arguments = $arguments ?? $_SERVER['argv'] ?? [];
    }

    /**
     * Return all command-line arguments.
     *
     * @return array<int, string>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Return the executable name.
     *
     * @return string|null Executable name or null if unavailable.
     */
    public function getExecutable(): ?string
    {
        return $this->arguments[0] ?? null;
    }

    /**
     * Return the command name.
     *
     * The command is normally the first argument after the executable.
     *
     * @return string|null Command name or null if none was supplied.
     */
    public function getCommand(): ?string
    {
        return $this->arguments[1] ?? null;
    }

    /**
     * Return positional arguments after the command.
     *
     * @return array<int, string>
     */
    public function getArgumentsAfterCommand(): array
    {
        return array_slice($this->arguments, 2);
    }

    /**
     * Determine whether a command-line argument exists.
     *
     * @param string $argument Argument to search for.
     *
     * @return bool
     */
    public function has(string $argument): bool
    {
        return in_array($argument, $this->arguments, true);
    }
}
