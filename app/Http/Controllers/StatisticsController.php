<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use DB;
use Cache;
use Debugbar;

use App\Word;
use App\MeaningType;
use App\Wotd;
use App\Meaning;
use App\WordLanguage;

class StatisticsController extends Controller {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}


	/**
	 * Gather statistics information and display it on a page.
	 *
	 * @return View
	 */
	public function index()
	{
		// Get all languages
		$languages = WordLanguage::all();

		// Recently added words count
		$days = 6;
		$recent_words_data = [];
		foreach ($languages as $language) 
		{
			for ($day = $days; $day >= 0; $day--) 
			{
				$date = date('Y-m-d', strtotime('-'.$day.' day', time()));
				$wordcount = Word::where('language_id', $language->id)
					->where(DB::raw('DATE(created_at)'), $date)
					->count();

				$recent_words_data[$language->name][$date] = $wordcount;
			}
		}

		// General statistics
		$allLines = [];

		$allLine = [];

		// Line 1, All words line
		$allLine['total'] = Word::count();
		$allLine['adjectives'] = Word::whereHas('meaning.type', function ($query) {
		    $query->where('id', '=', 1);
		})->count();
		$allLine['nouns'] = Word::whereHas('meaning.type', function ($query) {
		    $query->where('id', '=', 2);
		})->count();
		$allLine['verbs'] = Word::whereHas('meaning.type', function ($query) {
		    $query->where('id', '=', 3);
		})->count();
		$allLine['adverbs'] = Word::whereHas('meaning.type', function ($query) {
		    $query->where('id', '=', 4);
		})->count();
		$allLine['others'] = Word::whereHas('meaning.type', function ($query) {
		    $query->where('id', '=', 5);
		})->count();
		$allLine['percent'] = 100;

		// Save the line
		$allLines['All'] = $allLine;

		// Loop the languages
		$languages = WordLanguage::all();
		$fields = [
			0 => 'total', 1 => 'adjectives', 2 => 'nouns', 3 => 'verbs', 4 => 'adverbs', 5 => 'others', 6 => 'percent'
		];

		foreach ($languages as $language) 
		{
			foreach ($fields as $id => $field) 
			{
				if ($field == 'total')
				{
					$allLines[$language->name][$field] = Word::where('language_id', $language->id)->count();
				}
				else if ($field == 'percent')
				{
					$allLines[$language->name][$field] = $allLines[$language->name]['total'] / $allLines['All']['total'] * 100;
					$allLines[$language->name][$field] = round($allLines[$language->name][$field], 2);
				}
				else
				{
					$allLines[$language->name][$field] = Word::whereHas('meaning.type', function ($query) use ($id) {
				    	$query->where('id', '=', $id);
					})->where('language_id', $language->id)->count();
				}
			}
		}

		$combinations = [
			'DA + PL' => [1, 4],
			'ES + PL' => [5, 4],
			'ES + DA' => [5, 1],
			'DA + PL + ES' => [5, 1, 4]
		];
		foreach ($combinations as $combination => $values) 
		{
			foreach ($fields as $id => $field) 
			{
				if ($field == 'total')
				{
					if (count($values) == 2)
					{
						$allLines[$combination][$field] = Meaning::whereHas('words', function($query1) use ($values) {
							$query1->where('language_id', (int)$values[0]);
						})->whereHas('words', function($query2) use ($values) {
							$query2->where('language_id', (int)$values[1]);
						})->count();
					}
					else if (count($values) == 3)
					{
						$allLines[$combination][$field] = Meaning::with('words')->whereHas('words', 
						function($query1) use ($values) {
							$query1->where('language_id', (int)$values[0]);
						})->whereHas('words', 
						function($query2) use ($values) {
							$query2->where('language_id', (int)$values[1]);
						})->whereHas('words', 
						function($query3) use ($values) {
							$query3->where('language_id', (int)$values[2]);
						})->count();
					}
				}
				else if ($field == 'percent')
				{
					$allLines[$combination][$field] = $allLines[$combination]['total'] / $allLines['All']['total'] * 100;
					$allLines[$combination][$field] = round($allLines[$combination][$field], 2);
				}
				else
				{
					if (count($values) == 2)
					{
						$allLines[$combination][$field] = Meaning::whereHas('words', function($query1) use ($values) {
							$query1->where('language_id', (int)$values[0]);
						})->whereHas('words', function($query2) use ($values) {
							$query2->where('language_id', (int)$values[1]);
						})->where('meaning_type_id', $id)->count();
					}
					else if (count($values) == 3)
					{
						$allLines[$combination][$field] = Meaning::whereHas('words', function($query1) use ($values) {
							$query1->where('language_id', (int)$values[0]);
						})->whereHas('words', function($query2) use ($values) {
							$query2->where('language_id', (int)$values[1]);
						})->whereHas('words', function($query2) use ($values) {
							$query2->where('language_id', (int)$values[2]);
						})->where('meaning_type_id', $id)->count();
					}
				}
			}
		}
		$statistics_data = $allLines;

		$types = MeaningType::all();

		return view('statistics.index', compact('statistics_data', 'recent_words_data', 'types'));
	}
}