<?php namespace App\Http\Controllers;

use Carbon\CarbonPeriod;
use DB;

use App\Word;
use App\MeaningType;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the years we should show contribution calendars for.
     *
     * @return array Reversed years since the first word was added for this user.
     */
    public function getYearsForContributionCalendars()
    {
        $current_year = date("Y");

        // If the user does not have any words, just return the current year which will be displayed empty
        if (Word::where('user_id', auth()->user()->id)->count() < 1) {
            return [$current_year];
        }

        $first_year = Word::where('user_id', auth()->user()->id)
                          ->orderBy('created_at', 'asc')
                          ->first()
            ->created_at->year;

        $year  = $first_year;
        $years = [];
        do {
            $years[] = $year;
            $year++;
        } while ($year <= $current_year);

        return array_reverse($years); // Start from the latest year first
    }

    /**
     * Get calendar heatmap data for specific year.
     *
     * Epoch timestamp : contribution count.
     *
     * Example json output from this function:
     * {
     *   "946705035": 4,
     *   "946706692": 4,
     *   "946707210": 0,
     *   "946709243": 2,
     *   "946710714": 5,
     *   "946712907": 3,
     *   "946713183": 4,
     *   "946719001": 0,
     *   "946721450": 5,
     *   "946721875": 1
     * }
     *
     * @param $year integer in 4 numbers, i.e. 2017
     * @return string
     */
    public function getContributionCalendarData($year)
    {
        $aggregated_result = [];

        // Cal-Heatmap wants zeroes to be able to show a special color for empty days
        $year_period = CarbonPeriod::create($year . '-01-01', $year . '-12-31');
        foreach ($year_period as $date) {
            $aggregated_result[$date->timestamp] = 0;
        }

        // Gather the days where the user has contributed
        $days_with_contributions_in_words_for_year = DB::table('words')
                                                       ->where('user_id', Auth::user()->id)
                                                       ->select([DB::raw("UNIX_TIMESTAMP(created_at) as 'time'"), DB::raw("COUNT(*) as 'contributions'")])
                                                       ->where(DB::raw('YEAR(created_at)'), '=', $year)
                                                       ->groupBy([DB::raw('DAY(created_at)'), DB::raw('MONTH(created_at)'), DB::raw('YEAR(created_at)'),])
                                                       ->get();

        foreach ($days_with_contributions_in_words_for_year as $day_with_contribution) {
            $aggregated_result[$day_with_contribution->time] = $day_with_contribution->contributions;
        }

        return json_encode($aggregated_result);
    }

    /**
     * Gather statistics information and display it on a page.
     *
     * @return View
     */
    public function index()
    {
        // Get all enabled languages for this user
        $languages = Auth::user()->activeLanguages;

        // Recently added words count
        $days              = 6;
        $recent_words_data = [];
        foreach ($languages as $language) {
            for ($day = $days; $day >= 0; $day--) {
                $date       = date('Y-m-d', strtotime('-' . $day . ' day', time()));
                $word_count = Word::query()
                                  ->where('user_id', Auth::user()->id)
                                  ->where('language_id', $language->id)
                                  ->where(DB::raw('DATE(created_at)'), $date)
                                  ->count();

                $recent_words_data[$language->name][$date] = $word_count;
            }
        }

        // General statistics
        $allLines = [];

        $allLine = [];

        // Line 1, All words line
        $allLine['total']      = Word::where('user_id', Auth::user()->id)
                                     ->count();
        $allLine['adjectives'] = Word::where('user_id', Auth::user()->id)
                                     ->whereHas('meaning.type', function ($query) {
                                         $query->where('id', '=', 1);
                                     })->count();
        $allLine['nouns']      = Word::where('user_id', Auth::user()->id)->whereHas('meaning.type', function ($query) {
            $query->where('id', '=', 2);
        })->count();
        $allLine['verbs']      = Word::where('user_id', Auth::user()->id)->whereHas('meaning.type', function ($query) {
            $query->where('id', '=', 3);
        })->count();
        $allLine['adverbs']    = Word::where('user_id', Auth::user()->id)->whereHas('meaning.type', function ($query) {
            $query->where('id', '=', 4);
        })->count();
        $allLine['others']     = Word::where('user_id', Auth::user()->id)->whereHas('meaning.type', function ($query) {
            $query->where('id', '=', 5);
        })->count();
        $allLine['percent']    = 100;

        // Save the line
        $allLines['All'] = $allLine;

        // Loop the languages
        $fields = [
            0 => 'total', 1 => 'adjectives', 2 => 'nouns', 3 => 'verbs', 4 => 'adverbs', 5 => 'others', 6 => 'percent'
        ];

        foreach ($languages as $language) {
            foreach ($fields as $id => $field) {
                if ($field == 'total') {
                    $allLines[$language->name][$field] = Word::where('user_id', Auth::user()->id)->where('language_id', $language->id)->count();
                } else if ($field == 'percent') {
                    if ($allLines[$language->name]['total'] == 0) {
                        $allLines[$language->name][$field] = 0;
                    } else {
                        $allLines[$language->name][$field] = round($allLines[$language->name]['total'] / $allLines['All']['total'] * 100, 2);
                    }
                } else {
                    $allLines[$language->name][$field] = Word::where('user_id', Auth::user()->id)->whereHas('meaning.type', function ($query) use ($id) {
                        $query->where('id', '=', $id);
                    })->where('language_id', $language->id)->count();
                }
            }
        }

//        $combinations = [
//            'DA + PL' => [1, 4],
//            'ES + PL' => [5, 4],
//            'ES + DA' => [5, 1],
//            'DA + PL + ES' => [5, 1, 4]
//        ];
//        foreach ($combinations as $combination => $values) {
//            foreach ($fields as $id => $field) {
//                if ($field == 'total') {
//                    if (count($values) == 2) {
//                        $allLines[$combination][$field] = Meaning::where('user_id', Auth::user()->id)->whereHas('words', function ($query1) use ($values) {
//                            $query1->where('language_id', (int)$values[0]);
//                        })->whereHas('words', function ($query2) use ($values) {
//                            $query2->where('language_id', (int)$values[1]);
//                        })->count();
//                    } else if (count($values) == 3) {
//                        $allLines[$combination][$field] = Meaning::with('words')
//                            ->where('user_id', Auth::user()->id)
//                            ->whereHas('words', function ($query1) use ($values) {
//                                $query1->where('language_id', (int)$values[0]);
//                            })
//                            ->whereHas('words', function ($query2) use ($values) {
//                                $query2->where('language_id', (int)$values[1]);
//                            })
//                            ->whereHas('words', function ($query3) use ($values) {
//                                $query3->where('language_id', (int)$values[2]);
//                            })
//                            ->count();
//                    }
//                } else if ($field == 'percent') {
//                    if ($allLines[$combination]['total'] == 0) {
//                        $allLines[$combination][$field] = 0;
//                    } else {
//                        $allLines[$combination][$field] = round($allLines[$combination]['total'] / $allLines['All']['total'] * 100, 2);
//                    }
//                } else {
//                    if (count($values) == 2) {
//                        $allLines[$combination][$field] = Meaning::query()
//                            ->where('user_id', Auth::user()->id)
//                            ->whereHas('words', function ($query1) use ($values) {
//                                $query1->where('language_id', (int)$values[0]);
//                            })
//                            ->whereHas('words', function ($query2) use ($values) {
//                                $query2->where('language_id', (int)$values[1]);
//                            })
//                            ->where('meaning_type_id', $id)->count();
//                    } else if (count($values) == 3) {
//                        $allLines[$combination][$field] = Meaning::query()
//                            ->where('user_id', Auth::user()->id)
//                            ->whereHas('words', function ($query1) use ($values) {
//                                $query1->where('language_id', (int)$values[0]);
//                            })
//                            ->whereHas('words', function ($query2) use ($values) {
//                                $query2->where('language_id', (int)$values[1]);
//                            })
//                            ->whereHas('words', function ($query2) use ($values) {
//                                $query2->where('language_id', (int)$values[2]);
//                            })
//                            ->where('meaning_type_id', $id)->count();
//                    }
//                }
//            }
//        }
        $statistics_data = $allLines;

        $types = MeaningType::all();

        $contribution_calendar_years = $this->getYearsForContributionCalendars();

        return view('statistics.index', compact('statistics_data', 'recent_words_data', 'types', 'contribution_calendar_years'));
    }
}