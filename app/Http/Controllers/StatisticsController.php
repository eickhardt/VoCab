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
		$languages = WordLanguage::all();
		//dd(WordN::where(DB::raw('DATE(created_at)'), date('Y-m-d', strtotime('-1 day', time())))->count());


		// Recently added words count
		$days = 6;
		$recent_words_data = [];
		foreach ($languages as $language) 
		{
			for ($day = $days; $day >= 0; $day--) 
			{
				$date = date('Y-m-d', strtotime('-'.$day.' day', time()));
				// dd($date);
				$wordcount = Word::where('language_id', $language->id)
					->where(DB::raw('DATE(created_at)'), $date)
					->count();
				$recent_words_data[$language->name][$date] = $wordcount;
			}
		}


		// $statistics_data = [];

		// // Totals
		// $statistics_data['total']['name'] = 'All';
		// $statistics_data['total']['total_all'] = '';
		// $statistics_data['total']['total_adjectives'] = '';
		// $statistics_data['total']['total_nouns'] = '';
		// $statistics_data['total']['total_verbs'] = '';
		// $statistics_data['total']['total_other'] = '';
		// $statistics_data['total']['total_percent'] = '';

		// // English
		// $statistics_data['EN']['name'] = 'English';
		// $statistics_data['EN']['total_all'] = Word::where('EN', '!=', '')->count();
		// $statistics_data['EN']['total_adjectives'] = Word::where('EN', '!=', '')->where('type', '>', 99)->where('type', '<', 200)->count();
		// $statistics_data['EN']['total_nouns'] = Word::where('EN', '!=', '')->where('type', '>', 199)->where('type', '<', 300)->count();
		// $statistics_data['EN']['total_verbs'] = Word::where('EN', '!=', '')->where('type', '>', 299)->where('type', '<', 400)->count();
		// $statistics_data['EN']['total_other'] = Word::where('EN', '!=', '')->where('type', '>', 399)->where('type', '<', 999)->count();

		// // French
		// $statistics_data['FR']['name'] = 'French';
		// $statistics_data['FR']['total_all'] = Word::where('FR', '!=', '')->count();
		// $statistics_data['FR']['total_adjectives'] = Word::where('FR', '!=', '')->where('type', '>', 99)->where('type', '<', 200)->count();
		// $statistics_data['FR']['total_nouns'] = Word::where('FR', '!=', '')->where('type', '>', 199)->where('type', '<', 300)->count();
		// $statistics_data['FR']['total_verbs'] = Word::where('FR', '!=', '')->where('type', '>', 299)->where('type', '<', 400)->count();
		// $statistics_data['FR']['total_other'] = Word::where('FR', '!=', '')->where('type', '>', 399)->where('type', '<', 999)->count();

		// // Danish
		// $statistics_data['DK']['name'] = 'Danish';
		// $statistics_data['DK']['total_all'] = Word::where('DK', '!=', '')->count();
		// $statistics_data['DK']['total_adjectives'] = Word::where('DK', '!=', '')->where('type', '>', 99)->where('type', '<', 200)->count();
		// $statistics_data['DK']['total_nouns'] = Word::where('DK', '!=', '')->where('type', '>', 199)->where('type', '<', 300)->count();
		// $statistics_data['DK']['total_verbs'] = Word::where('DK', '!=', '')->where('type', '>', 299)->where('type', '<', 400)->count();
		// $statistics_data['DK']['total_other'] = Word::where('DK', '!=', '')->where('type', '>', 399)->where('type', '<', 999)->count();

		// // Polish
		// $statistics_data['PL']['name'] = 'Polish';
		// $statistics_data['PL']['total_all'] = Word::where('PL', '!=', '')->count();
		// $statistics_data['PL']['total_adjectives'] = Word::where('PL', '!=', '')->where('type', '>', 99)->where('type', '<', 200)->count();
		// $statistics_data['PL']['total_nouns'] = Word::where('PL', '!=', '')->where('type', '>', 199)->where('type', '<', 300)->count();
		// $statistics_data['PL']['total_verbs'] = Word::where('PL', '!=', '')->where('type', '>', 299)->where('type', '<', 400)->count();
		// $statistics_data['PL']['total_other'] = Word::where('PL', '!=', '')->where('type', '>', 399)->where('type', '<', 999)->count();

		// // Spanish
		// $statistics_data['ES']['name'] = 'Spanish';
		// $statistics_data['ES']['total_all'] = Word::where('ES', '!=', '')->count();
		// $statistics_data['ES']['total_adjectives'] = Word::where('ES', '!=', '')->where('type', '>', 99)->where('type', '<', 200)->count();
		// $statistics_data['ES']['total_nouns'] = Word::where('ES', '!=', '')->where('type', '>', 199)->where('type', '<', 300)->count();
		// $statistics_data['ES']['total_verbs'] = Word::where('ES', '!=', '')->where('type', '>', 299)->where('type', '<', 400)->count();
		// $statistics_data['ES']['total_other'] = Word::where('ES', '!=', '')->where('type', '>', 399)->where('type', '<', 999)->count();

		// // The total sum of words
		// $statistics_data['total']['total_all'] = $statistics_data['EN']['total_all'] + $statistics_data['FR']['total_all'] + $statistics_data['DK']['total_all'] + $statistics_data['PL']['total_all'] + $statistics_data['ES']['total_all'];
		// $statistics_data['total']['total_adjectives'] = $statistics_data['EN']['total_adjectives'] + $statistics_data['FR']['total_adjectives'] + $statistics_data['DK']['total_adjectives'] + $statistics_data['PL']['total_adjectives'] + $statistics_data['ES']['total_adjectives'];
		// $statistics_data['total']['total_nouns'] = $statistics_data['EN']['total_nouns'] + $statistics_data['FR']['total_nouns'] + $statistics_data['DK']['total_nouns'] + $statistics_data['PL']['total_nouns'] + $statistics_data['ES']['total_nouns'];
		// $statistics_data['total']['total_verbs'] = $statistics_data['EN']['total_verbs'] + $statistics_data['FR']['total_verbs'] + $statistics_data['DK']['total_verbs'] + $statistics_data['PL']['total_verbs'] + $statistics_data['ES']['total_verbs'];
		// $statistics_data['total']['total_other'] = $statistics_data['EN']['total_other'] + $statistics_data['FR']['total_other'] + $statistics_data['DK']['total_other'] + $statistics_data['PL']['total_other'] + $statistics_data['ES']['total_other'];

		// $statistics_data['EN']['total_percent'] = round( $statistics_data['EN']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );
		// $statistics_data['FR']['total_percent'] = round( $statistics_data['FR']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );
		// $statistics_data['DK']['total_percent'] = round( $statistics_data['DK']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );
		// $statistics_data['PL']['total_percent'] = round( $statistics_data['PL']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );
		// $statistics_data['ES']['total_percent'] = round( $statistics_data['ES']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );


		// // Combinations of languages
		// // Danish and Polish and Spanish
		// $statistics_data['DK_PL_ES']['name'] = 'DK + PL + ES';
		// $statistics_data['DK_PL_ES']['total_all'] = Word::where('DK', '!=', '')->where('PL', '!=', '')->where('ES', '!=', '')->count();
		// $statistics_data['DK_PL_ES']['total_adjectives'] = Word::where('DK', '!=', '')->where('PL', '!=', '')->where('ES', '!=', '')->where('type', '>', 99)->where('type', '<', 200)->count();
		// $statistics_data['DK_PL_ES']['total_nouns'] = Word::where('DK', '!=', '')->where('PL', '!=', '')->where('ES', '!=', '')->where('type', '>', 199)->where('type', '<', 300)->count();
		// $statistics_data['DK_PL_ES']['total_verbs'] = Word::where('DK', '!=', '')->where('PL', '!=', '')->where('ES', '!=', '')->where('type', '>', 299)->where('type', '<', 400)->count();
		// $statistics_data['DK_PL_ES']['total_other'] = Word::where('DK', '!=', '')->where('PL', '!=', '')->where('ES', '!=', '')->where('type', '>', 399)->where('type', '<', 999)->count();
		// $statistics_data['DK_PL_ES']['total_percent'] = round( $statistics_data['DK_PL_ES']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );

		// // Danish and Polish
		// $statistics_data['DK_PL']['name'] = 'DK + PL';
		// $statistics_data['DK_PL']['total_all'] = Word::where('DK', '!=', '')->where('PL', '!=', '')->count();
		// $statistics_data['DK_PL']['total_adjectives'] = Word::where('DK', '!=', '')->where('PL', '!=', '')->where('type', '>', 99)->where('type', '<', 200)->count();
		// $statistics_data['DK_PL']['total_nouns'] = Word::where('DK', '!=', '')->where('PL', '!=', '')->where('type', '>', 199)->where('type', '<', 300)->count();
		// $statistics_data['DK_PL']['total_verbs'] = Word::where('DK', '!=', '')->where('PL', '!=', '')->where('type', '>', 299)->where('type', '<', 400)->count();
		// $statistics_data['DK_PL']['total_other'] = Word::where('DK', '!=', '')->where('PL', '!=', '')->where('type', '>', 399)->where('type', '<', 999)->count();
		// $statistics_data['DK_PL']['total_percent'] = round( $statistics_data['DK_PL']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );

		// // Spanish and Polish
		// $statistics_data['ES_PL']['name'] = 'ES + PL';
		// $statistics_data['ES_PL']['total_all'] = Word::where('ES', '!=', '')->where('PL', '!=', '')->count();
		// $statistics_data['ES_PL']['total_adjectives'] = Word::where('ES', '!=', '')->where('PL', '!=', '')->where('type', '>', 99)->where('type', '<', 200)->count();
		// $statistics_data['ES_PL']['total_nouns'] = Word::where('ES', '!=', '')->where('PL', '!=', '')->where('type', '>', 199)->where('type', '<', 300)->count();
		// $statistics_data['ES_PL']['total_verbs'] = Word::where('ES', '!=', '')->where('PL', '!=', '')->where('type', '>', 299)->where('type', '<', 400)->count();
		// $statistics_data['ES_PL']['total_other'] = Word::where('ES', '!=', '')->where('PL', '!=', '')->where('type', '>', 399)->where('type', '<', 999)->count();
		// $statistics_data['ES_PL']['total_percent'] = round( $statistics_data['ES_PL']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );

		// // Danish and Spanish
		// $statistics_data['DK_ES']['name'] = 'DK + ES';
		// $statistics_data['DK_ES']['total_all'] = Word::where('DK', '!=', '')->where('ES', '!=', '')->count();
		// $statistics_data['DK_ES']['total_adjectives'] = Word::where('DK', '!=', '')->where('ES', '!=', '')->where('type', '>', 99)->where('type', '<', 200)->count();
		// $statistics_data['DK_ES']['total_nouns'] = Word::where('DK', '!=', '')->where('ES', '!=', '')->where('type', '>', 199)->where('type', '<', 300)->count();
		// $statistics_data['DK_ES']['total_verbs'] = Word::where('DK', '!=', '')->where('ES', '!=', '')->where('type', '>', 299)->where('type', '<', 400)->count();
		// $statistics_data['DK_ES']['total_other'] = Word::where('DK', '!=', '')->where('ES', '!=', '')->where('type', '>', 399)->where('type', '<', 999)->count();
		// $statistics_data['DK_ES']['total_percent'] = round( $statistics_data['DK_ES']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );


		// // Only a specific language

		// // Only Danish
		// $statistics_data['ONLY_DK']['name'] = 'Only DK';
		// $statistics_data['ONLY_DK']['total_all'] = Word::whereNull('ES')->whereNull('PL')->count();
		// $statistics_data['ONLY_DK']['total_adjectives'] = Word::whereNull('ES')->whereNull('PL')->where('type', '>', 99)->where('type', '<', 200)->count();
		// $statistics_data['ONLY_DK']['total_nouns'] = Word::whereNull('ES')->whereNull('PL')->where('type', '>', 199)->where('type', '<', 300)->count();
		// $statistics_data['ONLY_DK']['total_verbs'] = Word::whereNull('ES')->whereNull('PL')->where('type', '>', 299)->where('type', '<', 400)->count();
		// $statistics_data['ONLY_DK']['total_other'] = Word::whereNull('ES')->whereNull('PL')->where('type', '>', 399)->where('type', '<', 999)->count();
		// $statistics_data['ONLY_DK']['total_percent'] = round( $statistics_data['ONLY_DK']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );

		// // Only Spanish
		// $statistics_data['ONLY_ES']['name'] = 'Only ES';
		// $statistics_data['ONLY_ES']['total_all'] = Word::whereNull('DK')->whereNull('PL')->count();
		// $statistics_data['ONLY_ES']['total_adjectives'] = Word::whereNull('DK')->whereNull('PL')->where('type', '>', 99)->where('type', '<', 200)->count();
		// $statistics_data['ONLY_ES']['total_nouns'] = Word::whereNull('DK')->whereNull('PL')->where('type', '>', 199)->where('type', '<', 300)->count();
		// $statistics_data['ONLY_ES']['total_verbs'] = Word::whereNull('DK')->whereNull('PL')->where('type', '>', 299)->where('type', '<', 400)->count();
		// $statistics_data['ONLY_ES']['total_other'] = Word::whereNull('DK')->whereNull('PL')->where('type', '>', 399)->where('type', '<', 999)->count();
		// $statistics_data['ONLY_ES']['total_percent'] = round( $statistics_data['ONLY_ES']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );

		// // Only Polish
		// $statistics_data['ONLY_PL']['name'] = 'Only PL';
		// $statistics_data['ONLY_PL']['total_all'] = Word::whereNull('DK')->whereNull('ES')->count();
		// $statistics_data['ONLY_PL']['total_adjectives'] = Word::whereNull('DK')->whereNull('ES')->where('type', '>', 99)->where('type', '<', 200)->count();
		// $statistics_data['ONLY_PL']['total_nouns'] = Word::whereNull('DK')->whereNull('ES')->where('type', '>', 199)->where('type', '<', 300)->count();
		// $statistics_data['ONLY_PL']['total_verbs'] = Word::whereNull('DK')->whereNull('ES')->where('type', '>', 299)->where('type', '<', 400)->count();
		// $statistics_data['ONLY_PL']['total_other'] = Word::whereNull('DK')->whereNull('ES')->where('type', '>', 399)->where('type', '<', 999)->count();
		// $statistics_data['ONLY_PL']['total_percent'] = round( $statistics_data['ONLY_PL']['total_all'] / $statistics_data['total']['total_all'] * 100, 2 );


		// Recently added words count
		// $days = 6;
		// $recent_words_data = [];
		// foreach ($this->languages as $language) 
		// {
		// 	for ($day = $days; $day >= 0; $day--) 
		// 	{
		// 		$date = date('Y-m-d', strtotime('-'.$day.' day', time()));
		// 		$wordcount = Word::where($language, '!=', '')->where('TS'.$language, '=', $date)->count();
		// 		$recent_words_data[$language][$date] = $wordcount;
		// 	}
		// }

		// return view('words.statistics', compact('statistics_data', 'recent_words_data'));
		return view('statistics.index', compact('recent_words_data'));
	}
}
