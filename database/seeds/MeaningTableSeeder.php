<?php

use App\Meaning;
use App\MeaningType;
use App\User;
use App\Word;
use App\WordLanguage;
use Illuminate\Database\Seeder;

class MeaningTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('meanings')->delete();

        $users = User::all();

        foreach ($users as $user) {

            // Create a meaning
            $meaning = new Meaning;

            $meaning->meaning_type_id = MeaningType::whereName('noun')->first()->id;
            $meaning->root            = 'cat';
            $meaning->user_id         = $user->id;

            $meaning->save();

            // Create a word
            $word = new Word;

            $word->text        = 'cat';
            $word->language_id = WordLanguage::whereShortName('en')->first()->id;; // English
            $word->meaning_id = $meaning->id;
            $word->user_id    = $user->id;

            $word->save();
        }
    }
}