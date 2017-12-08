<?php

use PHPUnit\Framework\TestCase;

class PostcodeTest extends TestCase
{
	public function testApiCall()
	{
		$p = new Postcode();
		$info = $p->openApiInfo('zz1x99');
		$this->assertFalse($info);

		$info = $p->openApiInfo('w1a1aa');
		//$this->assertFalse(!$info);
		$this->assertEquals($info->region, 'London');
		$this->assertEquals($info->incode, '1AA');
		$this->assertEquals($info->admin_district, 'Westminster');
	}
}