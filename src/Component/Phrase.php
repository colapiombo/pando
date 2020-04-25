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

namespace Pando\Component;

use Pando\Component\Placeholder as RendererPlaceholder;

class Phrase implements \JsonSerializable
{
    /**
     * Default phrase renderer. Allows stacking renderers that "don't know about each other".
     *
     * @var RendererInterface
     */
    private static $renderer;

    /**
     * String for rendering.
     *
     * @var string
     */
    private $text;

    /**
     * Arguments for placeholder values.
     *
     * @var array
     */
    private $arguments;

    /**
     * Set default Phrase renderer.
     */
    public static function setRenderer(RendererInterface $renderer): void
    {
        self::$renderer = $renderer;
    }

    /**
     * Get default Phrase renderer.
     */
    public static function getRenderer(): RendererInterface
    {
        if (!self::$renderer) {
            self::$renderer = new RendererPlaceholder();
        }

        return self::$renderer;
    }

    /**
     * Phrase construct.
     *
     * @param string $text
     */
    public function __construct($text, array $arguments = [])
    {
        $this->text = (string) $text;
        $this->arguments = $arguments;
    }

    /**
     * Get phrase base text.
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Get phrase message arguments.
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Render phrase.
     */
    public function render(): string
    {
        try {
            return self::getRenderer()->render([$this->text], $this->getArguments());
        } catch (\Exception $e) {
            return $this->getText();
        }
    }

    /**
     * Defers rendering to the last possible moment (when converted to string).
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Specify data which should be serialized to JSON.
     */
    public function jsonSerialize(): string
    {
        return $this->render();
    }
}
