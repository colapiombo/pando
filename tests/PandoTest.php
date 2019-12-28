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

use Pando\Component\PandoIterator;
use Pando\Exception\NoSuchEntityException;
use PHPUnit\Framework\TestCase;

use \Pando\Pando;

final class PandoTest extends TestCase
{

    protected $pando;

    protected function setUp(): void
    {
        $this->pando  = new Pando();
    }

    protected function tearDown(): void
    {
        $this->pando = null;
    }

    public function testCreatePando()
    {
        $pando = new Pando();
        $this->assertInstanceOf(\Pando\Component\PandoInterface::class, $pando);

        $pando = new Pando([new Pando()]);
        $this->assertInstanceOf(\Pando\Component\PandoInterface::class, $pando);
    }

    public function testPandoShouldReturnExceptionForNotExistingPosition()
    {
        $this->expectException(NoSuchEntityException::class);
        $this->pando->getChildren(1);
    }

    public function testPandoShouldReturnPandoForExistingPosition()
    {
        $this->pando->add(new Pando());
        $result = $this->pando->getChildren(0);
        $this->assertInstanceOf(Pando::class, $result);
    }

    public function testEmptyPando()
    {
        $pando = new Pando();
        $this->assertEquals(new PandoIterator($pando), $this->pando->getIterator());
        $this->assertEquals(0, $this->pando->count());
    }
}
