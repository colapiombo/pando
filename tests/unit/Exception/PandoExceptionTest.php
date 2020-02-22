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

use Pando\Component\Phrase;
use Pando\Component\Placeholder;
use Pando\Exception\PandoException;
use PhpBench\Report\RendererInterface;
use PHPUnit\Framework\TestCase;

class PandoExceptionTest extends TestCase
{
    /** @var RendererInterface */
    private $defaultRenderer;

    /** @var string */
    private $renderedMessage;

    protected function setUp(): void
    {
        $this->defaultRenderer = Phrase::getRenderer();
        $rendererMock = $this->getMockBuilder(Placeholder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->renderedMessage = 'rendered message';
        $rendererMock->expects($this->once())
            ->method('render')
            ->willReturn($this->renderedMessage);
        Phrase::setRenderer($rendererMock);
    }

    public function tearDown(): void
    {
        Phrase::setRenderer($this->defaultRenderer);
    }

    /**
     * @param string $message
     * @param array  $params
     * @param string $expectedLogMessage
     *
     * @return void
     * @dataProvider constructorParametersDataProvider
     */
    public function testConstructor($message, $params, $expectedLogMessage)
    {
        $cause = new \Exception();
        $localizeException = new PandoException(
            new Phrase($message, $params),
            $cause
        );

        $this->assertSame(0, $localizeException->getCode());

        $this->assertSame($message, $localizeException->getRawMessage());
        $this->assertSame($this->renderedMessage, $localizeException->getMessage());
        $this->assertSame($expectedLogMessage, $localizeException->getLogMessage());

        $this->assertSame($cause, $localizeException->getPrevious());
    }

    /**
     * @return array
     */
    public function constructorParametersDataProvider()
    {
        return [
            'withNoNameParameters' => [
                'message %1 %2',
                ['parameter1',
                    'parameter2', ],
                'message parameter1 parameter2',
            ],
            'withNamedParameters'  => [
                'message %key1 %key2',
                ['key1'    => 'parameter1',
                    'key2' => 'parameter2', ],
                'message parameter1 parameter2',
            ],
            'withoutParameters'    => [
                'message',
                [],
                'message',
                'message',
            ],
        ];
    }

    /**
     * @return void
     */
    public function testGetRawMessage()
    {
        $message = 'message %1 %2';
        $params = [
            'parameter1',
            'parameter2',
        ];
        $cause = new \Exception();
        $localizeException = new PandoException(
            new Phrase($message, $params),
            $cause
        );
        $this->assertSame($message, $localizeException->getRawMessage());
    }

    /**
     * @return void
     */
    public function testGetParameters()
    {
        $message = 'message %1 %2';
        $params = [
            'parameter1',
            'parameter2',
        ];
        $cause = new \Exception();
        $localizeException = new PandoException(
            new Phrase($message, $params),
            $cause
        );

        $this->assertSame($params, $localizeException->getParameters());
    }

    /**
     * @return void
     */
    public function testGetLogMessage()
    {
        $message = 'message %1 %2';
        $params = [
            'parameter1',
            'parameter2',
        ];
        $cause = new \Exception();

        $localizeException = new PandoException(
            new Phrase($message, $params),
            $cause
        );
        $expectedLogMessage = 'message parameter1 parameter2';
        $this->assertSame($expectedLogMessage, $localizeException->getLogMessage());
    }

    public function testGetCode()
    {
        $expectedCode = 42;
        $localizedException = new PandoException(
            new Phrase('message %1', ['test']),
            new \Exception(),
            $expectedCode
        );

        $this->assertSame($expectedCode, $localizedException->getCode());
    }
}
