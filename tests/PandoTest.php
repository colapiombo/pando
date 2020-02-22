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

/**
 * @covers \Pando\Pando
 * @uses   \Pando\Component\PandoIterator
 * @uses   \Pando\Component\DataSource
 */
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
     * @covers  \Pando\Component\DataSource::__construct
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
     * @covers  \Pando\Component\DataSource::__construct
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
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\PandoIterator::__construct
     * @covers  \Pando\Pando::count
     * @covers  \Pando\Pando::getIterator
     */
    public function testPandoEmptyPando()
    {
        $pando = new Pando();
        $this->assertEquals(new PandoIterator($pando), $this->pando->getIterator());
        $this->assertEquals(0, $this->pando->count());
    }

    /**
     * @covers  \Pando\Pando::__construct
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\PandoIterator::__construct
     * @covers  \Pando\Pando::getIterator
     * @covers  \Pando\Pando::isRoot
     * @covers  \Pando\Pando::isLeaf
     */
    public function testPandoGetParent()
    {
        $pando = new Pando();
        $pando2 = new Pando();
        $pando->add($pando2);
        $this->assertTrue($pando->isRoot());
        $this->assertFalse($pando->isLeaf());
        foreach ($pando->getIterator() as $value) {
            $this->assertFalse($value->isRoot());
            $this->assertTrue($value->isLeaf());
        }
    }

    /**
     * @covers  \Pando\Pando::__construct
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\PandoIterator::__construct
     * @covers  \Pando\Pando::degree
     * @covers  \Pando\Pando::count
     */
    public function testPandoDegree()
    {
        $pando = new Pando();
        $pando2 = new Pando();
        $this->assertEquals(0, $pando->degree());
        $pando->add($pando2);
        $this->assertEquals(1, $pando->degree());
    }

    /**
     * @covers  \Pando\Pando::__construct
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\PandoIterator::__construct
     * @covers  \Pando\Pando::getTrunk
     */
    public function testPandoGetTrunk()
    {
        $pando = new Pando();
        $pando2 = new Pando();
        $pando2->setData('key', 'firstPando');
        $pando->add($pando2);
        $iterator = $pando->getIterator();
        $this->assertEquals(['key' =>'firstPando'], $iterator->current()->getTrunk());
        $this->assertEquals(null, $pando->getTrunk());
    }


    /**
     * @covers  \Pando\Pando::__construct
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\PandoIterator::__construct
     * @covers  \Pando\Pando::setData
     * @covers  \Pando\Pando::compare
     */
    public function testCompareFunction()
    {
        $pando = new Pando();
        $pando->setData('key', 'firstPando');
        $pando2 = new Pando();
        $pando2->setData('key', 'firstPando');

        $reflectedClass = new \ReflectionClass('\Pando\Pando');
        $method = $reflectedClass->getMethod('compare');
        $method->setAccessible(true);
        $this->assertTrue($method->invokeArgs($pando, [$pando2]));

        $pando2->setData('key', 'secondPando');
        $this->assertFalse($method->invokeArgs($pando, [$pando2]));
    }
}
