<?php

declare(strict_types=1);

namespace StrappinPhp\Engine;

use InvalidArgumentException;

use function http_build_query;
use function is_string;
use function parse_str;
use function preg_match;

use const false;
use const null;

/**
 * @phpstan-type ArgsArray array<string,string>
 */
class BootstrapUrl
{
    /** @var string */
    private const SCHEME = 'bs';

    private static string $regExp;

    private string $action = '';

    /**
     * @phpstan-var ArgsArray
     */
    private array $args = [];

    /**
     * @phpstan-param ArgsArray $args
     */
    public function __construct(
        string $action,
        array $args = []
    ) {
        $this
            ->action($action)
            ->args($args)
        ;
    }

    /**
     * @phpstan-return ($name is null ? string : static)
     */
    public function action(string $name = null)
    {
        if (null === $name) {
            return $this->action;
        }

        $this->action = $name;

        return $this;
    }

    /**
     * Returns `null` if the arg with the specified name doesn't exist
     *
     * @param string|null|false $value
     * @phpstan-return ($value is false ? string|null : static)
     * @throws InvalidArgumentException If the value is invalid
     */
    public function arg(string $name, $value = false)
    {
        if (false === $value) {
            return $this->args[$name]
                ?? null
            ;
        }

        if (null === $value) {
            unset($this->args[$name]);

            return $this;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException('The value is invalid');
        }

        $this->args[$name] = $value;

        return $this;
    }

    /**
     * @phpstan-param ArgsArray|null $args
     * @phpstan-return ($args is null ? ArgsArray : static)
     */
    public function args(array $args = null)
    {
        if (null === $args) {
            return $this->args;
        }

        foreach ($args as $name => $value) {
            $this->arg($name, $value);
        }

        return $this;
    }

    public function __toString(): string
    {
        $paramsStr = $this->args()
            ? '?' . http_build_query($this->args())
            : ''
        ;

        return self::SCHEME . ':' . $this->action . $paramsStr;
    }

    private static function getRegExp(): string
    {
        if (!isset(self::$regExp)) {
            $scheme = self::SCHEME;
            $actionNameSubpattern = '([a-z]+)';
            $queryStringSubpattern = '(?:\?(.*))?';
            self::$regExp = "~^{$scheme}:{$actionNameSubpattern}{$queryStringSubpattern}$~";
        }

        return self::$regExp;
    }

    /**
     * Returns `true` if the specified string is a valid Bootstrap URL, or `false` otherwise
     */
    public static function validateUrl(string $something): bool
    {
        return (bool) preg_match(self::getRegExp(), $something);
    }

    /**
     * Returns `null` if the URL-string is not a valid Bootstrap URL
     *
     * @param string $urlStr
     * @return self|null
     */
    public static function fromString(string $urlStr): ?self
    {
        $matches = [];
        $urlInvokesBootstrap = (bool) preg_match(self::getRegExp(), $urlStr, $matches);

        if (!$urlInvokesBootstrap) {
            return null;
        }

        $argsStr = $matches[2] ?? null;

        $args = [];

        if (null !== $argsStr) {
            parse_str($argsStr, $args);
        }

        /** @phpstan-var ArgsArray $args */

        return new self($matches[1], $args);
    }
}
