<?php
declare(strict_types=1);

/**
 *  NOTICE OF LICENSE
 *
 *  This source file is released under commercial license by Lamia Oy.
 *
 * @copyright Copyright (c) 2019 Lamia Oy (https://lamia.fi)
 */


namespace Pando\Component;

interface PandoLogicInterface
{

    /**
     * Check if the node has children, then it's a leaf.
     * @return bool True if it has children, false otherwise
     */
    public function isLeaf(): bool;

    /**
     * Check if the node is the root node (Node parent is null).
     * @return bool True if it's a root node, false otherwise
     */
    public function isRoot(): bool;


    /**
     * For a given node, its number of children.
     * A leaf is necessarily degree zero.
     *
     * @return int
     */
    public function degree(): int;

    /**
     * The degree of a Pando is the degree of its root.
     * A leaf is necessarily degree zero.
     * @return int
     */
    public function pandoDegree(): int;
}
