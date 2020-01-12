<?php
declare(strict_types=1);
/**
 *
 * @link    https://github.com/MarshallJamesRaynor/pando
 * @author  Paolo Combi <paolo@combi.li>
 * @license https://github.com/MarshallJamesRaynor/pando/blob/master/LICENSE (MIT License)
 * @package Component
 */

namespace Test\Component;

use Pando\Pando;
use PHPStan\Testing\TestCase;
use \Pando\Component\PandoIterator;

/**
 * @covers \Pando\Component\PandoIterator
 * @uses \Pando\Pando
 * @uses \Pando\Component\PandoData
 */
class PandoIteratorTest extends TestCase
{


    /**
     * @covers \Pando\Component\PandoIterator
     * @covers \Pando\Pando::__construct
     * @covers \Pando\Component\PandoIterator::key
     */
    public function testPandoIteratorStartFromZero()
    {
        $iterator = new PandoIterator(new Pando());
        $this->assertEquals(0, $iterator->key());
    }

    /**
     * @covers \Pando\Component\PandoIterator
     * @covers \Pando\Pando::__construct
     * @covers \Pando\Component\PandoIterator::key
     */
    public function testPandoIteratorReturnMinusOneWithoutChildren()
    {
        $iterator = new PandoIterator(new Pando(), true);
        $this->assertEquals(-1, $iterator->key());
    }

    /**
     * @covers \Pando\Component\PandoIterator
     * @covers \Pando\Pando::__construct
     * @covers \Pando\Component\PandoIterator::key
     */
    public function testPandoIteratorReturnLastValuePosition()
    {
        $pando = new Pando();
        $pandoChildren = new Pando();
        $pandoChildren2 = new Pando();
        $pando->add($pandoChildren);
        $pando->add($pandoChildren2);
        $iterator = new PandoIterator($pando, true);
        $this->assertEquals(1, $iterator->key());
    }


    /**
     * @covers \Pando\Component\PandoIterator
     * @covers \Pando\Component\PandoData::__construct
     * @covers \Pando\Pando::__construct
     */
    public function testPandoIteratorKeyReturnTheFirstPosition()
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
    public function testPandoIteratorReturnNullIfTheCollectionIsFinish()
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
    public function testPandoIteratorCheckValidFunctionReturnFalseIfTheIteratorHasFinish()
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
    public function testRewindFunctionReturnZero()
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
