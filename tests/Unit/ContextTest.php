<?php

declare(strict_types=1);

use Actengage\MessageGears\Context;

it('can be created with data', function (): void {
    $context = new Context(['key' => 'value']);

    expect($context->get('key'))->toBe('value');
});

it('converts to array', function (): void {
    $context = new Context(['foo' => 'bar', 'baz' => 'qux']);

    expect($context->toArray())->toBe(['foo' => 'bar', 'baz' => 'qux']);
});

it('returns empty array when no data', function (): void {
    $context = new Context;

    expect($context->toArray())->toBe([]);
});

it('can set values', function (): void {
    $context = new Context;
    $context->set('key', 'value');

    expect($context->get('key'))->toBe('value');
});
