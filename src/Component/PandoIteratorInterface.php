<?php
declare(strict_types=1);
/**
 *
 * @link    https://github.com/MarshallJamesRaynor/pando
 * @author  Paolo Combi <paolo@combi.li>
 * @license https://github.com/MarshallJamesRaynor/pando/blob/master/LICENSE (MIT License)
 * @package Component
 */

namespace  Pando\Component;

interface PandoIteratorInterface  extends \Iterator{

    public function current(): ?PandoInterface;

    public function next(): void;

    public function key(): int;

    public function valid(): bool;

    public function rewind(): void;

}