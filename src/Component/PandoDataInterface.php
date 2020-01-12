<?php

declare(strict_types=1);

/**
 *
 * Pando 2020 — NOTICE OF MIT LICENSE
 * @copyright 2019-2020 (c) Paolo Combi (https://combi.li)
 * @link    https://github.com/MarshallJamesRaynor/pando
 * @author  Paolo Combi <paolo@combi.li>
 * @license https://github.com/MarshallJamesRaynor/pando/blob/master/LICENSE (MIT License)
 *
 */

namespace Pando\Component;

use Pando\Exception\InputException;

interface PandoDataInterface extends \ArrayAccess
{
    /**
     * Add data to the object.
     *
     * Retains previous data in the object.
     *
     * @throws InputException
     *
     * @return PandoDataInterface
     */
    public function addData(array $arr): self;

    /**
     * Overwrite data in the object.
     *
     * The $key parameter can be string or array.
     * If $key is string, the attribute value will be overwritten by $value
     *
     * If $key is an array, it will overwrite all the data in the object.
     *
     * @param $key
     * @param null $value
     *
     * @throws InputException
     *
     * @return PandoDataInterface
     */
    public function setData($key, $value = null): self;

    /**
     * @param null $key
     *
     * @throws InputException
     *
     * @return PandoDataInterface
     */
    public function unsetData($key = null): self;

    /**
     * Object data getter.
     *
     * If $key is not defined will return all the data as an array.
     * Otherwise it will return value of the element specified by $key.
     * It is possible to use keys like a/b/c for access nested array data
     *
     * If $index is specified it will assume that attribute data is an array
     * and retrieve corresponding member. If data is the string - it will be explode
     * by new line character and converted to array.
     *
     * @param string|int $index
     *
     * @return mixed
     */
    public function getData(string $key = '', $index = null);

    /**
     * Get object data by path.
     *
     * Method consider the path as chain of keys: a/b/c => ['a']['b']['c']
     *
     * @param string $path
     *
     * @return mixed
     */
    public function getDataByPath($path);

    /**
     * Get object data by particular key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getDataByKey($key);

    /**
     * If $key is empty, checks whether there's any data in the object.
     *
     * Otherwise checks if the specified attribute is set.
     *
     * @param string $key
     */
    public function hasData($key = ''): bool;

    /**
     * Convert array of object data with to array with keys requested in $keys array.
     *
     * @param array $keys array of required keys
     */
    public function toArray(array $keys = []): array;

    /**
     * Convert object data into string with predefined format.
     */
    public function toString(): string;

    /**
     * Checks whether the object is empty.
     */
    public function isEmpty(): bool;

    /**
     * Implementation of \ArrayAccess::offsetSet().
     *
     * @param string $offset
     * @param mixed  $value
     *
     * @see http://www.php.net/manual/en/arrayaccess.offsetset.php
     */
    public function offsetSet($offset, $value): void;

    /**
     * Implementation of \ArrayAccess::offsetExists().
     *
     * @param string $offset
     *
     * @return bool
     *
     * @see http://www.php.net/manual/en/arrayaccess.offsetexists.php
     */
    public function offsetExists($offset);

    /**
     * Implementation of \ArrayAccess::offsetUnset().
     *
     * @param string $offset
     *
     * @see http://www.php.net/manual/en/arrayaccess.offsetunset.php
     */
    public function offsetUnset($offset): void;

    /**
     * Implementation of \ArrayAccess::offsetGet().
     *
     * @param string $offset
     *
     * @return mixed
     *
     * @see http://www.php.net/manual/en/arrayaccess.offsetget.php
     */
    public function offsetGet($offset);
}
