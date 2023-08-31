<?php declare(strict_types=1);
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\PHPLOC;

final class Arguments
{
    /**
     * @psalm-var list<non-empty-string>
     */
    private array $directories;

    /**
     * @psalm-var list<non-empty-string>
     */
    private array $suffixes;

    /**
     * @psalm-var list<non-empty-string>
     */
    private array $exclude;
    private bool $help;
    private bool $version;

    /**
     * @psalm-param list<non-empty-string> $directories
     * @psalm-param list<non-empty-string> $suffixes
     * @psalm-param list<non-empty-string> $exclude
     */
    public function __construct(array $directories, array $suffixes, array $exclude, bool $help, bool $version)
    {
        $this->directories = $directories;
        $this->suffixes    = $suffixes;
        $this->exclude     = $exclude;
        $this->help        = $help;
        $this->version     = $version;
    }

    /**
     * @psalm-return list<non-empty-string>
     */
    public function directories(): array
    {
        return $this->directories;
    }

    /**
     * @psalm-return list<non-empty-string>
     */
    public function suffixes(): array
    {
        return $this->suffixes;
    }

    /**
     * @psalm-return list<non-empty-string>
     */
    public function exclude(): array
    {
        return $this->exclude;
    }

    public function help(): bool
    {
        return $this->help;
    }

    public function version(): bool
    {
        return $this->version;
    }
}
