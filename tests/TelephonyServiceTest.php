<?php

namespace Enj0yer\CrmTelephony\Tests;

use Enj0yer\CrmTelephony\Processors\AbstractProcessor;
use Enj0yer\CrmTelephony\TelephonyRawApiService;
use Enj0yer\CrmTelephony\TelephonyService;
use PHPUnit\Framework\TestCase;
use function Enj0yer\CrmTelephony\Tests\bootstrapFacades;
use ReflectionClass;

class TelephonyServiceTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        bootstrapFacades();
    }

    public function testRawApiServiceCreate()
    { 
        $this->assertInstanceOf(TelephonyRawApiService::class, 
                                TelephonyService::apiService());
    }

    /**
     * @depends testRawApiServiceCreate
     * @return void
     */
    public function testRawApiServiceMethods()
    {
        $apiService = TelephonyService::apiService();

        $reflectedApiService = new ReflectionClass($apiService);
        foreach ($reflectedApiService->getMethods() as $method)
        {
            $processor = $method->invoke($apiService);
            $this->assertInstanceOf(AbstractProcessor::class, $processor);
            $reflectedProcessor = new ReflectionClass($processor);
            $property = $reflectedProcessor->getProperty("prefix");
            $property->setAccessible(true);
            $this->assertNotEmpty($property->getValue($processor));
        }
        
    }
}