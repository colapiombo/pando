<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use \Pando\Pando;

final class PandoTest extends TestCase{


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


    public function testCreatePando(){
        $pando = new Pando();
        $this->assertInstanceOf(\Pando\Component\PandoInterface::class,$pando);

        $pando = new Pando([new Pando()]);
        $this->assertInstanceOf(\Pando\Component\PandoInterface::class,$pando);
    }

    public function testSetParent(){

        $pando = new Pando(['pippo'=>'franco']);
        $pando2 = new Pando(['pippo2'=>'franco2']);
        $pando2->add($pando);

    }

}