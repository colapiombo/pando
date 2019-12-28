<?php
declare(strict_types=1);

/**
 *
 * @link    https://github.com/MarshallJamesRaynor/pando
 * @author  Paolo Combi <paolo@combi.li>
 * @license https://github.com/MarshallJamesRaynor/pando/blob/master/LICENSE (MIT License)
 * @package Pando
 */
namespace Pando;

use Pando\Component\PandoData;
use Pando\Component\PandoInterface;
use Pando\Component\PandoIterator;
use Pando\Component\PandoLogicInterface;
use Pando\Exception\NoSuchEntityException;

class Pando extends PandoData implements PandoInterface, PandoLogicInterface
{

    /**
     * @var PandoInterface
     */
    private $parent;

    /**
     * @var PandoInterface[]
     */
    private $children;

    /**
     * Pando constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->children = [];
    }

    /**
     * {@inheritdoc}
     */
    public function add(PandoInterface $pando): PandoInterface
    {
        $pando->setParent($this);
        $this->children[] = $pando;
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

    /**
     * {@inheritdoc}
     */
    public function isLeaf(): bool
    {
        return $this->getParent() === null;
    }
    /**
     * {@inheritdoc}
     */
    public function isRoot(): bool
    {
        return $this->getParent() === null;
    }
    /**
     * {@inheritdoc}
     */
    public function degree(): int
    {
        return $this->count();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->children);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?PandoInterface
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren(int $position): PandoInterface
    {
        if (isset($this->children[$position])) {
            return $this->children[$position];
        } else {
            throw new NoSuchEntityException("No such entity with position $position");
        }
    }


    public function pandoDegree(): int
    {
        // TODO: Implement pandoDegree() method.
        return 0;
    }


    /**
     * {@inheritdoc}
     */
    public function children():array
    {
        return $this->children;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): PandoIterator
    {
        return new PandoIterator($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getReverseIterator(): PandoIterator
    {
        return new PandoIterator($this, true);
    }
}
