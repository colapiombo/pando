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

class PandoData implements PandoDataInterface
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
     * @inheritDoc
     */
    public function addData(array $arr): PandoDataInterface
    {
        foreach ($arr as $index => $value) {
            $this->setData((string) $index, $value);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setData($key, $value = null): PandoDataInterface
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
     * @inheritDoc
     */
    public function unsetData($key = null): PandoDataInterface
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
     * @inheritDoc
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
     * @inheritDoc
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
     * @inheritDoc
     */
    public function getDataByKey($key)
    {
        return $this->get($key);
    }


    /**
     * @inheritDoc
     */
    public function hasData($key = ''): bool
    {
        if (empty($key) || !\is_string($key)) {
            return !empty($this->data);
        }

        return \array_key_exists($key, $this->data);
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function toString(): string
    {
        $result = implode(', ', $this->getData());

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        if (empty($this->data)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]) || \array_key_exists($offset, $this->data);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        if (isset($this->data[$offset])) {
            return $this->data[$offset];
        }

        return null;
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
}
