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

namespace Pando;

use Pando\Component\DataSource;
use Pando\Component\PandoInterface;
use Pando\Component\PandoIterator;
use Pando\Component\PandoLogicInterface;
use Pando\Component\Phrase;
use Pando\Exception\ArgumentNullException;
use Pando\Exception\NoSuchEntityException;
use Pando\Exception\PandoException;

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
        return \count($this->getChildren());
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
    public function getChildrenByPosition(int $position): PandoInterface
    {
        if (isset($this->children[$position])) {
            return $this->children[$position];
        }

        throw new NoSuchEntityException(new Phrase('No such entity with position', ['position' => $position]));
    }

    /**
     * {@inheritdoc}
     */
    public function search(PandoInterface $pando)
    {
        $wanted = [];
        if (null === $pando->getTrunk()) {
            throw new ArgumentNullException(new Phrase('Pando cannot be set empty'));
        }

        if ($this->compare($pando)) {
            $wanted[] = $this;
        }

        foreach ($this->getIterator() as $children) {
            $discovered = $children->search($pando);
            if ($discovered instanceof PandoInterface) {
                $wanted[] = $discovered;
            } elseif (\is_array($discovered)) {
                foreach ($discovered as $found) {
                    $wanted[] = $found;
                }
            }
        }

        if (0 === \count($wanted)) {
            return null;
        }
        if (1 === \count($wanted)) {
            return \current($wanted);
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
    public function getSiblings(PandoInterface $pando = null, bool $includeSelf = false, $ageSiblings = null): array
    {
        $siblings = [];
        if (null === $pando) {
            return $this->getSibling($includeSelf);
        }
        $discovered = $this->search($pando);
        if ($discovered instanceof PandoInterface) {
            $siblings[] = $discovered->getSibling($includeSelf, $ageSiblings);
        } elseif (\is_array($discovered)) {
            foreach ($discovered as $found) {
                $siblings[] = $found->getSibling($includeSelf, $ageSiblings);
            }
        }

        return $siblings;
    }

    /**
     * {@inheritdoc}
     */
    public function getSibling(bool $includeSelf = false, $ageSiblings = null): array
    {
        $siblings = [];
        $pos = null;
        if ($this->isRoot()) {
            throw new PandoException(new Phrase('root doesn\'t have siblings'));
        }

        foreach ($this->parent->getChildren() as $child) {
            $samePando = $this->compare($child);
            if ((null !== $ageSiblings || $includeSelf) || !$samePando) {
                $siblings[] = $child;
                if ($samePando) {
                    $pos = \count($siblings) - 1;
                }
            }
        }
        if (true === $ageSiblings) {
            $siblings = \array_slice($siblings, $includeSelf ? $pos : ++$pos);
        } elseif (false === $ageSiblings) {
            $siblings = \array_slice($siblings, 0, $includeSelf ? ++$pos : $pos);
        }

        return $siblings;
    }

    /**
     * {@inheritdoc}
     */
    public function getOlderSibling(bool $includeSelf = false): array
    {
        return $this->getSibling($includeSelf, false);
    }

    /**
     * {@inheritdoc}
     */
    public function getYoungerSibling(bool $includeSelf = false): array
    {
        return $this->getSibling($includeSelf, true);
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren(): array
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
     *
     * @param PandoInterface $pando node to compare
     *
     * @throws ArgumentNullException
     *
     * @return bool return TRUE if is different and FALSE if is the same
     */
    protected function compare(PandoInterface $pando): bool
    {
        if (null === $this->getTrunk() || null === $pando->getTrunk()) {
            throw new ArgumentNullException(new Phrase('empty pando is set'));
        }

        $diff = \array_diff_assoc($pando->getTrunk(), $this->getTrunk());

        return empty($diff);
    }
}
