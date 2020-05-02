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

use ArrayObject;
use Pando\Api\PandoInterface;
use Pando\Api\PandoLogicInterface;
use Pando\Component\DataSource;
use Pando\Component\Phrase;
use Pando\Exception\ArgumentNullException;
use Pando\Exception\NoSuchEntityException;
use Pando\Exception\PandoException;

class Pando implements PandoInterface, PandoLogicInterface
{
    /**
     * @var PandoInterface
     */
    private $parent;

    /**
     * @var ArrayObject<PandoInterface>
     */
    private $children;

    /**
     * @var DataSource|null
     */
    private $dataSource;

    /**
     * Pando constructor.
     */
    public function __construct(DataSource $dataSource = null)
    {
        $this->children = new ArrayObject();
        $this->dataSource = $dataSource;
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
    public function getParent(): ?PandoInterface
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataSource(DataSource $dataSource): PandoInterface
    {
        $this->dataSource = $dataSource;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDatasource(): ?DataSource
    {
        return $this->dataSource;
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
    public function setChild(PandoInterface $child, $index = null, PandoInterface $parent = null): PandoInterface
    {
        if ($parent !== null) {
            /** @var PandoInterface $pando */
            foreach ($this->getPando() as $pando) {
                if ($pando->compare($parent)) {
                    $pando->setChild($child, $index);
                }
            }
        } else {
            $child->setParent($this);
            if ($index === null) {
                $this->children->append($child);
            } else {
                if ($this->children->offsetExists($index)) {
                    if (\is_string($index)) {
                        $this->children->offsetSet($index, $child);
                    } else {
                        $children = $this->children->getArrayCopy();
                        \array_splice($children, $index, 0, [$child]);
                        $this->children->exchangeArray($children);
                    }
                } else {
                    $this->children->offsetSet($index, $child);
                }
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getChild($index, PandoInterface $parent = null): PandoInterface
    {
        if ($parent !== null) {
            foreach ($this->getPando() as $pando) {
                if ($pando->compare($parent)) {
                    $pando->getChild($index);
                }
            }
        } else {
            if ($this->children->offsetExists($index)) {
                return $this->children->offsetGet($index);
            }
        }
        throw new NoSuchEntityException(new Phrase('No such entity with index', ['index' => $index]));
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren(): \ArrayIterator
    {
        return $this->children->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function getPando(): \Generator
    {
        yield $this;

        /** @var PandoInterface $pando */
        foreach ($this->getChildren() as $pando) {
            yield from $pando->getPando();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove(PandoInterface $child, PandoInterface $parent = null): PandoInterface
    {
        if ($parent !== null) {
            foreach ($this->getPando() as $pando) {
                if ($pando->compare($parent)) {
                    $pando->remove($child);
                }
            }
        } else {
            $children = $this->children->getArrayCopy();
            $key = \array_search($child, $children, true);
            if ($key !== false) {
                if (\is_string($key)) {
                    $this->children->offsetUnset($key);
                } else {
                    \array_splice($children, $key, 1);
                    $this->children->exchangeArray($children);
                }
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return $this->getChildren()->count();
    }

    /**
     * {@inheritdoc}
     */
    public function search(PandoInterface $pando)
    {
        $wanted = [];

        if ($this->compare($pando)) {
            $wanted[] = $this;
        }

        /** @var PandoInterface $children */
        foreach ($this->getPando() as $children) {
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
    public function degree(): int
    {
        return $this->count();
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
     * Function use to check if the select Pando have the same data of the other Pando.
     *
     * @param PandoInterface $pando node to compare
     *
     * @return bool return TRUE if is different and FALSE if is the same
     *
     * @throws ArgumentNullException
     */
    protected function compare(PandoInterface $pando): bool
    {
        if (null === $this->getDatasource() || null === $pando->getDatasource()) {
            throw new ArgumentNullException(new Phrase('empty pando is set'));
        }

        $diff = \array_diff_assoc(
            $pando->getDatasource()->getData(),
            $this->getDatasource()->getData()
        );

        return empty($diff);
    }
}
