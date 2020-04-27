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

namespace Pando\Api;

use Pando\Exception\ArgumentNullException;
use Pando\Exception\PandoException;

interface PandoLogicInterface
{
    /**
     * For a given node, the degree of a node is the number of its children.
     * A leaf is necessarily degree zero.
     */
    public function degree(): int;

    /**
     * The degree of a Pando is the maximum degree of any of its nodes.
     * A leaf is necessarily degree zero.
     */
    public function pandoDegree(): int;

    /**
     *  Returns an array with a Pando's sibling nodes,
     *  a Pando object if there is just one sibling and  null if the node has
     *  no siblings, or throws an error (if the node doesn't exist or is it empty).
     *
     * @param PandoInterface|null $pando       the node for which to return the node's sibling nodes
     * @param bool                $includeSelf whether the node given as argument should also
     *                                         be present in the returned array
     * @param bool|null           $ageSiblings if the value is set NULL  will be ingnored ;
     *                                         if the value is set TRUE  return the following Pando;
     *                                         if the value is set FALSE return the previous Pando,
     *
     * @throws ArgumentNullException
     * @throws PandoException
     *
     * @return PandoInterface[]
     */
    public function getSiblings(PandoInterface $pando = null, bool $includeSelf = false, $ageSiblings = null): array;

    /**
     * @param bool      $includeSelf whether the node given as argument should also be present in the returned array
     * @param bool|null $ageSiblings if the value is set NULL  will be ingnored ;
     *                               if the value is set TRUE  return the following Pando;
     *                               if the value is set FALSE return the previous Pando,     *
     *
     * @throws ArgumentNullException
     * @throws PandoException
     *
     * @return PandoInterface[]
     */
    public function getSibling(bool $includeSelf = false, $ageSiblings = null): array;

    /**
     * Returns previous node in the same level, or NULL if there's no previous node.
     *
     * @param bool $includeSelf whether the node given as argument should also be present in the returned array
     *
     * @throws ArgumentNullException
     * @throws PandoException
     *
     * @return PandoInterface[]
     */
    public function getOlderSibling(bool $includeSelf = false): array;

    /**
     * Returns following node in the same level, or NULL if there's no following node.
     *
     * @param bool $includeSelf whether the node given as argument should also be present in the returned array
     *
     * @throws ArgumentNullException
     * @throws PandoException
     *
     * @return PandoInterface[]
     */
    public function getYoungerSibling(bool $includeSelf = false): array;

    /**
     * Check if a Pando exist if there are multiple Pando it will return an array of that.
     *
     * @param PandoInterface $pando the Pando researched
     *
     * @throws ArgumentNullException
     *
     * @return PandoInterface|PandoInterface[]|null
     */
    public function search(PandoInterface $pando);
}
