<?php

test('env can deal with TRUE', function() {
    expect(env('TEST1'))->toBeTrue()
        ->and(env('TEST2'))->toBeTrue();
});

test('env can deal with FALSE', function() {
    expect(env('TEST3'))->toBeFalse()
        ->and(env('TEST4'))->toBeFalse();
});

test('env can deal with EMPTY', function() {
    expect(env('TEST5'))->toBe('');
});

test('env can deal with NULL', function() {
    expect(env('TEST6'))->toBe(null);
});

test('env can deal with DEFAULT', function() {
    expect(env('TEST7'))->toBe('a value');
});
