<?php


namespace App\Port;


use App\Port\Import\Parsers\CellParser;
use App\WordLanguage;

class CsvColumn
{
    /**
     * @var string Name of the column.
     */
    protected $name;

    /**
     * @var int Index of the column in the header.
     */
    protected $index;

    /**
     * @var CellParser Parser that can turn the column content into the necessary type of content for the DB.
     */
    protected $parser;

    /**
     * @var string Name of the column without the leading language identifier "it_text" -> "text".
     */
    protected $no_language_prefix_name;

    /**
     * @var WordLanguage | null For word columns: The language this column belongs to.
     */
    protected $language;

    public function __construct($name, $index, CellParser $parser, $no_language_prefix_name, WordLanguage $language = null)
    {
        $this->name                    = $name;
        $this->index                   = $index;
        $this->parser                  = $parser;
        $this->no_language_prefix_name = $no_language_prefix_name;
        $this->language                = $language;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return CellParser
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @return string
     */
    public function getNoLanguagePrefixName()
    {
        return $this->no_language_prefix_name;
    }

    /**
     * @return WordLanguage | null
     */
    public function getLanguage()
    {
        return $this->language;
    }
}