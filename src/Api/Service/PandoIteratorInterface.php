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

namespace Pando\Api\Service;

use Pando\Api\PandoInterface;

interface PandoIteratorInterface extends \Iterator
{
    /**
     * Return the current element.
     */
    public function current(): ?PandoInterface;

    /**
     * Move forward to next element.
     *
     * @return void any returned value is ignored
     */
    public function next(): void;

    /**
     * Return the key of the current element.
     *
     * @return int scalar on success, or null on failure
     */
    public function key(): int;

    /**
     * Checks if current position is valid.
     *
     * @return bool The return value will be casted to boolean and then evaluated.
     *              Returns true on success or false on failure.
     */
    public function valid(): bool;

    /**
     * Rewind the Iterator to the first element.
     *
     * @return void any returned value is ignored
     */
    public function rewind(): void;
}
