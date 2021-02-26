<?php


namespace Tests\Unit\Port\Export;


use App\Meaning;
use App\Port\CsvConstants;
use App\Port\Export\Services\CsvExportDataProcessorService\CsvExportDataProcessorServiceImpl;
use App\Word;
use App\WordLanguage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use MeaningTypeTableSeeder;
use Tests\AutomaticTestCase;
use UserTableSeeder;
use WordLanguageTableSeeder;

class CsvExportServiceTest extends AutomaticTestCase
{
    use RefreshDatabase;

    /**
     * @var Collection The test data we pass to the SUT.
     */
    protected $meanings;

    /**
     * @var int
     */
    protected $expected_column_count;

    /**
     * @var array
     */
    protected $expected_deleted_at_indexes;

    /**
     * @var CsvExportDataProcessorServiceImpl
     */
    protected $sut;

    /**
     * Seed database before each test.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(MeaningTypeTableSeeder::class);
        $this->seed(WordLanguageTableSeeder::class);
        $this->seed(UserTableSeeder::class);

        $this->meanings                    = null;
        $this->expected_column_count       = null;
        $this->expected_deleted_at_indexes = null;
        $this->sut                         = null;

        $this->sut = new CsvExportDataProcessorServiceImpl();
    }

    /**
     * A single meaning with one word pr language.
     */
    public function testSingleMeaningWithOneWordPrLanguage()
    {
        // Given
        $this->createMeaningMocks([
                                      new MeaningMock(['en', 'fr', 'da', 'pl'])
                                  ]);

        // When
        $result = $this->sut->process($this->meanings);

        // Then

        // Verify column header names
        foreach ($this->getTest1ExpectedColumns() as $expected_column) {
            $this->assertContains($expected_column, $result->getColumns());
        }

        // Verify number of rows
        $this->assertCount(count($this->meanings), $result->getRows());

        // Verify number of cells
        $this->assertCount($this->expected_column_count, $result->getRows()[0]);

        // Verify values in fields for meaning
        $this->assertIsString($result->getRows()[0][0]);
        $this->assertIsInt($result->getRows()[0][1]);
        $this->assertInstanceOf(Carbon::class, $result->getRows()[0][2]); // created_at
        $this->assertInstanceOf(Carbon::class, $result->getRows()[0][3]); // updated_at
        $this->assertNull($result->getRows()[0][4]);                      // deleted_at should be null

        // Verify values in fields for word (only checking the first one)
        $this->assertIsString($result->getRows()[0][5]);
        $this->assertIsString($result->getRows()[0][6]);
        $this->assertInstanceOf(Carbon::class, $result->getRows()[0][7]); // created_at
        $this->assertInstanceOf(Carbon::class, $result->getRows()[0][8]); // updated_at
        $this->assertNull($result->getRows()[0][9]);                      // deleted_at should be null
    }

    /**
     * A single meaning with several languages with several words.
     */
    public function testSingleMeaningWithSeveralLanguagesWithSeveralWords()
    {
        // Given
        $this->createMeaningMocks([
                                      new MeaningMock(['en', 'en', 'en', 'fr', 'da', 'pl', 'pl', 'pl', 'pl'])
                                  ]);

        // When
        $result = $this->sut->process($this->meanings);

        // Then

        // Verify column header names
        foreach ($this->getTest2ExpectedColumns() as $expected_column) {
            $this->assertContains($expected_column, $result->getColumns());
        }

        // Verify number of cells
        $this->assertCount($this->expected_column_count, $result->getRows()[0]);
    }

    /**
     * A meaning with some deleted words.
     */
    public function testMeaningWithDeletedWords()
    {
        // Given
        $this->createMeaningMocks([
                                      new MeaningMock(
                                          ['en', 'en', 'en', 'fr', 'da', 'pl', 'pl', 'pl', 'pl'],
                                          [1, 4]
                                      )
                                  ]);

        // When
        $result = $this->sut->process($this->meanings);

        // Then: Verify values in cells
        foreach ($this->expected_deleted_at_indexes[0] as $expected_deleted_at_index) {
            $this->assertInstanceOf(
                Carbon::class,
                $result->getRows()[0][$expected_deleted_at_index],
                'Expected cell at row index ' . $expected_deleted_at_index . ' to contain a "deleted_at" value'
            );
        }
    }

    /**
     * A deleted meaning with all deleted words.
     */
    public function testDeletedMeaningWithDeletedWords()
    {
        // Given
        $this->createMeaningMocks([new MeaningMock(['en', 'fr'], [0, 1], true)]);

        // When
        $result = $this->sut->process($this->meanings);

        // Then: Verify values in cells
        foreach ($this->expected_deleted_at_indexes[0] as $expected_deleted_at_index) {
            $this->assertInstanceOf(Carbon::class, $result->getRows()[0][$expected_deleted_at_index]); // deleted_at
        }
    }

    /**
     * More than one meaning.
     */
    public function testManyMeanings()
    {
        // Given
        $this->meanings = factory(Meaning::class, 2)->create();

        // When
        $result = $this->sut->process($this->meanings);

        // Then: Verify row count
        $this->assertCount(2, $result->getRows());
    }

    /**
     * There should be empty cells where words don't align.
     */
    public function testCellPadding()
    {
        // Given
        $this->createMeaningMocks([
                                      new MeaningMock(['en', 'en', 'fr'], [0, 1]),
                                      new MeaningMock(['en', 'fr'], [0, 1]) // Empty column
                                  ]);

        // When
        $result = $this->sut->process($this->meanings);

        // Then: Verify that there is cell padding (empty cells)
        $this->assertEmpty($result->getRows()[0][10]);
        $this->assertEmpty($result->getRows()[0][11]);
        $this->assertEmpty($result->getRows()[0][12]);
        $this->assertEmpty($result->getRows()[0][13]);
        $this->assertEmpty($result->getRows()[0][14]);
    }

    /**
     * @param MeaningMock[] $mocks
     */
    protected function createMeaningMocks(array $mocks)
    {
        $highest_number_of_words = 0;
        foreach ($mocks as $i => $mock) {
            $this->setExpectedDeletedAtColumnIndexes($mock->deleted_words_indexes, $i, $mock->meaning_deleted);

            $this->createMeaningMock($mock);

            $word_count = count($mock->word_language_shortnames);
            if ($highest_number_of_words < $word_count) {
                $highest_number_of_words = $word_count;
            }
        }

        $this->setExpectedColumnCount($highest_number_of_words);

        $this->meanings = Meaning::withTrashed()
                                 ->orderBy('id', 'desc')
                                 ->with(['words' => function ($q) {
                                     $q->withTrashed();
                                 }])
                                 ->get();
    }

    /**
     * @param MeaningMock $mock
     */
    protected function createMeaningMock(MeaningMock $mock)
    {
        $meaning = factory(Meaning::class)->create(['deleted_at' => $mock->meaning_deleted ? now() : null]);

        $index = 0;
        foreach ($mock->word_language_shortnames as $words_language_shortname) {
            $word_language_id = WordLanguage::where('short_name', $words_language_shortname)->first()->id;
            factory(Word::class)->create([
                                             'meaning_id'  => $meaning->id,
                                             'language_id' => $word_language_id,
                                             'deleted_at'  => in_array($index, $mock->deleted_words_indexes) ? now() : null
                                         ]);
            $index++;
        }
    }

    /**
     * @param array $deleted_words_indexes
     * @param int $index
     * @param bool $meaning_deleted
     */
    protected function setExpectedDeletedAtColumnIndexes(
        array $deleted_words_indexes,
        int $index,
        bool $meaning_deleted = false)
    {
        $expected_deleted_at_indexes = [];

        if ($meaning_deleted) {
            $expected_deleted_at_indexes[] = CsvConstants::getMeaningMaxHeaderColumnCount() - 1; // arrays start at 0
        }

        foreach ($deleted_words_indexes as $deleted_words_index) {
            $expected_deleted_at_indexes[] = ($deleted_words_index + 1) // 1, 2, 3 (not 0, 1, 2)
                                             * CsvConstants::getMeaningMaxHeaderColumnCount()
                                             + CsvConstants::getWordMaxColumnCount()
                                             - 1; // arrays start at 0
        }

        $this->expected_deleted_at_indexes[$index] = $expected_deleted_at_indexes;
    }

    /**
     * @param int $word_count
     */
    protected function setExpectedColumnCount(int $word_count): void
    {
        $this->expected_column_count = $this->getExpectedColumnAndCellCount($word_count);
    }

    /**
     * @param int $number_of_words
     * @return int
     */
    protected function getExpectedColumnAndCellCount(int $number_of_words): int
    {
        return CsvConstants::getMeaningMaxHeaderColumnCount()
               + $number_of_words * CsvConstants::getWordMaxColumnCount();
    }

    /**
     * @return string[]
     */
    public static function getTest1ExpectedColumns(): array
    {
        return [
            'root',
            'meaning_type_id',
            'created_at',
            'updated_at',
            'deleted_at',
            '01_en_text',
            '01_en_comment',
            '01_en_created_at',
            '01_en_updated_at',
            '01_en_deleted_at',
            '01_fr_text',
            '01_fr_comment',
            '01_fr_created_at',
            '01_fr_updated_at',
            '01_fr_deleted_at',
            '01_da_text',
            '01_da_comment',
            '01_da_created_at',
            '01_da_updated_at',
            '01_da_deleted_at',
            '01_pl_text',
            '01_pl_comment',
            '01_pl_created_at',
            '01_pl_updated_at',
            '01_pl_deleted_at',
        ];
    }

    /**
     * @return string[]
     */
    public static function getTest2ExpectedColumns(): array
    {
        return [
            'root',
            'meaning_type_id',
            'created_at',
            'updated_at',
            'deleted_at',
            '01_en_text',
            '01_en_comment',
            '01_en_created_at',
            '01_en_updated_at',
            '01_en_deleted_at',
            '02_en_text',
            '02_en_comment',
            '02_en_created_at',
            '02_en_updated_at',
            '02_en_deleted_at',
            '03_en_text',
            '03_en_comment',
            '03_en_created_at',
            '03_en_updated_at',
            '03_en_deleted_at',
            '01_fr_text',
            '01_fr_comment',
            '01_fr_created_at',
            '01_fr_updated_at',
            '01_fr_deleted_at',
            '01_da_text',
            '01_da_comment',
            '01_da_created_at',
            '01_da_updated_at',
            '01_da_deleted_at',
            '01_pl_text',
            '01_pl_comment',
            '01_pl_created_at',
            '01_pl_updated_at',
            '01_pl_deleted_at',
            '02_pl_text',
            '02_pl_comment',
            '02_pl_created_at',
            '02_pl_updated_at',
            '02_pl_deleted_at',
            '03_pl_text',
            '03_pl_comment',
            '03_pl_created_at',
            '03_pl_updated_at',
            '03_pl_deleted_at',
            '04_pl_text',
            '04_pl_comment',
            '04_pl_created_at',
            '04_pl_updated_at',
            '04_pl_deleted_at',
        ];
    }
}

class MeaningMock
{
    /**
     * @param string[] $word_language_shortnames Shortnames of the languages words this meaning has should be in. One
     * word pr. shortname is created.
     * @param int[] $deleted_words_indexes Created words at these indexes should be soft-deleted.
     * @param bool $meaning_deleted Whether or not the meaning itself should be soft-deleted.
     */
    public function __construct(array $word_language_shortnames = [], array $deleted_words_indexes = [], bool $meaning_deleted = false)
    {
        $this->word_language_shortnames = $word_language_shortnames;
        $this->deleted_words_indexes    = $deleted_words_indexes;
        $this->meaning_deleted          = $meaning_deleted;
    }

    /**
     * @var string[] Shortnames of the languages words this meaning has should be in. One word pr. shortname is created.
     */
    public $word_language_shortnames = [];

    /**
     * @var int[] Created words at these indexes should be soft-deleted.
     */
    public $deleted_words_indexes = [];

    /**
     * @var bool Whether or not the meaning itself should be soft-deleted.
     */
    public $meaning_deleted = false;
}