<?php

//include '../vendor/autoload.php';
include '../autoload.php';


use Royfj\Captcha\Builder as CaptchaBuilder;

class CaptchaBuilderTest extends PHPUnit_Framework_TestCase
{

    public function testCreateValidateCode()
    {
        $instance1 = new CaptchaBuilder();
        $this->assertNotEmpty($instance1->getCode(), 'Validate code is not empty');
        $this->assertEquals(strlen($instance1->getCode()), 4, 'Captcha code length is 4');
    }
}
