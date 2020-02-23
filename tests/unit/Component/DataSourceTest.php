<?php

declare(strict_types=1);

/**
 *
 * Pando 2020 — NOTICE OF MIT LICENSE
 * @copyright 2019-2020 (c) Paolo Combi (https://combi.li)
 * @link    https://github.com/colapiombo/pando
 * @author  Paolo Combi <paolo@combi.li>
 * @license https://github.com/colapiombo/pando/blob/master/LICENSE (MIT License)
 *
 */

namespace Test;

use Pando\Component\DataSource;
use Pando\Exception\InputException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Pando\Component\DataSource
 */
class DataSourceTest extends TestCase
{
    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::addData
     */
    public function testDataSourceConstructorSetDataVariableToEmptyArray()
    {
        $originalClassName = '\Pando\Component\DataSource';
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
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::addData
     */
    public function testDataSourceStartWithEmptyArray()
    {
        $data = new DataSource();
        $this->assertSame([], $data->getData());
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::getData
     * @covers  \Pando\Component\DataSource::setData
     * @covers  \Pando\Component\DataSource::get
     */
    public function testDataSourceSetDataFunctionOverrideTheDataArray()
    {
        $data = new DataSource();
        $data->setData('key', 'pippo');
        $data->setData('key', 'value');
        $this->assertSame('value', $data->getData('key'));
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::getData
     * @covers  \Pando\Component\DataSource::addData
     * @covers  \Pando\Component\DataSource::setData
     * @covers  \Pando\Component\DataSource::get
     */
    public function testDataSourceAddDataOverrideNotAssociateArray()
    {
        $array = [1, 2, 3, 4, 5];
        $data = new DataSource();
        $data->addData($array);
        $data->addData($array);
        $this->assertSame($array, $data->getData());
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::getData
     * @covers  \Pando\Component\DataSource::getDataByPath
     * @covers  \Pando\Component\DataSource::setData
     * @covers  \Pando\Component\DataSource::get
     */
    public function testPDataSourceGetDataOutput()
    {
        $data = new DataSource();
        $this->assertSame([], $data->getData());
        $data->setData('0', 0);
        $this->assertSame([0=>0], $data->getData());
        $data->setData('1', 'a');
        $this->assertSame([0=>0, 1=>'a'], $data->getData());
        $data->setData('2', new \stdClass());
        $data2 = new DataSource();
        $data2->setData('key', 'value');
        $data->addData(['3'=>$data2]);
        $this->assertSame('value', $data->getData('3/key'));
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::getData
     * @covers  \Pando\Component\DataSource::setData
     * @covers  \Pando\Component\DataSource::get
     */
    public function testDataSourcegetDataIndexOutput()
    {
        $data = new DataSource();
        $data2 = new DataSource();
        $data2->setData('key', 'value');
        $array = [
            1,
            [1, 2, 3, 4, 5],
            'pippo
             franco
             giovanna',
            $data2,
        ];
        $data->addData($array);

        $this->isNull($data->getData('', 1));
        $this->assertSame(2, $data->getData('1', '1'));
        $this->assertSame('             giovanna', $data->getData('2', '2'));
        $this->assertSame('value', $data->getData('3', 'key'));
        $this->assertNull($data->getData('test', 'test'));
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::setData
     * @covers  \Pando\Component\DataSource::addData
     * @covers  \Pando\Exception\InputException
     * @covers  \Pando\Component\Phrase::__construct
     * @covers  \Pando\Component\Phrase::getArguments
     * @covers  \Pando\Component\Phrase::getRenderer
     * @covers  \Pando\Component\Phrase::render
     * @covers  \Pando\Component\Placeholder::keyToPlaceholder
     * @covers  \Pando\Component\Placeholder::render
     * @covers  \Pando\Exception\PandoException::__construct
     */
    public function testDataSourcesetDataException()
    {
        $this->expectException(InputException::class);
        $data = new DataSource();
        $data->setData(1, 23);
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::unsetData
     * @covers  \Pando\Component\DataSource::addData
     * @covers  \Pando\Component\DataSource::setData
     * @covers  \Pando\Component\DataSource::get
     * @covers  \Pando\Exception\InputException
     * @covers  \Pando\Component\Phrase::__construct
     * @covers  \Pando\Component\Phrase::getArguments
     * @covers  \Pando\Component\Phrase::getRenderer
     * @covers  \Pando\Component\Phrase::render
     * @covers  \Pando\Component\Placeholder::keyToPlaceholder
     * @covers  \Pando\Component\Placeholder::render
     * @covers  \Pando\Exception\PandoException::__construct
     */
    public function testDataSourceunsetOutput()
    {
        $data = new  DataSource([0=>0]);
        $data->unsetData();
        $this->assertEmpty($data->getData());
        $array = [1, 2, 3, 4, 5];
        $data->addData($array);
        $this->expectException(InputException::class);
        $data->unsetData(2);
        $data->unsetData('2');
        $this->assertNotSame([1, 2, 4, 5], $data->getData());
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::getDataByPath
     * @covers  \Pando\Component\DataSource::addData
     * @covers  \Pando\Component\DataSource::setData
     */
    public function testDataSourceGetDataByPath()
    {
        $data = new  DataSource();
        $array = [1, 2, 3, 4, 5];
        $data->addData($array);
        $this->assertNull($data->getDataByPath('1/2'));
        $this->assertSame(2, $data->getDataByPath('1'));
        $data2 = new  DataSource();
        $data2->setData('key', 'value');
        $data->addData([$data2]);
        $data->getDataByPath('0/key');
        $this->assertSame('value', $data->getDataByPath('0/key'));
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::getDataByKey
     * @covers  \Pando\Component\DataSource::addData
     * @covers  \Pando\Component\DataSource::setData
     * @covers  \Pando\Component\DataSource::get
     */
    public function testDataSourceGetDataByKey()
    {
        $data = new  DataSource();
        $array = [1, 2, 3, 4, 5];
        $data->addData($array);
        $this->assertSame(2, $data->getDataByKey('1'));
        $this->assertNull($data->getDataByKey('6'));
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::addData
     * @covers  \Pando\Component\DataSource::setData
     * @covers  \Pando\Component\DataSource::getData
     * @covers  \Pando\Component\DataSource::unsetData
     */
    public function testDataSourceUnsetArrayOfKeys()
    {
        $data = new  DataSource();
        $array = ['Peter' => '35', 'Ben' => '37', 'Joe' => '43'];
        $data->addData($array);
        $data->unsetData(['Ben', 'Fly']);
        $this->assertSame(['Peter' => '35', 'Joe' => '43'], $data->getData());
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::hasData
     * @covers  \Pando\Component\DataSource::addData
     * @covers  \Pando\Component\DataSource::setData
     * @covers  \Pando\Component\DataSource::unsetData
     */
    public function testDataSourceHasData()
    {
        $data = new  DataSource();
        $array = [1, 2, 3, 4, 5];
        $data->addData($array);
        $this->assertTrue($data->hasData('1'));
        $this->assertFalse($data->hasData('asdasd'));
        $this->assertTrue($data->hasData(''));
        $data->unsetData();
        $this->assertFalse($data->hasData(''));
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::addData
     * @covers  \Pando\Component\DataSource::setData
     * @covers  \Pando\Component\DataSource::isEmpty
     * @covers  \Pando\Component\DataSource::unsetData
     */
    public function testDataSourceIsEmpty()
    {
        $data = new  DataSource();
        $data->setData('key', 'value');
        $this->assertFalse($data->isEmpty());
        $data->unsetData('key');
        $this->assertTrue($data->isEmpty());
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::addData
     * @covers  \Pando\Component\DataSource::setData
     * @covers  \Pando\Component\DataSource::toString
     * @covers  \Pando\Component\DataSource::getData
     */
    public function testDataSourceToString()
    {
        $data = new  DataSource();
        $array = [1, 2, 3, 4, 5];
        $data->addData($array);
        $this->assertIsString($data->toString());
        $this->assertSame('1, 2, 3, 4, 5', $data->toString());
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::addData
     * @covers  \Pando\Component\DataSource::setData
     * @covers  \Pando\Component\DataSource::toArray
     */
    public function testDataSourceIsArrayFunction()
    {
        $data = new  DataSource();
        $array = ['Peter' => '35', 'Ben' => '37', 'Joe' => '43'];
        $data->addData($array);
        $this->assertSame($array, $data->toArray());
        $this->assertSame(['Joe' => '43'], $data->toArray(['Joe']));
        $this->assertSame(['Fly' => null], $data->toArray(['Fly']));
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::addData
     * @covers  \Pando\Component\DataSource::offsetSet
     * @covers  \Pando\Component\DataSource::getData
     */
    public function testDataSourceOffsetSet()
    {
        $data = new  DataSource();
        $data->offsetSet('Peter', '35');
        $this->assertSame(['Peter' => '35'], $data->getData());
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::addData
     * @covers  \Pando\Component\DataSource::offsetUnset
     * @covers  \Pando\Component\DataSource::getData
     */
    public function testDataSourceOffsetUnset()
    {
        $data = new  DataSource();
        $array = ['Peter' => '35', 'Ben' => '37', 'Joe' => '43'];
        $data->addData($array);
        $data->offsetUnset('Peter');
        $this->assertSame(['Ben' => '37', 'Joe' => '43'], $data->getData());
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::addData
     * @covers  \Pando\Component\DataSource::offsetExists
     */
    public function testDataSourceOffsetExists()
    {
        $data = new  DataSource();
        $array = ['Peter' => '35', 'Ben' => '37', 'Joe' => '43'];
        $data->addData($array);
        $this->assertTrue($data->offsetExists('Peter'));
    }

    /**
     * @covers  \Pando\Component\DataSource::__construct
     * @covers  \Pando\Component\DataSource::addData
     * @covers  \Pando\Component\DataSource::offsetGet
     */
    public function testDataSourceOffsetGet()
    {
        $data = new  DataSource();
        $array = ['Peter' => '35', 'Ben' => '37', 'Joe' => '43'];
        $data->addData($array);
        $this->assertSame('35', $data->offsetGet('Peter'));
        $this->isNull($data->offsetGet('Fly'));
    }
}
