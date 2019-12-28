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

use Pando\Exception\NoSuchEntityException;

interface PandoInterface extends \Countable, \IteratorAggregate
{

    /**
     * add child Pando to the current Pando.
     *
     * @param PandoInterface $pando
     * @return PandoInterface
     */
    public function add(PandoInterface $psando): PandoInterface;

    /**
     * remove child Pando to the current Pando.
     * @param PandoInterface $pando
     * @return PandoInterface
     */
    public function remove(PandoInterface $pando):PandoInterface;

    /**
     * @param PandoInterface $pando
     * @return PandoInterface
     */
    public function setParent(PandoInterface $pando):PandoInterface;

    /**
     * @return PandoInterface|null
     */
    public function getParent(): ?PandoInterface;

    /**
     * @param int $position
     * @return PandoInterface
     * @throws NoSuchEntityException
     */
    public function getChildren(int $position): PandoInterface;

    /**
     * Get the children.
     * @return PandoInterface[]
     */
    public function children():array;

    /**
     * return a Traversable class used for iterate on the Pando children.
     * @return PandoIterator
     */
    public function getIterator(): PandoIterator;

    /**
     * return a Traversable class used for iterate on the Pando children.
     * with this specific function the iteration is reverse
     * @return PandoIterator
     */
    public function getReverseIterator(): PandoIterator;
}
