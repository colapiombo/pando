<?php
declare(strict_types=1);
/**
 *
 * @link    https://github.com/MarshallJamesRaynor/pando
 * @author  Paolo Combi <paolo@combi.li>
 * @license https://github.com/MarshallJamesRaynor/pando/blob/master/LICENSE (MIT License)
 * @package Component
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

    public function __construct(PandoInterface $pando, bool $reverse = false)
    {
        $this->pando = $pando;
        $this->reverse = $reverse;
    }

    public function current(): ?PandoInterface
    {
        return $this->pando->children() ? $this->pando->children()[$this->position] : null;
    }

    public function next(): void
    {
        $this->position = $this->position + ($this->reverse ? -1 : 1);
    }

    public function key(): int
    {
        return is_null($this->position) ? 0 : $this->position;
    }

    public function valid(): bool
    {
        return $this->pando->children() && isset($this->pando->children()[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = $this->reverse ? $this->pando->count() - 1 : 0;
    }
}
