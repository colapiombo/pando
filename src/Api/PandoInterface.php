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

namespace Pando\Api;

use Pando\Component\DataSource;
use Pando\Exception\NoSuchEntityException;

interface PandoInterface
{
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
     * @return $this
     */
    public function setDataSource(DataSource $dataSource): self;

    public function getDatasource(): ?DataSource;

    /**
     * @param PandoInterface $pando
     * @param string|int|null $index
     * @param PandoInterface|null $parent
     *
     * @return $this
     */
    public function setChild(self $pando, $index = null, self $parent = null): self;

    /**
     * @param string|int $index
     * @param PandoInterface|null $parent
     *
     * @throws NoSuchEntityException
     *
     * @return $this
     */
    public function getChild($index, self $parent = null): self;

    /**
     * return a Iterator class used for iterate on the Pando children.
     */
    public function getChildren();

    public function getPando(): \Generator;

    public function count(): int;

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
     * remove child Pando to the current Pando.
     *
     * @param PandoInterface $pando
     * @param PandoInterface|null $parent
     *
     * @return PandoInterface
     */
    public function remove(self $pando, self $parent = null): self;
}
