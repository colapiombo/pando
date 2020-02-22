<?php

declare(strict_types=1);

/**
 *
 * Pando 2020 — NOTICE OF MIT LICENSE
 * @copyright 2019-2020 (c) Paolo Combi (https://combi.li)
 * @link    https://github.com/colapiombo/pando
 * @author  Paolo Combi <paolo@combi.li>
 * @license https://github.com/colapiombo/pando/blob/master/LICENSE (MIT License)
 *
 */

namespace Pando\Component;

class PandoIterator implements PandoIteratorInterface
{
    /**
     * @var PandoInterface
     */
    private $pando;

    /**
     * @var int
     */
    private $position;

    /**
     * @var bool
     */
    private $reverse;

    /**
     * PandoIterator constructor.
     */
    public function __construct(PandoInterface $pando, bool $reverse = false)
    {
        $this->pando = $pando;
        $this->reverse = $reverse;
        $this->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function current(): ?PandoInterface
    {
        return $this->pando->children() ? $this->pando->children()[$this->position] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        $this->position += ($this->reverse ? -1 : 1);
    }

    /**
     * {@inheritdoc}
     */
    public function key(): int
    {
        return $this->position ?? 0;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return $this->pando->children() && isset($this->pando->children()[$this->position]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->position = $this->reverse ? $this->pando->count() - 1 : 0;
    }
}
