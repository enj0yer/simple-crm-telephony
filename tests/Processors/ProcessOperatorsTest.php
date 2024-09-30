<?php

namespace Enj0yer\CrmTelephony\Tests;
use Enj0yer\CrmTelephony\Processors\ProcessOperators;
use Enj0yer\CrmTelephony\TelephonyRawApiService;
use Enj0yer\CrmTelephony\TelephonyService;
use ReflectionClass;
use PHPUnit\Framework\TestCase;

class ProcessOperatorsTest extends TestCase
{
    /**
     * @depends TelephonyServiceTest::testRawApiServiceMethods
     */
    public function testGetAll()
    {
        $processor = TelephonyService::apiService()->operators();
        $reflectedProcessor = new ReflectionClass($processor);
        $this->assertTrue($reflectedProcessor->hasMethod('getAll'));
        $method = $reflectedProcessor->getMethod('getAll');
        $this->assertEquals(0, $method->getNumberOfParameters());
    }
}