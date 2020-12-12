<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function assertArrayStructure(array $arrayStructure, $resultData)
    {
        if (is_object($resultData) && method_exists($resultData, 'toArray')) {
            $resultData = $resultData->toArray();
        } elseif (!is_array($resultData)) {
            throw new \Exception('Não é um array');
        }

        if (!array_key_exists('*', $arrayStructure)) {
            $keys = array_map(static function ($value, $key) {
                return is_array($value) ? $key : $value;
            }, $arrayStructure, array_keys($arrayStructure));

            $this->assertEquals(Arr::sortRecursive($keys), Arr::sortRecursive(array_keys($resultData)));
        }

        foreach ($arrayStructure as $key => $value) {
            if (is_array($value) && $key === '*') {
                $this->assertIsArray($resultData);

                foreach ($resultData as $resultDataItem) {
                    $this->assertArrayStructure($arrayStructure['*'], $resultDataItem);
                }
            } elseif (is_array($value)) {
                $this->assertArrayHasKey($key, $resultData);

                $this->assertArrayStructure($arrayStructure[$key], $resultData[$key]);
            } else {
                $this->assertArrayHasKey($value, $resultData);
            }
        }
    }
}
