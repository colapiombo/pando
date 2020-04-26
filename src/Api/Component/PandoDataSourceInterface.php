<?php

declare(strict_types=1);

/**
 *
 * Pando NOTICE OF MIT LICENSE
 *
 * @copyright Paolo Combi (https://combi.li)
 * @link    https://github.com/colapiombo/pando
 * @author  Paolo Combi <paolo@combi.li>
 * @license https://github.com/colapiombo/pando/blob/master/LICENSE (MIT License)
 *
 *
 */

namespace Pando\Api\Component;

interface PandoDataSourceInterface extends DataSourceInterface
{
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
