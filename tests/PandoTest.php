<?php
declare(strict_types=1);
/**
 *
 * @link    https://github.com/MarshallJamesRaynor/pando
 * @author  Paolo Combi <paolo@combi.li>
 * @license https://github.com/MarshallJamesRaynor/pando/blob/master/LICENSE (MIT License)
 * @package Component
 */
namespace Test;

use PHPUnit\Framework\TestCase;

use \Pando\Pando;

final class PandoTest extends TestCase
{

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
    }


    public function testCreatePando()
    {
        $pando = new Pando();
        $this->assertInstanceOf(\Pando\Component\PandoInterface::class, $pando);

        $pando = new Pando([new Pando()]);
        $this->assertInstanceOf(\Pando\Component\PandoInterface::class, $pando);
    }
}
