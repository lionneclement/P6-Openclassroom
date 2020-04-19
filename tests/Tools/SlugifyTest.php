<?php

namespace App\Tests\Tools;

use App\Tools\Slugify;
use PHPUnit\Framework\TestCase;

class SlugifyTest extends TestCase
{
    public function testWordGenerator()
    {
        $slugger = new Slugify();
        $result = $slugger->sluggerLowerCase('JE suis le test');
        $this->assertEquals('je-suis-le-test', $result);
    }
    public function testSpecialWordGenerator()
    {
        $slugger = new Slugify();
        $result = $slugger->sluggerLowerCase('Wôrķšƥáçè ~~sèťtïñğš~~');
        $this->assertEquals('workspace-settings', $result);
    }
    public function testOtherLanguageWordGenerator()
    {
        $slugger = new Slugify();
        $result = $slugger->sluggerLowerCase('さよなら');
        $this->assertEquals('sayonara', $result);
    }
}