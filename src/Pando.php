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
use Pando\Exception\NoSuchEntityException;

class Pando extends PandoData implements PandoInterface
{

    /**
     * @var PandoInterface
     */
    private $parent;

    /**
     * @var array
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

    public function getChildren($position): PandoInterface
    {
        if (isset($this->children[$position])) {
            return $this->children[$position];
        } else {
            throw new NoSuchEntityException("No such entity with position $position");
        }
    }

    public function children()
    {
        return $this->children;
    }


    public function count(): int
    {
        return count($this->children);
    }

    public function getIterator(): PandoIterator
    {
        return new PandoIterator($this);
    }

    public function getReverseIterator(): PandoIterator
    {
        return new PandoIterator($this, true);
    }
}
