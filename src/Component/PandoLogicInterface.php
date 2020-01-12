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

use Pando\Exception\ArgumentNullException;

interface PandoLogicInterface
{
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
     * For a given node, the degree of a node is the number of its children.
     * A leaf is necessarily degree zero.
     */
    public function degree(): int;

    /**
     * The degree of a Pando is the maximum degree of any of its nodes.
     * A leaf is necessarily degree zero.
     */
    public function pandoDegree(): int;

    /**
     *  Returns an array with a node's sibling nodes, an empty array if the node has
     *  no siblings, or throws an error (if the node doesn't exist or is it empty).
     *
     *  @param  PandoInterface $pando the node for which to return the node's sibling nodes
     *  @param  bool $self         whether the node given as argument should also be present in the returned array
     *
     *  @throws ArgumentNullException
     *
     *  @return PandoInterface[]
     */
    public function getSibblings(PandoInterface $pando, bool $self = false): array;

    /**
     * Check if a Pando exist if there are multiple Pando it will return an array of that.
     *
     * @param PandoInterface $pando the Pando researched
     *
     * @throws ArgumentNullException
     *
     * @return PandoInterface|PandoInterface[]
     */
    public function search(PandoInterface $pando): array;
}
