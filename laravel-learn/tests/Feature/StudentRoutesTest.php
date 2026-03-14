<?php

it('has a students index route', function () {
    expect(route('students.index'))->toBe(url('/students'));
});

it('has a students create route', function () {
    expect(route('students.create'))->toBe(url('/students/create'));
});

it('has a students store route', function () {
    expect(route('students.store'))->toBe(url('/students'));
});
