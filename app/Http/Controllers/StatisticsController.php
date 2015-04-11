<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use DB;

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
		$types = MeaningType::all();

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

		$statistics_data = [];

		// Totals
		$statistics_data['total']['name'] = 'All';
		$statistics_data['total']['total_all'] = 0;
		foreach ($types as $type) 
		{
			$statistics_data['total']['total_'.$type->name.'s'] = 0;
		}
		$statistics_data['total']['total_percent'] = 100;

		foreach ($languages as $language) 
		{
			// First we need a name for the language we are assembling data for
			$statistics_data[$language->short_name]['name'] = $language->name;

			// Then we get all the words which belong to the language
			// $words_in_language = Word::with('meaning')->where('language_id', $language->id)->get();
			$words_in_language = Word::with('meaning')->where('language_id', $language->id)->get();

			// Here we save the total count of words in that language
			$statistics_data[$language->short_name]['total_all'] = $words_in_language->count();

			// Now we want to count how many words there are of each type
			foreach ($types as $type) 
			{
				$count = 0;
				foreach ($words_in_language as $word_in_language) 
				{
					if ($word_in_language->meaning->meaning_type_id == $type->id)
						$count++;
				}
				$statistics_data[$language->short_name]['total_'.$type->name.'s'] = $count;

				// Totals for all
				$statistics_data['total']['total_'.$type->name.'s'] = $statistics_data['total']['total_'.$type->name.'s'] + $count;
				$statistics_data['total']['total_all'] = $statistics_data['total']['total_all'] + $count;

				// $words_of_type = $words_in_language->whereHas('meaning', function($q) use($type)
				// {
				// 	$q->where('meaning_type_id', $type->id);
				// });
				// if ($type->id == 2)
				// 	dd($words_of_type);
				// $statistics_data[$language->short_name]['total_'.$type->name.'s'] = $words_of_type->count();
			}
		}

		// Calculate percentages
		foreach ($languages as $language) 
		{
			$statistics_data[$language->short_name]['total_percent'] = round($statistics_data[$language->short_name]['total_all'] / $statistics_data['total']['total_all'] * 100, 2);
		}

		// Combinations of languages
		$dk_count = WordLanguage::find(3)->words()->count();
		$pl_count = WordLanguage::find(4)->words()->count();
		$es_count = WordLanguage::find(5)->words()->count();

		// Danish and Polish and Spanish
		$statistics_data['da_pl_es']['name'] = 'DA + PL + ES';
		$statistics_data['da_pl_es']['total_all'] = $dk_count + $pl_count + $es_count;
		$combined_words = Word::with('meaning')->where('language_id', 3)->orWhere('language_id', 4)->orWhere('language_id', 5)->get();
		foreach ($types as $type) 
		{
			$count = 0;
			foreach ($combined_words as $combined_word) 
			{
				if ($combined_word->meaning->meaning_type_id == $type->id)
					$count++;
			}
			$statistics_data['da_pl_es']['total_'.$type->name.'s'] = $count;
		}
		$statistics_data['da_pl_es']['total_percent'] = round( $statistics_data['da_pl_es']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );

		// Danish and Polish
		$statistics_data['da_pl']['name'] = 'DA + PL';
		$statistics_data['da_pl']['total_all'] = $dk_count + $pl_count;
		$combined_words = Word::with('meaning')->where('language_id', 3)->orWhere('language_id', 4)->get();
		foreach ($types as $type)
		{
			$count = 0;
			foreach ($combined_words as $combined_word)
			{
				if ($combined_word->meaning->meaning_type_id == $type->id)
					$count++;
			}
			$statistics_data['da_pl']['total_'.$type->name.'s'] = $count;
		}
		$statistics_data['da_pl']['total_percent'] = round( $statistics_data['da_pl']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );

		// Spanish and Polish
		$statistics_data['es_pl']['name'] = 'ES + PL';
		$statistics_data['es_pl']['total_all'] = $es_count + $pl_count;
		$combined_words = Word::with('meaning')->where('language_id', 5)->orWhere('language_id', 4)->get();
		foreach ($types as $type)
		{
			$count = 0;
			foreach ($combined_words as $combined_word)
			{
				if ($combined_word->meaning->meaning_type_id == $type->id)
					$count++;
			}
			$statistics_data['es_pl']['total_'.$type->name.'s'] = $count;
		}
		$statistics_data['es_pl']['total_percent'] = round( $statistics_data['es_pl']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );

		// Danish and Spanish
		$statistics_data['da_es']['name'] = 'ES + DA';
		$statistics_data['da_es']['total_all'] = $es_count + $dk_count;
		$combined_words = Word::with('meaning')->where('language_id', 5)->orWhere('language_id', 3)->get();
		foreach ($types as $type)
		{
			$count = 0;
			foreach ($combined_words as $combined_word)
			{
				if ($combined_word->meaning->meaning_type_id == $type->id)
					$count++;
			}
			$statistics_data['da_es']['total_'.$type->name.'s'] = $count;
		}
		$statistics_data['da_es']['total_percent'] = round( $statistics_data['da_es']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );

		return view('statistics.index', compact('statistics_data', 'recent_words_data', 'types'));
	}
}
