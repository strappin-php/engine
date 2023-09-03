# Strappin' 💪

[Bootstrap](https://getbootstrap.com/) is super awesome for quickly building easy-to-use&mdash;and easy-on-the-eyes&mdash;Web interfaces, but things can quickly become very, very fiddly.  We really appreciate their consideration of accessibility and semantics, but that necessarily comes with a lot of words and a lot of wires.

Strappin' is a growing collection of PHP classes, and&mdash;crucially&mdash;a factory, that makes creating Bootstrap markup quicker and easier.  Its power is in joining things up so you can stop worrying about IDs, roles, and `aria-this`~`aria-that`.

This library can be easily integrated with templating engines such as [Twig](https://twig.symfony.com/).

## Example

Strappin' is still being moulded into the right shape&mdash;so we aren't ready to fully document everything yet&mdash;but here's an example of how you can currently use the library to create a tabbed interface:

```php
use StrappinPhp\Engine\Factory;

echo Factory::create()->createTabbedInterface([
    'panels' => [
        [
            'action' => 'bs:toggle?object=tab&target=optimal-tab-pane-1',
            'label' => 'Tab-Pane 1 Label',
            'content' => 'Tab-pane 1 content.',
        ],
        [
            'action' => 'bs:toggle?object=tab&target=optimal-tab-pane-2',
            'label' => 'Tab-Pane 2 Label',
            'content' => 'Tab-pane 2 content.',
        ],
        [
            'action' => 'https://example.com/',
            'label' => 'External Link',
        ],
    ],
]);
```

> ℹ️ We use a custom URL format to easily express Bootstrap actions

## Live Demo

For now:

- Clone this repo
- Run `composer install` at the root of the clone
- Navigate to `<hostname>/<path-to-clone>/tests/functional/index.php`
