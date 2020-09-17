<?php

use App\Meaning;
use App\User;
use App\Word;
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
            $meaning->meaning_type_id = 2;
            $meaning->real_word_type = 200;
            $meaning->root = 'cat';
            $meaning->user_id = $user->id;
            $meaning->save();

            // Create a word
            $word = new Word;
            $word->text = 'cat';
            $word->language_id = 1; // English
            $word->meaning_id = $meaning->id;
            $word->user_id = $user->id;
            $word->save();
        }
    }
}