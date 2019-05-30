# Havoc-Collection
PHP scalar, array, and strict-typed object collections.

## Use
Set `ObjectCollection::$expected_class_name` to define what class/interface objects must be instances of.

## Type Hinting
Consider setting "@method" annotations for the following methods to enforce type hinting:
 - `@method MyObject current()`
 - `@method MyObject offsetGet($offset)`
 - `@method MyCollection slice(int $offset, ?int $length = null, bool $preserve_keys = false)`
 - `@method MyObject[] dump()`
