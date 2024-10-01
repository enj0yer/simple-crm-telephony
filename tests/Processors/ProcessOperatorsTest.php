<?php

namespace Enj0yer\CrmTelephony\Tests;
use Enj0yer\CrmTelephony\Processors\ProcessOperators;
use Enj0yer\CrmTelephony\TelephonyRawApiService;
use Enj0yer\CrmTelephony\TelephonyService;
use ReflectionClass;
use PHPUnit\Framework\TestCase;
use function Enj0yer\CrmTelephony\Tests\bootstrapFacades;

class ProcessOperatorsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        bootstrapFacades();
    }
    public function testGetAll()
    {
        $processor = TelephonyService::apiService()->operators();
        $reflectedProcessor = new ReflectionClass($processor);
        $this->assertTrue($reflectedProcessor->hasMethod('getAll'));
        $method = $reflectedProcessor->getMethod('getAll');
        $this->assertEquals(0, $method->getNumberOfParameters());
        $processor->getAll();
        
    }
}