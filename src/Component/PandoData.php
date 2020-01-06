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

class PandoData implements \ArrayAccess
{
    /**
     * Object attributes.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Constructor.
     *
     * By default is looking for first argument as array and assigns it as object attributes
     * This behavior may change in child classes
     *
     * @throws InputException
     */
    public function __construct(array $data = [])
    {
        $this->addData($data);
    }

    /**
     * Add data to the object.
     *
     * Retains previous data in the object.
     *
     * @throws InputException
     *
     * @return $this
     */
    public function addData(array $arr): self
    {
        foreach ($arr as $index => $value) {
            $this->setData((string) $index, $value);
        }

        return $this;
    }

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
     * @return $this
     */
    public function setData($key, $value = null): self
    {
        if ($key === (array) $key) {
            $this->data = $key;
        } elseif (\is_string($key)) {
            $this->data[$key] = $value;
        } else {
            throw new InputException('Invalid key has provided');
        }

        return $this;
    }

    /**
     * @param null $key
     *
     * @throws InputException
     *
     * @return $this
     */
    public function unsetData($key = null): self
    {
        if (null === $key) {
            $this->setData([]);
        } elseif (\is_string($key)) {
            if (isset($this->data[$key]) || \array_key_exists($key, $this->data)) {
                unset($this->data[$key]);
            }
        } elseif ($key === (array) $key) {
            foreach ($key as $element) {
                $this->unsetData($element);
            }
        } else {
            throw new InputException('Invalid key has provided');
        }

        return $this;
    }

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
    public function getData(string $key = '', $index = null)
    {
        if ('' === $key) {
            return $this->data;
        }

        // process a/b/c key as ['a']['b']['c']
        if (false !== strpos($key, '/')) {
            $data = $this->getDataByPath($key);
        } else {
            $data = $this->get($key);
        }

        if (null !== $index) {
            if ($data === (array) $data) {
                $data = isset($data[$index]) ? $data[$index] : null;
            } elseif (\is_string($data)) {
                $data = explode(PHP_EOL, $data);
                $data = isset($data[$index]) ? $data[$index] : null;
            } elseif ($data instanceof self) {
                $data = $data->getData($index);
            } else {
                $data = null;
            }
        }

        return $data;
    }

    /**
     * Get object data by path.
     *
     * Method consider the path as chain of keys: a/b/c => ['a']['b']['c']
     *
     * @param string $path
     *
     * @return mixed
     */
    public function getDataByPath($path)
    {
        $keys = explode('/', $path);
        $data = $this->data;

        foreach ($keys as $key) {
            if ((array) $data === $data && isset($data[$key])) {
                $data = $data[$key];
            } elseif ($data instanceof self) {
                $data = $data->getDataByKey($key);
            } else {
                return null;
            }
        }

        return $data;
    }

    /**
     * Get object data by particular key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getDataByKey($key)
    {
        return $this->get($key);
    }

    /**
     * Get value from data array without parse key.
     *
     * @param string $key
     *
     * @return mixed
     */
    protected function get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * If $key is empty, checks whether there's any data in the object.
     *
     * Otherwise checks if the specified attribute is set.
     *
     * @param string $key
     */
    public function hasData($key = ''): bool
    {
        if (empty($key) || !\is_string($key)) {
            return !empty($this->data);
        }

        return \array_key_exists($key, $this->data);
    }

    /**
     * Convert array of object data with to array with keys requested in $keys array.
     *
     * @param array $keys array of required keys
     */
    public function toArray(array $keys = []): array
    {
        if (empty($keys)) {
            return $this->data;
        }

        $result = [];
        foreach ($keys as $key) {
            if (isset($this->data[$key])) {
                $result[$key] = $this->data[$key];
            } else {
                $result[$key] = null;
            }
        }

        return $result;
    }

    /**
     * Convert object data into string with predefined format
     **.
     */
    public function toString(): string
    {
        $result = implode(', ', $this->getData());

        return $result;
    }

    /**
     * Checks whether the object is empty.
     */
    public function isEmpty(): bool
    {
        if (empty($this->data)) {
            return true;
        }

        return false;
    }

    /**
     * Implementation of \ArrayAccess::offsetSet().
     *
     * @param string $offset
     * @param mixed  $value
     *
     * @see http://www.php.net/manual/en/arrayaccess.offsetset.php
     */
    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
     * Implementation of \ArrayAccess::offsetExists().
     *
     * @param string $offset
     *
     * @return bool
     *
     * @see http://www.php.net/manual/en/arrayaccess.offsetexists.php
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]) || \array_key_exists($offset, $this->data);
    }

    /**
     * Implementation of \ArrayAccess::offsetUnset().
     *
     * @param string $offset
     *
     * @see http://www.php.net/manual/en/arrayaccess.offsetunset.php
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    /**
     * Implementation of \ArrayAccess::offsetGet().
     *
     * @param string $offset
     *
     * @return mixed
     *
     * @see http://www.php.net/manual/en/arrayaccess.offsetget.php
     */
    public function offsetGet($offset)
    {
        if (isset($this->data[$offset])) {
            return $this->data[$offset];
        }

        return null;
    }
}
