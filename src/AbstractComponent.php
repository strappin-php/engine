<?php

declare(strict_types=1);

namespace StrappinPhp\Engine;

use OutOfBoundsException;
use ReflectionClass;

use function array_key_exists;
use function array_map;
use function array_reverse;
use function call_user_func_array;
use function get_called_class;
use function implode;
use function str_replace;
use function strpos;

use const false;
use const null;
use const true;

/**
 * @phpstan-import-type AttributeValue from HtmlHelper
 * @phpstan-import-type AttributesArray from HtmlHelper
 * @phpstan-type PropValue mixed
 * @phpstan-type PropsArray array<string,PropValue>
 */
abstract class AbstractComponent
{
    protected static string $rootTagName = 'div';

    /**
     * This defines the names, and default values, of permissible props
     *
     * @phpstan-var PropsArray
     */
    protected static array $defaultProps = [];

    /**
     * This defines the names, and default values, of permissible attrs
     *
     * @phpstan-var AttributesArray
     */
    protected static array $defaultAttrs = [
        'id' => null,  // An `id` attribute can be given to any component
    ];

    private HtmlHelper $htmlHelper;

    /**
     * @var AbstractComponent[]
     */
    private array $children;

    /**
     * @phpstan-var PropsArray
     */
    private array $props;

    /**
     * @phpstan-var AttributesArray
     */
    private array $attrs;

    /**
     * In general, this must be called before all other code in the constructor of a subclass
     */
    public function __construct(HtmlHelper $htmlHelper)
    {
        $this->htmlHelper = $htmlHelper;
        $this->children = [];
        $this->initializeProps();
        $this->initializeAttrs();
    }

    /**
     * @return array<mixed[]>
     */
    private function getAllArrayStaticPropertiesInLineage(string $propertyName): array
    {
        $calledClass = new ReflectionClass(get_called_class());
        $arraysInLineage = [];

        do {
            /** @var mixed[] */
            $arrayInLineage = $calledClass->getStaticPropertyValue($propertyName, []);
            $arraysInLineage[] = $arrayInLineage;
        } while ($calledClass = $calledClass->getParentClass());

        // `array_merge()` order: values in arrays declared in child classes will override those in ancestors
        $arraysInLineageInMergeOrder = array_reverse($arraysInLineage);

        return $arraysInLineageInMergeOrder;
    }

    private function initializeProps(): void
    {
        $defaultPropsInLineageInMergeOrder = $this->getAllArrayStaticPropertiesInLineage('defaultProps');
        /** @phpstan-var PropsArray */
        $mergedDefaultProps = call_user_func_array('\array_replace', $defaultPropsInLineageInMergeOrder);

        $this->props = $mergedDefaultProps;
    }

    private function initializeAttrs(): void
    {
        $defaultAttrsInLineageInMergeOrder = $this->getAllArrayStaticPropertiesInLineage('defaultAttrs');

        $anArrayContainsClass = false;

        foreach ($defaultAttrsInLineageInMergeOrder as $arrayInLineage) {
            if (array_key_exists('class', $arrayInLineage)) {
                $anArrayContainsClass = true;

                break;
            }
        }

        /** @phpstan-var AttributesArray */
        $mergedDefaultAttrs = call_user_func_array('\array_replace', $defaultAttrsInLineageInMergeOrder);

        if ($anArrayContainsClass) {
            $placeholderForDefault = '{{ parent }}';

            $classStrSoFar = '';

            foreach ($defaultAttrsInLineageInMergeOrder as $defaultAttrs) {
                if (!array_key_exists('class', $defaultAttrs)) {
                    continue;
                }

                $classStrHere = $defaultAttrs['class'];

                if (null === $classStrHere || false === strpos($classStrHere, $placeholderForDefault)) {
                    // `null` does what you'd expect: it clears everything so far
                    $classStrSoFar = $classStrHere;
                } else {
                    $classStrSoFar = str_replace($placeholderForDefault, $classStrSoFar, $classStrHere);
                }
            }

            $mergedDefaultAttrs['class'] = $classStrSoFar;
        }

        $this->attrs = $mergedDefaultAttrs;
    }

    public function addChild(AbstractComponent $child): self
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * @return AbstractComponent[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function hasChildren(): bool
    {
        return (bool) $this->getChildren();
    }

    /**
     * Returns all prop values
     *
     * @phpstan-return PropsArray
     */
    public function props(): array
    {
        return $this->props;
    }

    /**
     * Returns the value of the prop with the specified name if only a name is passed.  Sets the value of the prop if a
     * value is also passed -- as the second argument.
     *
     * @phpstan-param PropValue|void ...$otherArgs
     * @phpstan-return ($otherArgs is void ? PropValue : static)
     * @throws OutOfBoundsException If the prop does not exist
     */
    public function prop(string $name, ...$otherArgs)
    {
        if (!array_key_exists($name, $this->props)) {
            throw new OutOfBoundsException("The prop `{$name}` does not exist");
        }

        if ($otherArgs) {
            $this->props[$name] = $otherArgs[0];

            return $this;
        }

        return $this->props()[$name];
    }

    /**
     * Returns all attr values
     *
     * @phpstan-return AttributesArray
     */
    public function attrs(): array
    {
        return $this->attrs;
    }

    /**
     * Returns the value of the attr with the specified name if only a name is passed.  Sets the value of the attr if a
     * value is also passed -- as the second argument.
     *
     * @phpstan-param AttributeValue|void ...$otherArgs
     * @phpstan-return ($otherArgs is void ? AttributeValue : static)
     * @throws OutOfBoundsException If the attr does not exist
     */
    public function attr(string $name, ...$otherArgs)
    {
        if (!array_key_exists($name, $this->attrs)) {
            throw new OutOfBoundsException("The attr `{$name}` does not exist");
        }

        if ($otherArgs) {
            /** @phpstan-var AttributeValue[] $otherArgs */

            $this->attrs[$name] = $otherArgs[0];

            return $this;
        }

        return $this->attrs()[$name];
    }

    protected function getHtmlHelper(): HtmlHelper
    {
        return $this->htmlHelper;
    }

    /**
     * Returns the HTML of the descendants of the called object
     */
    public function serializeChildren(): string
    {
        if (!$this->hasChildren()) {
            return '';
        }

        return implode("\n", array_map(
            fn (AbstractComponent $child): string => $child->serialize(),
            $this->getChildren()
        ));
    }

    /**
     * Returns the HTML of the called object and its descendants
     */
    public function serialize(): string
    {
        $childrenHtml = "\n" . ($this->hasChildren() ? $this->serializeChildren() . "\n" : '');

        return $this->getHtmlHelper()->createElement(
            static::$rootTagName,
            $this->attrs(),
            $childrenHtml
        );
    }

    /**
     * @see AbstractComponent::serialize()
     */
    public function __toString(): string
    {
        return $this->serialize();
    }
}
