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

use Pando\Component\PandoData;
use Pando\Exception\InputException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Pando\Component\PandoData
 */
class PandoDataTest extends TestCase
{

    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::addData
     */
    public function testPandoDataConstructorSetDataVariableToEmptyArray()
    {
        $originalClassName = '\Pando\Component\PandoData';
        // Get mock, without the constructor being called
        $mock = $this->getMockBuilder($originalClassName)
            ->disableOriginalConstructor()
            ->getMock();
        // set expectations for constructor calls
        $mock->expects($this->once())
            ->method('addData')
            ->with(
                $this->equalTo([])
            );

        // now call the constructor
        $reflectedClass = new \ReflectionClass($originalClassName);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock);
    }

    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::addData
     */
    public function testPandoDataStartWithEmptyArray()
    {
        $data = new  PandoData();
        $this->assertEquals([], $data->getData());
    }

    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::getData
     * @covers  \Pando\Component\PandoData::setData
     * @covers  \Pando\Component\PandoData::get
     */
    public function testPandoDataSetDataFunctionOverrideTheDataArray()
    {
        $data = new  PandoData();
        $data->setData('key', 'pippo');
        $data->setData('key', 'value');
        $this->assertEquals('value', $data->getData('key'));
    }

    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::getData
     * @covers  \Pando\Component\PandoData::addData
     * @covers  \Pando\Component\PandoData::setData
     * @covers  \Pando\Component\PandoData::get

     */
    public function testPandoDataAddDataOverrideNotAssociateArray()
    {
        $array = [1,2,3,4,5];
        $data = new  PandoData();
        $data->addData($array);
        $data->addData($array);
        $this->assertEquals($array, $data->getData());
    }

    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::getData
     * @covers  \Pando\Component\PandoData::getDataByPath
     * @covers  \Pando\Component\PandoData::setData
     * @covers  \Pando\Component\PandoData::get

     */
    public function testPandoDataGetDataOutput()
    {
        $data = new  PandoData();
        $this->assertEquals([], $data->getData());
        $data->setData('0', 0);
        $this->assertEquals([0=>0], $data->getData());
        $data->setData('1', "a");
        $this->assertEquals([0=>0, 1=>"a"], $data->getData());
        $data->setData('2', new \stdClass());
        $data2 = new  PandoData();
        $data2->setData('key', 'value');
        $data->addData(['3'=>$data2]);
        $this->assertEquals('value', $data->getData('3/key'));
    }

    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::getData
     * @covers  \Pando\Component\PandoData::setData
     * @covers  \Pando\Component\PandoData::get
     */
    public function testPandoDatagetDataIndexOutput()
    {
        $data = new  PandoData();
        $data2 = new  PandoData();
        $data2->setData('key', 'value');
        $array = [
            1,
            [1,2,3,4,5],
            'pippo
             franco
             giovanna',
            $data2
        ];
        $data->addData($array);

        $this->isNull($data->getData('', 1));
        $this->assertEquals(2, $data->getData('1', '1'));
        $this->assertEquals('             giovanna', $data->getData('2', '2'));
        $this->assertEquals('value', $data->getData('3', 'key'));
        $this->assertEquals(null, $data->getData('test', 'test'));
    }

    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::setData
     * @covers  \Pando\Component\PandoData::addData
     * @covers  \Pando\Exception\InputException
     */
    public function testPandoDatasetDataException()
    {
        $this->expectException(InputException::class);
        $data = new  PandoData();
        $data->setData(1, 23);
    }

    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::unsetData
     * @covers  \Pando\Component\PandoData::addData
     * @covers  \Pando\Component\PandoData::setData
     * @covers  \Pando\Component\PandoData::get
     * @covers  \Pando\Exception\InputException
     */
    public function testPandoDataunsetOutput()
    {
        $data = new  PandoData([0=>0]);
        $data->unsetData();
        $this->assertEmpty($data->getData());
        $array = [1,2,3,4,5];
        $data->addData($array);
        $this->expectException(InputException::class);
        $data->unsetData(2);
        $data->unsetData('2');
        $this->assertNotEquals([1,2,4,5], $data->getData());
    }


    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::getDataByPath
     * @covers  \Pando\Component\PandoData::addData
     * @covers  \Pando\Component\PandoData::setData
     */
    public function testPandoDataGetDataByPath()
    {
        $data = new  PandoData();
        $array = [1, 2, 3, 4, 5];
        $data->addData($array);
        $this->assertEquals(null, $data->getDataByPath('1/2'));
        $this->assertEquals(2, $data->getDataByPath('1'));
        $data2 = new  PandoData();
        $data2->setData('key', 'value');
        $data->addData([$data2]);
        $data->getDataByPath('0/key');
        $this->assertEquals('value', $data->getDataByPath('0/key'));
    }

    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::getDataByKey
     * @covers  \Pando\Component\PandoData::addData
     * @covers  \Pando\Component\PandoData::setData
     * @covers  \Pando\Component\PandoData::get
     */
    public function testPandoDataGetDataByKey()
    {
        $data = new  PandoData();
        $array = [1,2,3,4,5];
        $data->addData($array);
        $this->assertEquals(2, $data->getDataByKey('1'));
        $this->assertEquals(null, $data->getDataByKey('6'));
    }


    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::addData
     * @covers  \Pando\Component\PandoData::setData
     * @covers  \Pando\Component\PandoData::getData
     * @covers  \Pando\Component\PandoData::unsetData
     */
    public function testPandoDataUnsetArrayOfKeys()
    {
        $data = new  PandoData();
        $array= ['Peter' => '35', 'Ben' => '37', 'Joe' => '43'];
        $data->addData($array);
        $data->unsetData(['Ben','Fly']) ;
        $this->assertEquals(['Peter' => '35', 'Joe' => '43'], $data->getData());
    }

    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::hasData
     * @covers  \Pando\Component\PandoData::addData
     * @covers  \Pando\Component\PandoData::setData
     * @covers  \Pando\Component\PandoData::unsetData

     */
    public function testPandoDataHasData()
    {
        $data = new  PandoData();
        $array = [1,2,3,4,5];
        $data->addData($array);
        $this->assertTrue($data->hasData('1'));
        $this->assertFalse($data->hasData('asdasd'));
        $this->assertTrue($data->hasData(''));
        $data->unsetData();
        $this->assertFalse($data->hasData(''));
    }

    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::addData
     * @covers  \Pando\Component\PandoData::setData
     * @covers  \Pando\Component\PandoData::isEmpty
     * @covers  \Pando\Component\PandoData::unsetData
     */
    public function testPandoDataIsEmpty()
    {
        $data = new  PandoData();
        $data->setData('key', 'value');
        $this->assertFalse($data->isEmpty());
        $data->unsetData('key');
        $this->assertTrue($data->isEmpty());
    }

    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::addData
     * @covers  \Pando\Component\PandoData::setData
     * @covers  \Pando\Component\PandoData::toString
     * @covers  \Pando\Component\PandoData::getData
     */
    public function testPandoDataToString()
    {
        $data = new  PandoData();
        $array = [1,2,3,4,5];
        $data->addData($array);
        $this->assertIsString($data->toString());
        $this->assertEquals('1, 2, 3, 4, 5', $data->toString());
    }

    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::addData
     * @covers  \Pando\Component\PandoData::setData
     * @covers  \Pando\Component\PandoData::toArray
     */
    public function testPandoDataIsArrayFunction()
    {
        $data = new  PandoData();
        $array= ['Peter' => '35', 'Ben' => '37', 'Joe' => '43'];
        $data->addData($array);
        $this->assertEquals($array, $data->toArray());
        $this->assertEquals(['Joe' => '43'], $data->toArray(['Joe']));
        $this->assertEquals(['Fly' => null], $data->toArray(['Fly']));
    }


    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::addData
     * @covers  \Pando\Component\PandoData::offsetSet
     * @covers  \Pando\Component\PandoData::getData
     */
    public function testPandoDataOffsetSet()
    {
        $data = new  PandoData();
        $data->offsetSet('Peter', 35);
        $this->assertEquals(['Peter' => '35'], $data->getData());
    }

    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::addData
     * @covers  \Pando\Component\PandoData::offsetUnset
     * @covers  \Pando\Component\PandoData::getData
     */
    public function testPandoDataOffsetUnset()
    {
        $data = new  PandoData();
        $array= ['Peter' => '35', 'Ben' => '37', 'Joe' => '43'];
        $data->addData($array);
        $data->offsetUnset('Peter');
        $this->assertEquals(['Ben' => '37', 'Joe' => '43'], $data->getData());
    }


    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::addData
     * @covers  \Pando\Component\PandoData::offsetExists
     */
    public function testPandoDataOffsetExists()
    {
        $data = new  PandoData();
        $array= ['Peter' => '35', 'Ben' => '37', 'Joe' => '43'];
        $data->addData($array);
        $this->assertTrue($data->offsetExists('Peter'));
    }

    /**
     * @covers  \Pando\Component\PandoData::__construct
     * @covers  \Pando\Component\PandoData::addData
     * @covers  \Pando\Component\PandoData::offsetGet
     */
    public function testPandoDataOffsetGet()
    {
        $data = new  PandoData();
        $array= ['Peter' => '35', 'Ben' => '37', 'Joe' => '43'];
        $data->addData($array);
        $this->assertEquals('35', $data->offsetGet('Peter'));
        $this->isNull($data->offsetGet('Fly'));
    }
}
