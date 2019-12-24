<?php
declare(strict_types=1);

/**
 *  NOTICE OF LICENSE
 *
 *  This source file is released under commercial license by Lamia Oy.
 *
 * @copyright Copyright (c) 2019 Lamia Oy (https://lamia.fi)
 */

namespace Pando\Component;


 class PandoIterator implements \Iterator{

     /**
      * @var PandoInterface
      */
     private $pando;
     private $position;
     private $reverse;

     public function __construct(PandoInterface $pando, bool $reverse = false)
     {
         $this->pando = $pando;
         $this->reverse = $reverse;
     }

     public function current(): ?PandoInterface
     {
         return $this->pando->children()
             ? $this->pando->children()[$this->position]
             : null;
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