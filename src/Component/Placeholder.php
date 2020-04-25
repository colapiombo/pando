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

class Placeholder implements RendererInterface
{
    /**
     * {@inheritdoc}
     */
    public function render(array $source, array $arguments): string
    {
        $text = \end($source);

        if ($arguments) {
            $placeholders = \array_map([$this, 'keyToPlaceholder'], \array_keys($arguments));
            $pairs = \array_combine($placeholders, $arguments);
            $text = \strtr($text, $pairs);
        }

        return $text;
    }

    /**
     * Get key to placeholder.
     *
     * @param string|int $key
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private function keyToPlaceholder($key): string
    {
        return '%' . (\is_int($key) ? (string) ($key + 1) : $key);
    }
}
