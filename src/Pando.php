<?php

declare(strict_types=1);

/**
 *
 * Pando 2020 — NOTICE OF MIT LICENSE
 * @copyright 2019-2020 (c) Paolo Combi (https://combi.li)
 * @link    https://github.com/PavelKingInTheNorth/pando
 * @author  Paolo Combi <paolo@combi.li>
 * @license https://github.com/PavelKingInTheNorth/pando/blob/master/LICENSE (MIT License)
 *
 */

namespace Pando;

use Pando\Component\DataSource;
use Pando\Component\PandoInterface;
use Pando\Component\PandoIterator;
use Pando\Component\PandoLogicInterface;
use Pando\Component\Phrase;
use Pando\Exception\ArgumentNullException;
use Pando\Exception\NoSuchEntityException;

class Pando extends DataSource implements PandoInterface, PandoLogicInterface
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
     *
     * @throws Exception\InputException
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
    public function getTrunk(): ?array
    {
        return empty($data = $this->getData()) ? null : $data;
    }

    /**
     * {@inheritdoc}
     */
    public function isLeaf(): bool
    {
        return null !== $this->getParent();
    }

    /**
     * {@inheritdoc}
     */
    public function isRoot(): bool
    {
        return null === $this->getParent();
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
        return \count($this->children);
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
        }

        throw new NoSuchEntityException(new Phrase('No such entity with position', ['position' => $position]));
    }

    /**
     * {@inheritdoc}
     */
    public function search(PandoInterface $pando): array
    {
        $wanted = [];

        if (null === $pando->getTrunk()) {
            throw new ArgumentNullException(new Phrase('Pando cannot be set empty'));
        }

        if ($this->compare($pando)) {
            return $this;
        }

        foreach ($this->getIterator() as $children) {
            $discovered = $children->search($pando);
            if (\is_array($discovered)) {
                foreach ($discovered as $found) {
                    $wanted[] = $found;
                }
            } else {
                $wanted[] = $discovered;
            }
        }

        return $wanted;
    }

    /**
     * {@inheritdoc}
     */
    public function pandoDegree(): int
    {
        // TODO: Implement pandoDegree() method.
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getSibblings(PandoInterface $pando = null, bool $self = false): array
    {
        if (null === $pando && $this->isRoot()) {
            return [];
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function children(): array
    {
        return $this->children;
    }

    /**
     *  {@inheritdoc}
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

    /**
     * Function use to check if the select Pando have the same data of the other Pando.
     */
    protected function compare(PandoInterface $pando): bool
    {
        $diff = array_diff_assoc($pando->getTrunk(), $this->getTrunk());

        return empty($diff);
    }
}
