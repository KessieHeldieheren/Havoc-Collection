<?php
declare(strict_types=1);

namespace Havoc\Collection;

/**
 * Havoc Object Collection.
 *
 * Set ObjectCollection::$expected_class_name to define what class/interface objects must be instances of.
 *
 * Consider setting "@method" annotations for the following methods to enforce type hinting:
 * - `@method MyObject current()`
 * - `@method MyObject offsetGet($offset)`
 * - `@method MyCollection slice(int $offset, ?int $length = null, bool $preserve_keys = false)`
 * - `@method MyObject[] dump()`
 *
 * @package Havoc/Collection (https://github.com/KessieHeldieheren/Havoc-Collection)
 * @author Kessie Heldieheren (kessie@sdstudios.uk)
 * @version 1.0.0
 * @license MIT License (https://github.com/KessieHeldieheren/Havoc-Collection/blob/master/LICENSE)
 */
class ObjectCollection extends Collection
{
    /**
     * Expected class/interface name for objects in our collection.
     *
     * @var string
     */
    protected static $expected_class_name;
    
    /**
     * ObjectCollection constructor method.
     *
     * @param array $initial_elements
     */
    public function __construct($initial_elements = [])
    {
        if (static::$expected_class_name === null) {
            throw CollectionException::noClassSet(__CLASS__);
        }
        
        parent::__construct($initial_elements);
    }
    
    /**
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->validateElement($value);
        
        if ($offset === null) {
            $this->collection[] = $value;
            
            return;
        }
        
        $this->collection[$offset] = $value;
    }
    
    /**
     * @param $value
     * @return bool
     */
    public function containsValue($value): bool
    {
        $this->validateElement($value);
        
        return parent::containsValue($value);
    }
    
    /**
     * @param $element
     */
    private function validateElement($element): void
    {
        if ($element instanceof static::$expected_class_name === false) {
            throw CollectionException::invalidElementType(
                __CLASS__,
                $element,
                static::$expected_class_name
            );
        }
    }
}
