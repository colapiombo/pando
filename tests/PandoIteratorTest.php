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

use Pando\Pando;
use PHPStan\Testing\TestCase;
use \Pando\Component\PandoIterator;

class PandoIteratorTest extends TestCase
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

    /**
     * @covers \Pando\Component\PandoIterator::__construct
     */
    public function testCostructPandoIteratorWithoutArgument()
    {
        $this->expectException(\ArgumentCountError::class);
        $iterator = new PandoIterator();
    }

    /**
     * @covers \Pando\Component\PandoIterator::__construct
     */
    public function testCostructPandoIteratorWithTypeError()
    {
        $this->expectException(\TypeError::class);
        $iterator = new PandoIterator('asd');
        $iterator1 = new PandoIterator(1);
        $iterator2 = new PandoIterator([]);
        $iterator3 = new PandoIterator(new \stdClass());
    }

    /**
     * @covers \Pando\Component\PandoIterator
     * @covers \Pando\Component\PandoData::__construct
     * @covers \Pando\Pando::__construct
     */
    public function testPandoIteratorKeyValue()
    {
        $iterator = new PandoIterator(new Pando());
        $iterator->next();
        $this->assertEquals(1, $iterator->key());
    }

    /**
     * @covers \Pando\Component\PandoIterator::next
     * @covers \Pando\Component\PandoIterator::current
     * @covers \Pando\Component\PandoData::__construct
     * @covers \Pando\Component\PandoIterator::__construct
     * @covers \Pando\Pando::__construct
     * @covers \Pando\Pando::children
     */
    public function testPandoIteratorNext()
    {
        $iterator = new PandoIterator(new Pando());
        $iterator->next();
        $iterator->next();
        $this->assertEquals(null, $iterator->current());
    }

    /**
     * @covers \Pando\Component\PandoIterator::next
     * @covers \Pando\Component\PandoIterator::valid
     * @covers \Pando\Component\PandoData::__construct
     * @covers \Pando\Component\PandoIterator::__construct
     * @covers \Pando\Pando::__construct
     * @covers \Pando\Pando::children
     */
    public function testValidIfItemInvalid()
    {
        $iterator = new PandoIterator(new Pando());
        $iterator->next();
        $iterator->next();
        $iterator->next();
        $this->assertEquals(false, $iterator->valid());
    }

    /**
     * @covers \Pando\Component\PandoIterator::rewind
     * @covers \Pando\Component\PandoIterator::key
     * @covers \Pando\Component\PandoData::__construct
     * @covers \Pando\Component\PandoIterator::__construct
     * @covers \Pando\Pando::__construct
     * @covers \Pando\Pando::children
     */
    public function testRewind()
    {
        $iterator = new PandoIterator(new Pando());
        $iterator->rewind();
        $this->assertEquals(0, $iterator->key());
    }

    /**
     * @covers \Pando\Component\PandoIterator::current
     * @covers \Pando\Component\PandoData::__construct
     * @covers \Pando\Component\PandoIterator::__construct
     * @covers \Pando\Pando::__construct
     * @covers \Pando\Pando::children
     * @covers \Pando\Pando::add
     * @covers \Pando\Pando::setParent
     */
    public function testCurrent()
    {
        $pando = new Pando();
        $pando->add(new Pando());
        $iterator = new PandoIterator($pando);
        $current = $iterator->current();
        $this->assertEquals($pando->children()[0], $current);
    }
}
