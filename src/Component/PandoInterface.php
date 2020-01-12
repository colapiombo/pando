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

use Pando\Exception\NoSuchEntityException;

interface PandoInterface extends \Countable, \IteratorAggregate
{
    /**
     * add child Pando to the current Pando.
     *
     * @param PandoInterface $pando
     *
     * @return PandoInterface
     */
    public function add(self $pando): self;

    /**
     * remove child Pando to the current Pando.
     *
     * @return PandoInterface
     */
    public function remove(self $pando): self;

    /**
     * @param PandoInterface $pando
     *
     * @return PandoInterface
     */
    public function setParent(self $pando): self;

    /**
     * @return PandoInterface|null
     */
    public function getParent(): ?self;

    /**
     * @throws NoSuchEntityException
     *
     * @return PandoInterface
     */
    public function getChildren(int $position): self;

    /**
     * Get the children.
     *
     * @return PandoInterface[]
     */
    public function children(): array;

    /**
     * get the Pando instance without root and leaf.
     *
     * @return mixed
     */
    public function getTrunk(): ?array;

    /**
     * return a Iterator class used for iterate on the Pando children.
     */
    public function getIterator(): PandoIterator;

    /**
     * return a Iterator class used for iterate on the Pando children.
     * with this specific function the iteration is reverse.
     */
    public function getReverseIterator(): PandoIterator;
}
