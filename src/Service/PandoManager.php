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

namespace Pando\Service;

use Pando\Api\Component\DataSourceInterface;
use Pando\Api\PandoInterface;
use Pando\Api\Service\PandoManagerInterface;
use Pando\Component\DataSource;
use Pando\Pando;

class PandoManager implements PandoManagerInterface
{
    /**
     * @var PandoInterface|null
     */
    private $pando;

    /**
     * @var string|int|null
     */
    private $referenceKey;

    /**
     * {@inheritdoc}
     */
    public function createTree(): PandoManagerInterface
    {
        $this->pando = new Pando();
        $data = new DataSource();
        $data->setData('root', 'root');
        $this->pando->setDataSource($data);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setTree(PandoInterface $pando): PandoManagerInterface
    {
        $this->pando = $pando;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTree(): ?PandoInterface
    {
        return $this->pando;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceKey()
    {
        return $this->referenceKey;
    }

    /**
     * {@inheritdoc}
     */
    public function setReferenceKey($referenceKey): PandoManagerInterface
    {
        $this->referenceKey = $referenceKey;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPando(PandoInterface $pando, $parent = null, $position = null): PandoManagerInterface
    {
        $this->pando->setChild($pando, $position);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createPando(DataSourceInterface $dataSource): PandoManagerInterface
    {
        $pando = new Pando($dataSource);
        $this->pando->setChildren($pando);

        return $this;
    }
}
