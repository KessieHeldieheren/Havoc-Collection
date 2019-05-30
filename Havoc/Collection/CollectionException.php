<?php
declare(strict_types=1);

namespace Havoc\Collection;

/**
 * Havoc Collection exceptions.
 *
 * @package Havoc/Collection (https://github.com/KessieHeldieheren/Havoc-Collection)
 * @author Kessie Heldieheren (kessie@sdstudios.uk)
 * @version 1.0.0
 * @license MIT License (https://github.com/KessieHeldieheren/Havoc-Collection/blob/master/LICENSE)
 */
class CollectionException extends \RuntimeException
{
    public const INVALID_ELEMENT_TYPE = 100;
    public const NO_CLASS_SET = 101;
    public const USORT_FAIL = 600;
    public const UKSORT_FAIL = 601;
    public const UASORT_FAIL = 602;
    public const SORT_FAIL = 603;
    public const RSORT_FAIL = 604;
    public const NATSORT_FAIL = 605;
    public const NATCASESORT_FAIL = 606;
    public const KSORT_FAIL = 607;
    public const KRSORT_FAIL = 608;
    public const ASORT_FAIL = 609;
    public const ARSORT_FAIL = 610;
    
    /**
     * @param string $class
     * @param $element
     * @param string $expected
     * @return CollectionException
     */
    public static function invalidElementType(string $class, $element, string $expected): self
    {
        $message = sprintf(
            "%s was provided an invalid type of '%s'. Expected '%s'",
            $class,
            self::getElementType($element),
            $expected
        );
        
        return new self($message, self::INVALID_ELEMENT_TYPE);
    }
    
    /**
     * @param string $collection_class_name
     * @return CollectionException
     */
    public static function noClassSet(string $collection_class_name): self
    {
        $message = sprintf(
            "The collection '%s' does not specify a class/interface name to use. Please overwrite the protected static property '\$expected_class_name' to use a factory.",
            $collection_class_name
        );
        
        return new self($message, self::NO_CLASS_SET);
    }
    
    /**
     * @return CollectionException
     */
    public static function usortFail(): self
    {
        $message = "The call to usort on the collection failed (usort returned false).";
        
        return new self($message, self::USORT_FAIL);
    }
    
    /**
     * @return CollectionException
     */
    public static function uksortFail(): self
    {
        $message = "The call to uksort on the collection failed (uksort returned false).";
    
        return new self($message, self::UKSORT_FAIL);
    }
    
    /**
     * @return CollectionException
     */
    public static function uasortFail(): self
    {
        $message = "The call to uasort on the collection failed (uasort returned false).";
        
        return new self($message, self::UASORT_FAIL);
    }
    
    /**
     * @return CollectionException
     */
    public static function sortFail(): self
    {
        $message = "The call to sort on the collection failed (sort returned false).";
        
        return new self($message, self::SORT_FAIL);
    }
    
    /**
     * @return CollectionException
     */
    public static function rsortFail(): self
    {
        $message = "The call to rsort on the collection failed (rsort returned false).";
        
        return new self($message, self::RSORT_FAIL);
    }
    
    /**
     * @return CollectionException
     */
    public static function natsortFail(): self
    {
        $message = "The call to natsort on the collection failed (natsort returned false).";
        
        return new self($message, self::NATSORT_FAIL);
    }
    
    /**
     * @return CollectionException
     */
    public static function natcasesortFail(): self
    {
        $message = "The call to natcasesort on the collection failed (natcasesort returned false).";
        
        return new self($message, self::NATCASESORT_FAIL);
    }
    
    /**
     * @return CollectionException
     */
    public static function ksortFail(): self
    {
        $message = "The call to ksort on the collection failed (ksort returned false).";
        
        return new self($message, self::KSORT_FAIL);
    }
    
    /**
     * @return CollectionException
     */
    public static function krsortFail(): self
    {
        $message = "The call to krsort on the collection failed (krsort returned false).";
        
        return new self($message, self::KRSORT_FAIL);
    }
    
    /**
     * @return CollectionException
     */
    public static function asortFail(): self
    {
        $message = "The call to asort on the collection failed (asort returned false).";
        
        return new self($message, self::ASORT_FAIL);
    }
    
    /**
     * @return CollectionException
     */
    public static function arsortFail(): self
    {
        $message = "The call to arsort on the collection failed (arsort returned false).";
        
        return new self($message, self::ARSORT_FAIL);
    }
    
    /**
     * @param $element
     * @return string
     */
    private static function getElementType($element): string
    {
        if ($element === null) {
            return "null";
        }
        
        if (is_scalar($element) || \is_array($element)) {
            return \gettype($element);
        }
        
        if (\is_object($element)) {
            return \get_class($element);
        }
        
        return "unknown type";
    }
}
