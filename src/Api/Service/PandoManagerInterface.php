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

use Pando\Api\Component\DataSourceInterface;
use Pando\Api\PandoInterface;

interface PandoManagerInterface
{
    /**
     * @return $this;
     */
    public function createTree(): self;

    /**
     * @return $this
     */
    public function setTree(PandoInterface $pando): self;

    /**
     * @return PandoInterface
     */
    public function getTree(): ?PandoInterface;

    /**
     * @return int|string|null
     */
    public function getReferenceKey();

    /**
     * @param string|int $referenceKey
     *
     * @return $this|PandoManagerInterface
     */
    public function setReferenceKey($referenceKey): self;

    /**
     * @param string|int|null $referenceKey
     * @param null $position
     *
     * @return $this
     */
    public function setPando(PandoInterface $pando, $referenceKey = null, $position = null): self;

    public function createPando(DataSourceInterface $dataSource): self;
}
