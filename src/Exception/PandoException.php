<?php

declare(strict_types=1);

/**
 *
 * Pando 2020 — NOTICE OF MIT LICENSE
 * @copyright 2019-2020 (c) Paolo Combi (https://combi.li)
 * @link    https://github.com/PavelKingInTheNorth/pando
 * @author  Paolo Combi <paolo@combi.li>
 * @license https://github.com/PavelKingInTheNorth/pando/blob/master/LICENSE (MIT License)
 *
 */

namespace Pando\Exception;

use Pando\Component\Phrase;
use Pando\Component\Placeholder;

class PandoException extends \Exception
{
    /**
     * @var Phrase
     */
    protected $phrase;

    /**
     * @var string
     */
    protected $logMessage;

    /**
     * @param \Exception $cause
     * @param int        $code
     */
    public function __construct(Phrase $phrase, \Exception $cause = null, $code = 0)
    {
        $this->phrase = $phrase;
        parent::__construct($phrase->render(), (int) $code, $cause);
    }

    /**
     * Get the un-processed message, without the parameters filled in.
     *
     * @return string
     */
    public function getRawMessage()
    {
        return $this->phrase->getText();
    }

    /**
     * Get parameters, corresponding to placeholders in raw exception message.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->phrase->getArguments();
    }

    /**
     * Get the un-localized message, but with the parameters filled in.
     *
     * @return string
     */
    public function getLogMessage()
    {
        if (null === $this->logMessage) {
            $renderer = new Placeholder();
            $this->logMessage = $renderer->render([$this->getRawMessage()], $this->getParameters());
        }

        return $this->logMessage;
    }
}
