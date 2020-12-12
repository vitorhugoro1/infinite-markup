<?php

namespace Tests\Unit;

use App\Actions\ReadXmlToArray;
use App\Exceptions\InvalidXmlFile;
use Illuminate\Foundation\Testing\WithFaker;
use SimpleXMLElement;
use Tests\TestCase;

class ReadXmlToArrayTest extends TestCase
{
    use WithFaker;

    public function test_provided_xml_is_invalid()
    {
        $filename = __DIR__ . "/../stubs/invalid-xml.xml";

        $action = new ReadXmlToArray($filename);

        $this->expectException(InvalidXmlFile::class);
        $action->validateXmlFile();
    }

    public function test_can_validate_an_xml()
    {
        $filename = __DIR__ . "/../stubs/valid-xml.xml";

        $action = new ReadXmlToArray($filename);

        $object = $action->validateXmlFile()
            ->getObjectXmlDocument();

        $this->assertInstanceOf(SimpleXMLElement::class, $object);
    }

    public function test_can_change_filename()
    {
        $filename = storage_path("app/{$this->faker->sentence}.xml");

        $action = new ReadXmlToArray($filename);

        $this->assertEquals($filename, $action->getFilename());

        $newFilename = storage_path("app/{$this->faker->sentence}.xml");

        $action->withFile($newFilename);

        $this->assertEquals($newFilename, $action->getFilename());
    }

    public function test_can_read_as_array()
    {
        $filename = __DIR__ . "/../stubs/valid-xml.xml";

        $action = new ReadXmlToArray($filename);

        $array = $action->validateXmlFile()
            ->getXmlDocumentArray();

        $this->assertNotEmpty($array);
        $this->assertArrayStructure(
            [
                'person' => [
                    'personid',
                    'personname',
                    'phones' => [
                        'phone'
                    ]
                ]
            ],
            $array
        );
    }
}
