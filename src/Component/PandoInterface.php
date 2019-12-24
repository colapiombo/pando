<?php
declare(strict_types=1);

namespace Pando\Component;


interface PandoInterface extends \Countable, \IteratorAggregate{

    /**
     * @param PandoInterface $pando
     * @return PandoInterface
     */
    public function add(PandoInterface $psando): PandoInterface;

    /**
     * @param PandoInterface $pando
     * @return PandoInterface
     */
    public function remove(PandoInterface $pando):PandoInterface;


    /**
     * @param PandoInterface $pando
     * @return PandoInterface
     */
    public function setParent(PandoInterface $pando):PandoInterface;

}