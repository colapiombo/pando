<?php
declare(strict_types=1);

namespace Pando;

use Pando\Component\DataObject;
use Pando\Component\PandoInterface;

class Pando extends DataObject implements PandoInterface
{

    private $parent;
    private $children;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->children = new \ArrayObject();

    }

    /**
     * {@inheritdoc}
     */
    public function add(PandoInterface $pando): PandoInterface
    {
        $pando->setParent($this);
        $this->children->append($pando);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(PandoInterface $pando): PandoInterface
    {
        // TODO: Implement remove() method.
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(PandoInterface $pando): PandoInterface
    {
        $this->parent = $pando;
        return $this;
    }
}