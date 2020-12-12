<?php

namespace App\Actions;

use App\Exceptions\InvalidXmlFile;
use SimpleXMLElement;

class ReadXmlToArray
{
    protected string $filename;

    protected array $xmlDocumentArray = [];

    protected SimpleXMLElement $objXmlDocument;

    public function __construct(string $filename = "")
    {
        libxml_use_internal_errors(true);

        $this->filename = $filename;
    }

    /**
     * Set custom file
     *
     * @param string $filename
     *
     * @return self
     */
    public function withFile(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * Validate if referenced file can be readed
     *
     * @throws InvalidXmlFile Xml cannot be readed.
     * @return self
     */
    public function validateXmlFile(): self
    {
        $objXmlDocument = simplexml_load_file($this->getFilename());

        if ($objXmlDocument === false) {
            throw new InvalidXmlFile('Xml cannot be readed.');
        }

        $this->objXmlDocument = $objXmlDocument;
        $this->xmlDocumentArray = json_decode(json_encode($this->getObjectXmlDocument()), true);

        return $this;
    }

    /**
     * Get readed xml document object
     *
     * @return \SimpleXMLElement
     */
    public function getObjectXmlDocument(): SimpleXMLElement
    {
        return $this->objXmlDocument;
    }

    /**
     * Return normalized readed data
     *
     * @return array
     */
    public function getXmlDocumentArray(): array
    {
        return $this->xmlDocumentArray;
    }

    /**
     * Create set file
     *
     * @param string $filename
     *
     * @return self
     */
    public static function createWithFile(string $filename): self
    {
        return new self($filename);
    }
}
