<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\Tests;

use DanBettles\Marigold\AbstractTestCase;
use StrappinPhp\Engine\BootstrapUrl;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;

use const false;
use const null;
use const true;

class BootstrapUrlTest extends AbstractTestCase
{
    /** @return array<mixed[]> */
    public function providesUrlStrings(): array
    {
        return [
            [
                'bs:action',
                new BootstrapUrl('action'),
            ],
            [
                'bs:action?foo=bar&baz=qux',
                new BootstrapUrl('action', ['foo' => 'bar', 'baz' => 'qux']),
            ],
            [
                'bs:action?foo=bar&baz=qux',
                (new BootstrapUrl('action'))
                    ->args(['foo' => 'bar', 'baz' => 'qux']),
            ],
        ];
    }

    /** @dataProvider providesUrlStrings */
    public function testTostringReturnsAUrlString(
        string $expectedUrlString,
        BootstrapUrl $bootstrapUrl
    ): void {
        $this->assertSame($expectedUrlString, "{$bootstrapUrl}");
    }

    /** @return array<mixed[]> */
    public function providesBootstrapUrls(): array
    {
        return [
            [
                new BootstrapUrl('action'),
                'bs:action',
            ],
            [
                new BootstrapUrl('action', ['foo' => 'bar', 'baz' => 'qux']),
                'bs:action?foo=bar&baz=qux',
            ],
        ];
    }

    /** @dataProvider providesBootstrapUrls */
    public function testFromstringCreatesAnInstanceFromAUrlString(
        BootstrapUrl $expectedInstance,
        string $urlString
    ): void {
        $this->assertEquals($expectedInstance, BootstrapUrl::fromString($urlString));
    }

    /** @return array<mixed[]> */
    public function providesInvalidUrlStrings(): array
    {
        return [
            [
                'bs:?foo=bar&baz=qux',
            ],
            [
                'bs:',
            ],
            [
                'https://example.com/',
            ],
            [
                '',
            ],
        ];
    }

    /** @dataProvider providesInvalidUrlStrings */
    public function testFromstringReturnsNullIfTheUrlStringIsInvalid(string $invalidUrlString): void
    {
        $this->assertNull(BootstrapUrl::fromString($invalidUrlString));
    }

    public function testActionIsUsedToSetAndGetTheNameOfTheAction(): void
    {
        $url = new BootstrapUrl('action');

        $this->assertSame('action', $url->action());

        $something = $url->action('newAction');

        $this->assertSame('newAction', $url->action());
        $this->assertSame($url, $something);
    }

    public function testArgIsUsedToSetAndGetASingleArg(): void
    {
        $url = new BootstrapUrl('action', ['foo' => 'bar']);

        $this->assertSame('bar', $url->arg('foo'));

        $something = $url->arg('baz', 'qux');

        $this->assertSame('qux', $url->arg('baz'));
        $this->assertSame($url, $something);
    }

    public function testArgReturnsNullIfTheArgDoesNotExist(): void
    {
        $url = new BootstrapUrl('action');

        $this->assertNull($url->arg('foo'));
    }

    public function testArgsIsUsedToSetAndGetTheArgs(): void
    {
        $url = new BootstrapUrl('action');

        $this->assertSame([], $url->args());

        $something = $url->args(['foo' => 'bar', 'baz' => 'qux']);

        $this->assertSame([
            'foo' => 'bar',
            'baz' => 'qux',
        ], $url->args());

        $this->assertSame($url, $something);
    }

    public function testArgsUsesArgToSetEachArg(): void
    {
        /** @var MockObject */
        $mockUrl = $this
            ->getMockBuilder(BootstrapUrl::class)
            ->onlyMethods(['arg'])
            ->setConstructorArgs(['action'])
            ->getMock()
        ;

        $mockUrl
            ->expects($this->exactly(2))
            ->method('arg')
            ->withConsecutive(
                ['foo', 'bar'],
                ['baz', 'qux'],
            )
            ->willReturnSelf()
        ;

        /** @var BootstrapUrl $mockUrl */

        $mockUrl->args([
            'foo' => 'bar',
            'baz' => 'qux',
        ]);
    }

    public function testAnArgCanBeRemovedBySettingItsValueToNull(): void
    {
        $url = new BootstrapUrl('action', [
            'foo' => 'bar',
            'baz' => 'qux',
        ]);

        $url->arg('baz', null);

        $this->assertSame([
            'foo' => 'bar',
        ], $url->args());
    }

    /** @return array<mixed[]> */
    public function providesInvalidArgValues(): array
    {
        return [
            [123],
            [true],
            [array()],
        ];
    }

    /**
     * @dataProvider providesInvalidArgValues
     * @param mixed $invalidArgValue
     */
    public function testArgThrowsAnExceptionIfTheValueIsInvalid($invalidArgValue): void
    {
        /** @phpstan-var string $invalidArgValue Because we want to see what happens when we pass something invalid */

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value is invalid');

        (new BootstrapUrl('action'))
            ->arg('foo', $invalidArgValue)
        ;
    }

    /** @dataProvider providesBootstrapUrls */
    public function testValidateurlReturnsTrueIfTheUrlIsAValidBootstrapUrl(
        BootstrapUrl $ignore,
        string $bootstrapUrlStr
    ): void {
        $this->assertTrue(BootstrapUrl::validateUrl($bootstrapUrlStr));
    }

    /** @dataProvider providesInvalidUrlStrings */
    public function testValidateurlReturnsFalseIfTheUrlIsAnInvalidBootstrapUrl(string $invalid): void
    {
        $this->assertFalse(BootstrapUrl::validateUrl($invalid));
    }
}
