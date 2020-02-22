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
use Pando\Component\RendererInterface;
use PHPStan\Testing\TestCase;

class PhraseTest extends TestCase
{
    /**
     * @var RendererInterface
     */
    protected $defaultRenderer;

    /**
     * @var RendererInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $rendererMock;

    /**
     * SetUp method.
     */
    protected function setUp(): void
    {
        $this->defaultRenderer = Phrase::getRenderer();
        $this->rendererMock = $this->getMockBuilder(RendererInterface::class)
            ->getMock();
    }

    /**
     * Tear down.
     */
    protected function tearDown(): void
    {
        Phrase::setRenderer($this->defaultRenderer);
    }

    /**
     * Test rendering.
     *
     * @return void
     */
    public function testRendering()
    {
        $text = 'some text';
        $arguments = ['arg1', 'arg2'];
        $result = 'rendered text';
        $phrase = new Phrase($text, $arguments);
        Phrase::setRenderer($this->rendererMock);

        $this->rendererMock->expects($this->once())
            ->method('render')
            ->with([$text], $arguments)
            ->willReturn($result);

        $this->assertSame($result, $phrase->render());
    }

    /**
     * Test defers rendering.
     *
     * @return void
     */
    public function testDefersRendering()
    {
        $this->rendererMock->expects($this->never())
            ->method('render');

        new Phrase('some text');
    }

    /**
     * Test that to string is alias to render.
     *
     * @return void
     */
    public function testThatToStringIsAliasToRender()
    {
        $text = 'some text';
        $arguments = ['arg1', 'arg2'];
        $result = 'rendered text';
        $phrase = new Phrase($text, $arguments);
        Phrase::setRenderer($this->rendererMock);

        $this->rendererMock->expects($this->once())
            ->method('render')
            ->with([$text], $arguments)
            ->willReturn($result);

        $this->assertSame($result, (string) $phrase);
    }

    /**
     * Test get text.
     *
     * @return void
     */
    public function testGetText()
    {
        $text = 'some text';
        $phrase = new Phrase($text);

        $this->assertSame($text, $phrase->getText());
    }

    /**
     * Test get arguments.
     *
     * @return void
     */
    public function testGetArguments()
    {
        $text = 'some text';
        $arguments = ['arg1', 'arg2'];
        $phrase1 = new Phrase($text);
        $phrase2 = new Phrase($text, $arguments);

        $this->assertSame([], $phrase1->getArguments());
        $this->assertSame($arguments, $phrase2->getArguments());
    }

    public function testToStringWithExceptionOnRender()
    {
        $text = 'raw text';
        $exception = new \Exception('something went wrong');
        $phrase = new Phrase($text);

        $this->rendererMock->expects($this->any())
            ->method('render')
            ->willThrowException($exception);

        $this->assertSame($text, (string) $phrase);
    }

    /**
     * Test default renderer.
     */
    public function testDefaultRenderer()
    {
        $this->assertInstanceOf(Placeholder::class, Phrase::getRenderer());
    }
}
