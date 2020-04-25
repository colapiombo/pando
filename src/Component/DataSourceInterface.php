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

namespace Pando\Component;

use Pando\Exception\InputException;

interface DataSourceInterface extends \ArrayAccess
{
    /**
     * Add data to the object.
     *
     * Retains previous data in the object.
     *
     * @return DataSourceInterface
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
     * @return DataSourceInterface
     */
    public function setData($key, $value = null): self;

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
     * @param null $key
     *
     * @throws InputException
     *
     * @return DataSourceInterface
     */
    public function unsetData($key = null): self;
}
