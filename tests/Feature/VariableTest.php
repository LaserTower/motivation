<?php


namespace Tests\Feature;


use App\Denis\Parts\ApplyVariables;
use PHPUnit\Framework\TestCase;

class VariableTest extends TestCase
{
    public function testone()
    {
        $mock = $this->getMockForTrait(ApplyVariables::class);

        $variables = [
            'name' => 'Дима',
            'hour' => 'девять'
        ];

        $res = $mock->formatVariables('приятно познакомиться {name}, я позвоню вам в {hour} часов', $variables);

        $this->assertEquals(
            'приятно познакомиться Дима, я позвоню вам в девять часов',
            $res
        );
    }
}