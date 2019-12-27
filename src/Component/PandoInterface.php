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

interface PandoInterface extends \Countable, \IteratorAggregate
{

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
