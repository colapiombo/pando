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
        $this->pando = new Pando();
    }

    protected function tearDown(): void
    {
        $this->pando = null;
    }

    /**
     * @covers  \Pando\Pando::__construct
     * @covers  \Pando\Component\PandoData::__construct
     */
    public function testCreatePando()
    {
        $pando = new Pando();
        $this->assertInstanceOf(\Pando\Component\PandoInterface::class, $pando);

        $pando = new Pando([new Pando()]);
        $this->assertInstanceOf(\Pando\Component\PandoInterface::class, $pando);
    }

    /**
     * @covers  \Pando\Pando::__construct
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoIterator::__construct
     * @covers  \Pando\Pando::getChildren
     */
    public function testPandoShouldReturnExceptionForNotExistingPosition()
    {
        $this->expectException(NoSuchEntityException::class);
        $this->pando->getChildren(1);
    }


    /**
     * @covers  \Pando\Pando::__construct
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoIterator::__construct
     * @covers  \Pando\Pando::getChildren
     * @covers  \Pando\Pando::add
     * @covers  \Pando\Pando::setParent
     */
    public function testPandoShouldReturnPandoForExistingPosition()
    {
        $this->pando->add(new Pando());
        $result = $this->pando->getChildren(0);
        $this->assertInstanceOf(Pando::class, $result);
    }


    /**
     * @covers  \Pando\Pando::__construct
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoIterator::__construct
     * @covers  \Pando\Pando::count
     * @covers  \Pando\Pando::getIterator
     */
    public function testEmptyPando()
    {
        $pando = new Pando();
        $this->assertEquals(new PandoIterator($pando), $this->pando->getIterator());
        $this->assertEquals(0, $this->pando->count());
    }
}
