<?php
declare(strict_types=1);

namespace Havoc\Collection;

/**
 * Havoc Collection.
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
class Collection implements \ArrayAccess, \Iterator, \Countable
{
    /**
     * Internal array.
     *
     * @var array
     */
    protected $collection = [];
    
    /**
     * Collection constructor method.
     *
     * @param array|Collection $initial_elements
     */
    public function __construct($initial_elements = [])
    {
        foreach ($initial_elements as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }
    
    /**
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->collection);
    }
    
    /**
     * @return void
     */
    public function next(): void
    {
        next($this->collection);
    }
    
    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->collection);
    }
    
    /**
     * @return bool
     */
    public function valid(): bool
    {
        return key($this->collection) !== null;
    }
    
    /**
     * @return void
     */
    public function rewind(): void
    {
        reset($this->collection);
    }
    
    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->collection[$offset]);
    }
    
    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->collection[$offset] ?? null;
    }
    
    /**
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->collection[] = $value;
            
            return;
        }
        
        $this->collection[$offset] = $value;
    }
    
    /**
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->collection[$offset]);
    }
    
    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->collection);
    }
    
    /**
     * Returns true if the collection contains an element with the given key. False if not.
     *
     * @param $key
     * @return bool
     */
    public function containsKey($key): bool
    {
        return \array_key_exists($key, $this->collection);
    }
    
    /**
     * Returns true if the collection contains an element of the given value. False if not.
     *
     * @param $value
     * @return bool
     */
    public function containsValue($value): bool
    {
        return \in_array($value, $this->collection, true);
    }
    
    /**
     * Returns true if the collection contains no elements. False if it does.
     *
     * @return bool
     */
    public function empty(): bool
    {
        return empty($this->collection);
    }
    
    /**
     * Dumps the collection.
     *
     * @return array
     */
    public function dump(): array
    {
        return $this->collection;
    }
    
    /**
     * Removes all elements from the collection.
     *
     * @return void
     */
    public function wipe(): void
    {
        $this->collection = [];
    }
    
    /**
     * Removes all elements from the collection and replaces them with new elements from another array/collection.
     *
     * @param array|Collection $new_elements
     */
    public function overwrite($new_elements): void
    {
        $this->collection = [];
        
        foreach ($new_elements as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }
    
    /**
     * Merges the collection with an array/collection and overwrites identical keys.
     *
     * @param array|Collection $additional_elements
     * @return void
     */
    public function merge($additional_elements): void
    {
        foreach ($additional_elements as $key => $value) {
            $this->collection[$key] = $value;
        }
    }
    
    /**
     * Appends another array/collection to the end of the collection.
     *
     * @param array|Collection $additional_elements
     * @return void
     */
    public function append($additional_elements): void
    {
        foreach ($additional_elements as $value) {
            $this->collection[] = $value;
        }
    }
    
    /**
     * Prepends another array/collection to the start of the collection.
     *
     * @param array|Collection $additional_elements
     * @param bool $preverse_original_order
     * @return void
     */
    public function prepend($additional_elements, bool $preverse_original_order = false): void
    {
        if ($preverse_original_order === true) {
            if ($additional_elements instanceof self === true) {
                $additional_elements->reverse();
            } else {
                $additional_elements = array_reverse($additional_elements);
            }
        }
        
        foreach ($additional_elements as $additional_element) {
            array_unshift($this->collection, $additional_element);
        }
    }
    
    /**
     * PHP array_splice.
     *
     * @param int $offset
     * @param int $length
     * @param $replacement
     * @return void
     * @see https://www.php.net/manual/en/function.array-splice.php
     */
    public function splice(int $offset, ?int $length = null, $replacement = null): void
    {
        array_splice($this->collection, $offset, $length, $replacement);
    }
    
    /**
     * PHP array_slice.
     *
     * @param int $offset
     * @param int|null $length
     * @param bool $preserve_keys
     * @return static
     * @see https://www.php.net/manual/en/function.array-slice.php
     */
    public function slice(int $offset, ?int $length = null, bool $preserve_keys = false): self {
        return new static(
            \array_slice($this->collection, $offset, $length, $preserve_keys)
        );
    }
    
    /**
     * PHP array_reverse.
     *
     * @return void
     * @see https://www.php.net/manual/en/function.array-reverse.php
     */
    public function reverse(): void
    {
        $this->collection = array_reverse($this->collection);
    }
    
    /**
     * PHP usort.
     *
     * @param callable $compare_function
     * @return void
     * @see https://www.php.net/manual/en/function.usort.php
     */
    public function usort(callable $compare_function): void
    {
        $success = usort($this->collection, $compare_function);
        
        if ($success === false) {
            throw CollectionException::usortFail();
        }
    }
    
    /**
     * PHP uksort.
     *
     * @param callable $compare_function
     * @return void
     * @see https://www.php.net/manual/en/function.uksort.php
     */
    public function uksort(callable $compare_function): void
    {
        $success = uksort($this->collection, $compare_function);
        
        if ($success === false) {
            throw CollectionException::uksortFail();
        }
    }
    
    /**
     * PHP uasort.
     *
     * @param callable $compare_function
     * @return void
     * @see https://www.php.net/manual/en/function.uasort.php
     */
    public function uasort(callable $compare_function): void
    {
        $success = uasort($this->collection, $compare_function);
        
        if ($success === false) {
            throw CollectionException::uasortFail();
        }
    }
    
    /**
     * PHP sort.
     *
     * @param int $sort_flags
     * @return void
     * @see https://www.php.net/manual/en/function.sort.php
     */
    public function sort(int $sort_flags = SORT_REGULAR): void
    {
        $success = sort($this->collection, $sort_flags);
        
        if ($success === false) {
            throw CollectionException::sortFail();
        }
    }
    
    /**
     * PHP rsort.
     *
     * @param int $sort_flags
     * @return void
     * @see https://www.php.net/manual/en/function.rsort.php
     */
    public function rsort(int $sort_flags = SORT_REGULAR): void
    {
        $success = rsort($this->collection, $sort_flags);
    
        if ($success === false) {
            throw CollectionException::rsortFail();
        }
    }
    
    /**
     * PHP natsort.
     *
     * @return void
     * @see https://www.php.net/manual/en/function.natsort.php
     */
    public function natsort(): void
    {
        $success = natsort($this->collection);
        
        if ($success === false) {
            throw CollectionException::natsortFail();
        }
    }
    
    /**
     * PHP natcasesort.
     *
     * @return void
     * @see https://www.php.net/manual/en/function.natcasesort.php
     */
    public function natcasesort(): void
    {
        $success = natcasesort($this->collection);
        
        if ($success === false) {
            throw CollectionException::natcasesortFail();
        }
    }
    
    /**
     * PHP ksort.
     *
     * @param int $sort_flags
     * @return void
     * @see https://www.php.net/manual/en/function.ksort.php
     */
    public function ksort(int $sort_flags = SORT_REGULAR): void
    {
        $success = ksort($this->collection, $sort_flags);
        
        if ($success === false) {
            throw CollectionException::ksortFail();
        }
    }
    
    /**
     * PHP krsort.
     *
     * @param int $sort_flags
     * @return void
     * @see https://www.php.net/manual/en/function.krsort.php
     */
    public function krsort(int $sort_flags = SORT_REGULAR): void
    {
        $success = krsort($this->collection, $sort_flags);
        
        if ($success === false) {
            throw CollectionException::krsortFail();
        }
    }
    
    /**
     * PHP asort.
     *
     * @param int $sort_flags
     * @return void
     * @see https://www.php.net/manual/en/function.asort.php
     */
    public function asort(int $sort_flags = SORT_REGULAR): void
    {
        $success = asort($this->collection, $sort_flags);
        
        if ($success === false) {
            throw CollectionException::asortFail();
        }
    }
    
    /**
     * PHP arsort.
     *
     * @param int $sort_flags
     * @return void
     * @see https://www.php.net/manual/en/function.arsort.php
     */
    public function arsort(int $sort_flags = SORT_REGULAR): void
    {
        $success = arsort($this->collection, $sort_flags);
        
        if ($success === false) {
            throw CollectionException::arsortFail();
        }
    }
    
    /**
     * PHP array_flip.
     *
     * @return void
     * @see https://www.php.net/manual/en/function.array-flip.php
     */
    public function flip(): void
    {
        $this->collection = array_flip($this->collection);
    }
    
    /**
     * PHP array_keys.
     *
     * @return Collection
     * @see https://www.php.net/manual/en/function.array-keys.php
     */
    public function keys(): self
    {
        $keys = array_keys($this->collection);
        
        return new Collection($keys);
    }
}
