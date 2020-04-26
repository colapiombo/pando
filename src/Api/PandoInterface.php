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
     * @return PandoInterface
     */
    public function getChildrenByPosition(int $position): self;

    /**
     * Get the children.
     *
     * @return PandoInterface[]
     */
    public function getChildren(): array;

    /**
     * Check if the node has children, then it's a leaf.
     *
     * @return bool True if it has children, false otherwise
     */
    public function isLeaf(): bool;

    /**
     * Check if the node is the root node (Node parent is null).
     *
     * @return bool True if it's a root node, false otherwise
     */
    public function isRoot(): bool;

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
