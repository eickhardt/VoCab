# Vokapp

## Development environment
* Install Vagrant / Virtualbox
* Clone this repo
* `vagrant up` inside the project
* `vagrant ssh` inside the project
* `composer install` in the project inside the vagrant box
* Create a .env file by copying from the .env.example file. If using homestead it can be used as-is, otherwise check db credentials.
* `php artisan key:generate` If you get a "cipher" related error.
* `php artisan migrate`
* `php artisan db:seed`
* `php artisan restoreoldmw` Optional, will import an old dataset of words and meanings for a single user, user_id=2.
* Remember to have something in your `C:\Windows\System32\drivers\etc\hosts` file if youre using windows: `192.168.10.10 vokapp.test`
* Go to **vokapp.test** in browser and log in with `user1@gmail.com` and the `USER1_PW` set in `.env`, `user1pw` by default.

## Features
* User registration, login and password reset
* Statistics: recent weekly activity, github activity heatmap per year, word counts by language and total
* Word database REST
	* Search functionality
	* Word of the day
	* Get a random word & meaning and other word lists
	* Backup on the server and downloadable file (only for admins, as its for the entire database)
	* Customizable language selection

## To do
- [x] New: Add a user with only viewing privileges~~
- [x] Fix: the categories are wrong in the Statistic page. Words with 1xx are adjectives, words with 2xx are nouns (I think only those two are wrong, they are inversed, but just in case you can also check 3xx = verbs, 4xx = adverbs and 5xx = others)~~
- [x] New: An optional "advanced search", if accessed the user can specify options like which type, which language, find only matching of the search word, or start by the search word, etc~~
- [x] New: Statistics: Add a line for the count of DK + PL + ES~~
- [x] New: Show words in all languages as fold-out in the search table

- [ ] New: CSV importer
- [ ] New: A blog to post articles with grammar tips/cheat lists. Should be able to write articles using markdown (need to use colors mainly, and sometimes tables)
- [ ] New: Add a column "Viewed" on the words for users, that when they click it, it highlights the word, so they remember they have seen it. Then add a menu "Words learned" where they can see those words they have highlighted. Later, this collection of words can be used for creating flashcards, memory exercises etc
- [ ] New: Word API
- [ ] New: Statistics: Visual stuff like graphs (in addition to the )
- [ ] New: Statistics: allow for search options on period of times: redraw the table for a period between two dates that can be set as parameters (between 2014-01-20 and the date of today)
- [ ] New: Statistics: in the Recently added words count, add a line for the total of the 3 lines
- [ ] Fix: when I search "wpl" it doesn't return me the words containing "wpł" (= searching for "L" doesn't give words with "Ł" as it should)
- [ ] Automatic testing. Probably not going to happen. If I do a "version 2" of this app, it'll be a SPA developed with TDD.
