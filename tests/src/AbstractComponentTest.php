<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\Tests;

use DanBettles\Marigold\AbstractTestCase;
use StrappinPhp\Engine\AbstractComponent;
use StrappinPhp\Engine\HtmlHelper;
use StrappinPhp\Engine\Tests\AbstractComponentTest\TabsNav;
use OutOfBoundsException;

use const null;

class AbstractComponentTest extends AbstractTestCase
{
    /** @factory AbstractComponent */
    private function createDefaultComponent(): AbstractComponent
    {
        $htmlHelper = new HtmlHelper();

        return new class ($htmlHelper) extends AbstractComponent {
        };
    }

    public function testIsAbstract(): void
    {
        $this->assertTrue($this->getTestedClass()->isAbstract());
    }

    public function testIsComposable(): void
    {
        $parent = $this->createDefaultComponent();
        $child1 = $this->createDefaultComponent();
        $child2 = $this->createDefaultComponent();

        $parent
            ->addChild($child1)
            ->addChild($child2)
        ;

        $this->assertTrue($parent->hasChildren());

        $this->assertSame([
            $child1,
            $child2,
        ], $parent->getChildren());
    }

    // ...Because it was once abstract
    public function testSerializeIsConcrete(): void
    {
        $class = $this->getTestedClass();

        $this->assertTrue($class->hasMethod('serialize'));

        $serialize = $class->getMethod('serialize');

        $this->assertTrue($serialize->isPublic());
        $this->assertFalse($serialize->isAbstract());
    }

    /** @return array<mixed[]> */
    public function providesDefaultComponentHtml(): array
    {
        $htmlHelper = new HtmlHelper();

        $argLists = [];

        $grandchild = new class ($htmlHelper) extends AbstractComponent {
            protected static string $rootTagName = 'p';
            protected static array $defaultAttrs = ['class' => 'grandchild', 'id' => 'grandchild-1'];
        };

        $child = (new class ($htmlHelper) extends AbstractComponent {
            protected static array $defaultAttrs = ['class' => 'child', 'id' => 'child-1'];
        })
            ->addChild($grandchild)
        ;

        $parent = (new class ($htmlHelper) extends AbstractComponent {
            protected static array $defaultAttrs = ['class' => 'parent', 'id' => 'parent-1'];
        })
            ->addChild($child)
        ;

        $argLists[] = [
            <<<END
            <div id="parent-1" class="parent">
            <div id="child-1" class="child">
            <p id="grandchild-1" class="grandchild">
            </p>
            </div>
            </div>
            END,
            $parent,
        ];

        return $argLists;
    }

    /** @dataProvider providesDefaultComponentHtml */
    public function testSerializeReturnsTheHtmlOfTheCalledObjectAndItsDescendants(
        string $expected,
        AbstractComponent $component
    ): void {
        $this->assertSame($expected, $component->serialize());
    }

    public function testSerializechildrenReturnsTheHtmlOfTheDescendantsOfTheCalledObject(): void
    {
        $htmlHelper = new HtmlHelper();

        $grandchild = new class ($htmlHelper) extends AbstractComponent {
            protected static array $defaultAttrs = ['id' => 'grandchild'];
        };

        $childWithOwnChild = (new class ($htmlHelper) extends AbstractComponent {
            protected static array $defaultAttrs = ['id' => 'child-with-own-child'];
        })
            ->addChild($grandchild)
        ;

        $childWithNoChildren = new class ($htmlHelper) extends AbstractComponent {
            protected static array $defaultAttrs = ['id' => 'child-with-no-children'];
        };

        $parent = $this
            ->createDefaultComponent()
            ->addChild($childWithOwnChild)
            ->addChild($childWithNoChildren)
        ;

        $this->assertSame(<<<END
        <div id="child-with-own-child">
        <div id="grandchild">
        </div>
        </div>
        <div id="child-with-no-children">
        </div>
        END, $parent->serializeChildren());
    }

    public function testIsStringable(): void
    {
        $class = $this->getTestedClass();

        $this->assertTrue($class->hasMethod('__toString'));

        $toString = $class->getMethod('__toString');

        $this->assertFalse($toString->isAbstract());
        $this->assertTrue($toString->isPublic());
    }

    public function testTostringReturnsTheHtmlOfTheCalledObjectAndItsChildren(): void
    {
        $mockComponent = $this
            ->getMockBuilder(AbstractComponent::class)
            ->onlyMethods(['serialize'])
            ->setConstructorArgs([new HtmlHelper()])
            ->getMock()
        ;

        $mockComponent
            ->expects($this->once())
            ->method('serialize')
            ->willReturn('only foo')
        ;

        $this->assertSame('only foo', "{$mockComponent}");
    }

    public function testHasProps(): void
    {
        $component = $this->createDefaultComponent();

        $this->assertSame([
        ], $component->props());

        $component = new class (new HtmlHelper()) extends AbstractComponent {
            protected static array $defaultProps = [
                'foo' => null,
                'bar' => '123',
            ];
        };

        $this->assertSame([
            'foo' => null,
            'bar' => '123',
        ], $component->props());
    }

    public function testPropsAreAccessible(): void
    {
        $component = new class (new HtmlHelper()) extends AbstractComponent {
            protected static array $defaultProps = [
                'foo' => null,
                'bar' => '123',
            ];
        };

        $component->prop('bar', '987');

        $this->assertSame([
            'foo' => null,
            'bar' => '987',
        ], $component->props());

        $this->assertSame('987', $component->prop('bar'));
    }

    public function testPropThrowsAnExceptionIfThePropDoesNotExist(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage("The prop `nonexistent` does not exist");

        $component = new class (new HtmlHelper()) extends AbstractComponent {
            protected static array $defaultProps = [
                'foo' => null,
            ];
        };

        $component->prop('nonexistent');
    }

    public function testHasAttrs(): void
    {
        $component = $this->createDefaultComponent();

        $this->assertSame([
            'id' => null,
        ], $component->attrs());

        $component = new class (new HtmlHelper()) extends AbstractComponent {
            protected static array $defaultAttrs = [
                'title' => null,
            ];
        };

        $this->assertSame([
            'id' => null,
            'title' => null,
        ], $component->attrs());
    }

    public function testAttrsAreAccessible(): void
    {
        $component = new class (new HtmlHelper()) extends AbstractComponent {
            protected static array $defaultAttrs = [
                'title' => null,
            ];
        };

        $component->attr('id', 'foo');
        $component->attr('title', 'Lorem ipsum dolor');

        $this->assertSame([
            'id' => 'foo',
            'title' => 'Lorem ipsum dolor',
        ], $component->attrs());

        $this->assertSame('foo', $component->attr('id'));
        $this->assertSame('Lorem ipsum dolor', $component->attr('title'));
    }

    public function testAttrThrowsAnExceptionIfTheAttrDoesNotExist(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage("The attr `nonexistent` does not exist");

        $component = new class (new HtmlHelper()) extends AbstractComponent {
            protected static array $defaultAttrs = [
                'title' => null,
            ];
        };

        $component->attr('nonexistent');
    }

    public function testDefaultAttrsCanBeAugmented(): void
    {
        $tabsNav = new TabsNav(new HtmlHelper());

        $this->assertSame(<<<END
        <div class="nav nav-tabs">
        </div>
        END, "{$tabsNav}");
    }
}
