# LanguageWebApp
This application is intended for language and code training purposes.

## Development environment:
* Install Vagrant / Virtualbox
* Clone this repo
* vagrant up from that location
* composer install inside box from that location
* Create a .env file from the .env.example file. If using homestead it can be used as-is, otherwise check db credentials.
* php artisan key:generate If you at some point get a "cipher" related error
* php artisan migrate
* php artisan db:seed
* php artisan restoreoldmw
* php artisan setwordofday
* Remember to have something in your hosts file if on windows i.e. "192.168.10.10 vocab.test"
* Go to vocab.test in browser and log in with ddeickhardt@gmail.com and the USER1_PW set in .env

## Features:
As the application expands, please update the information here if it does not reflect the current state of the project.

* Login
* User registration (currently disabled)
* Word database REST
	* Search functionality
	* Word of the day
	* Random word and different word lists
	* Backup on the server and downloadable file

## To do:
- [x] New: Add a user with only viewing privileges~~
- [x] Fix: the categories are wrong in the Statistic page. Words with 1xx are adjectives, words with 2xx are nouns (I think only those two are wrong, they are inversed, but just in case you can also check 3xx = verbs, 4xx = adverbs and 5xx = others)~~
- [x] New: An optional "advanced search", if accessed the user can specify options like which type, which language, find only matching of the search word, or start by the search word, etc~~
- [x] New: Statistics: Add a line for the count of DK + PL + ES~~

- [ ] New: A blog to post articles with grammar tips/cheat lists. Should be able to write articles using html (need to use colors mainly, and sometimes tables)
- [ ] New: Add a column "Viewed" on the words for users, that when they click it it highlights the word so they remember they have seen it. Then add a menu "Words learned" where they can see those words they have highlighted. Later, this collection of words can be used for creating flashcards, memory exercises etc
- [ ] New: Word API
- [ ] New: Statistics
- [ ] New: Statistics: allow for search options on period of times: redraw the table for a period between two dates that can be set as parameters (between 2014-01-20 and the date of today)
- [ ] New: Statistics: in the Recently added words count, add a line for the total of the 3 lines
- [ ] New: Show words in all languages as fold-out in the search table
- [ ] Fix: when I search “wpl” it doesn’t return me the words containing “wpł” (= searching for “L” doesn’t give words with “Ł “ as it should)
