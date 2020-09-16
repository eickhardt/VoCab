<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\WordLanguage;

class WordLanguageTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('word_languages')->delete();

        WordLanguage::create([
            'name' => 'English',
            'short_name' => 'en',
            'image' => '/img/flags/en.png'
        ]);

        WordLanguage::create([
            'name' => 'French',
            'short_name' => 'fr',
            'image' => '/img/flags/fr.png'
        ]);

        WordLanguage::create([
            'name' => 'Danish',
            'short_name' => 'da',
            'image' => '/img/flags/dk.png'
        ]);

        WordLanguage::create([
            'name' => 'Polish',
            'short_name' => 'pl',
            'image' => '/img/flags/pl.png'
        ]);

        WordLanguage::create([
            'name' => 'Spanish',
            'short_name' => 'es',
            'image' => '/img/flags/es.png'
        ]);

        WordLanguage::create([
            'name' => 'Avestan',
            'short_name' => 'ae',
            'image' => '/img/flags/ae.png'
        ]);

        WordLanguage::create([
            'name' => 'Afrikaans',
            'short_name' => 'af',
            'image' => '/img/flags/af.png'
        ]);

        WordLanguage::create([
            'name' => 'Amharic',
            'short_name' => 'am',
            'image' => '/img/flags/am.png'
        ]);

        WordLanguage::create([
            'name' => 'Aragonese',
            'short_name' => 'an',
            'image' => '/img/flags/an.png'
        ]);

        WordLanguage::create([
            'name' => 'Arabic',
            'short_name' => 'ar',
            'image' => '/img/flags/ar.png'
        ]);

        WordLanguage::create([
            'name' => 'Assamese',
            'short_name' => 'as',
            'image' => '/img/flags/as.png'
        ]);

        WordLanguage::create([
            'name' => 'Azerbaijani',
            'short_name' => 'az',
            'image' => '/img/flags/az.png'
        ]);

        WordLanguage::create([
            'name' => 'Bashkir',
            'short_name' => 'ba',
            'image' => '/img/flags/ba.png'
        ]);

        WordLanguage::create([
            'name' => 'Belarusian',
            'short_name' => 'be',
            'image' => '/img/flags/be.png'
        ]);

        WordLanguage::create([
            'name' => 'Bulgarian',
            'short_name' => 'bg',
            'image' => '/img/flags/bg.png'
        ]);

        WordLanguage::create([
            'name' => 'Bihari languages',
            'short_name' => 'bh',
            'image' => '/img/flags/bh.png'
        ]);

        WordLanguage::create([
            'name' => 'Bislama',
            'short_name' => 'bi',
            'image' => '/img/flags/bi.png'
        ]);

        WordLanguage::create([
            'name' => 'Bambara',
            'short_name' => 'bm',
            'image' => '/img/flags/bm.png'
        ]);

        WordLanguage::create([
            'name' => 'Bengali',
            'short_name' => 'bn',
            'image' => '/img/flags/bn.png'
        ]);

        WordLanguage::create([
            'name' => 'Tibetan',
            'short_name' => 'bo',
            'image' => '/img/flags/bo.png'
        ]);

        WordLanguage::create([
            'name' => 'Breton',
            'short_name' => 'br',
            'image' => '/img/flags/br.png'
        ]);

        WordLanguage::create([
            'name' => 'Bosnian',
            'short_name' => 'bs',
            'image' => '/img/flags/bs.png'
        ]);

        WordLanguage::create([
            'name' => 'Catalan; Valencian',
            'short_name' => 'ca',
            'image' => '/img/flags/ca.png'
        ]);

        WordLanguage::create([
            'name' => 'Chamorro',
            'short_name' => 'ch',
            'image' => '/img/flags/ch.png'
        ]);

        WordLanguage::create([
            'name' => 'Corsican',
            'short_name' => 'co',
            'image' => '/img/flags/co.png'
        ]);

        WordLanguage::create([
            'name' => 'Cree',
            'short_name' => 'cr',
            'image' => '/img/flags/cr.png'
        ]);

        WordLanguage::create([
            'name' => 'Czech',
            'short_name' => 'cs',
            'image' => '/img/flags/cs.png'
        ]);

        WordLanguage::create([
            'name' => 'Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic',
            'short_name' => 'cu',
            'image' => '/img/flags/cu.png'
        ]);

        WordLanguage::create([
            'name' => 'Chuvash',
            'short_name' => 'cv',
            'image' => '/img/flags/cv.png'
        ]);

        WordLanguage::create([
            'name' => 'Welsh',
            'short_name' => 'cy',
            'image' => '/img/flags/cy.png'
        ]);

        WordLanguage::create([
            'name' => 'German',
            'short_name' => 'de',
            'image' => '/img/flags/de.png'
        ]);

        WordLanguage::create([
            'name' => 'Dzongkha',
            'short_name' => 'dz',
            'image' => '/img/flags/dz.png'
        ]);

        WordLanguage::create([
            'name' => 'Ewe',
            'short_name' => 'ee',
            'image' => '/img/flags/ee.png'
        ]);

        WordLanguage::create([
            'name' => 'Estonian',
            'short_name' => 'et',
            'image' => '/img/flags/et.png'
        ]);

        WordLanguage::create([
            'name' => 'Finnish',
            'short_name' => 'fi',
            'image' => '/img/flags/fi.png'
        ]);

        WordLanguage::create([
            'name' => 'Fijian',
            'short_name' => 'fj',
            'image' => '/img/flags/fj.png'
        ]);

        WordLanguage::create([
            'name' => 'Faroese',
            'short_name' => 'fo',
            'image' => '/img/flags/fo.png'
        ]);

        WordLanguage::create([
            'name' => 'Irish',
            'short_name' => 'ga',
            'image' => '/img/flags/ga.png'
        ]);

        WordLanguage::create([
            'name' => 'Gaelic; Scottish Gaelic',
            'short_name' => 'gd',
            'image' => '/img/flags/gd.png'
        ]);

        WordLanguage::create([
            'name' => 'Galician',
            'short_name' => 'gl',
            'image' => '/img/flags/gl.png'
        ]);

        WordLanguage::create([
            'name' => 'Guarani',
            'short_name' => 'gn',
            'image' => '/img/flags/gn.png'
        ]);

        WordLanguage::create([
            'name' => 'Gujarati',
            'short_name' => 'gu',
            'image' => '/img/flags/gu.png'
        ]);

        WordLanguage::create([
            'name' => 'Croatian',
            'short_name' => 'hr',
            'image' => '/img/flags/hr.png'
        ]);

        WordLanguage::create([
            'name' => 'Haitian; Haitian Creole',
            'short_name' => 'ht',
            'image' => '/img/flags/ht.png'
        ]);

        WordLanguage::create([
            'name' => 'Hungarian',
            'short_name' => 'hu',
            'image' => '/img/flags/hu.png'
        ]);

        WordLanguage::create([
            'name' => 'Indonesian',
            'short_name' => 'id',
            'image' => '/img/flags/id.png'
        ]);

        WordLanguage::create([
            'name' => 'Interlingue; Occidental',
            'short_name' => 'ie',
            'image' => '/img/flags/ie.png'
        ]);

        WordLanguage::create([
            'name' => 'Ido',
            'short_name' => 'io',
            'image' => '/img/flags/io.png'
        ]);

        WordLanguage::create([
            'name' => 'Icelandic',
            'short_name' => 'is',
            'image' => '/img/flags/is.png'
        ]);

        WordLanguage::create([
            'name' => 'Italian',
            'short_name' => 'it',
            'image' => '/img/flags/it.png'
        ]);

        WordLanguage::create([
            'name' => 'Kongo',
            'short_name' => 'kg',
            'image' => '/img/flags/kg.png'
        ]);

        WordLanguage::create([
            'name' => 'Kikuyu; Gikuyu',
            'short_name' => 'ki',
            'image' => '/img/flags/ki.png'
        ]);

        WordLanguage::create([
            'name' => 'Central Khmer',
            'short_name' => 'km',
            'image' => '/img/flags/km.png'
        ]);

        WordLanguage::create([
            'name' => 'Kannada',
            'short_name' => 'kn',
            'image' => '/img/flags/kn.png'
        ]);

        WordLanguage::create([
            'name' => 'Kanuri',
            'short_name' => 'kr',
            'image' => '/img/flags/kr.png'
        ]);

        WordLanguage::create([
            'name' => 'Cornish',
            'short_name' => 'kw',
            'image' => '/img/flags/kw.png'
        ]);

        WordLanguage::create([
            'name' => 'Kirghiz; Kyrgyz',
            'short_name' => 'ky',
            'image' => '/img/flags/ky.png'
        ]);

        WordLanguage::create([
            'name' => 'Latin',
            'short_name' => 'la',
            'image' => '/img/flags/la.png'
        ]);

        WordLanguage::create([
            'name' => 'Luxembourgish; Letzeburgesch',
            'short_name' => 'lb',
            'image' => '/img/flags/lb.png'
        ]);

        WordLanguage::create([
            'name' => 'Limburgan; Limburger; Limburgish',
            'short_name' => 'li',
            'image' => '/img/flags/li.png'
        ]);

        WordLanguage::create([
            'name' => 'Lithuanian',
            'short_name' => 'lt',
            'image' => '/img/flags/lt.png'
        ]);

        WordLanguage::create([
            'name' => 'Luba-Katanga',
            'short_name' => 'lu',
            'image' => '/img/flags/lu.png'
        ]);

        WordLanguage::create([
            'name' => 'Latvian',
            'short_name' => 'lv',
            'image' => '/img/flags/lv.png'
        ]);

        WordLanguage::create([
            'name' => 'Malagasy',
            'short_name' => 'mg',
            'image' => '/img/flags/mg.png'
        ]);

        WordLanguage::create([
            'name' => 'Marshallese',
            'short_name' => 'mh',
            'image' => '/img/flags/mh.png'
        ]);

        WordLanguage::create([
            'name' => 'Macedonian',
            'short_name' => 'mk',
            'image' => '/img/flags/mk.png'
        ]);

        WordLanguage::create([
            'name' => 'Malayalam',
            'short_name' => 'ml',
            'image' => '/img/flags/ml.png'
        ]);

        WordLanguage::create([
            'name' => 'Mongolian',
            'short_name' => 'mn',
            'image' => '/img/flags/mn.png'
        ]);

        WordLanguage::create([
            'name' => 'Marathi',
            'short_name' => 'mr',
            'image' => '/img/flags/mr.png'
        ]);

        WordLanguage::create([
            'name' => 'Malay',
            'short_name' => 'ms',
            'image' => '/img/flags/ms.png'
        ]);

        WordLanguage::create([
            'name' => 'Maltese',
            'short_name' => 'mt',
            'image' => '/img/flags/mt.png'
        ]);

        WordLanguage::create([
            'name' => 'Burmese',
            'short_name' => 'my',
            'image' => '/img/flags/my.png'
        ]);

        WordLanguage::create([
            'name' => 'Nauru',
            'short_name' => 'na',
            'image' => '/img/flags/na.png'
        ]);

        WordLanguage::create([
            'name' => 'Nepali',
            'short_name' => 'ne',
            'image' => '/img/flags/ne.png'
        ]);

        WordLanguage::create([
            'name' => 'Ndonga',
            'short_name' => 'ng',
            'image' => '/img/flags/ng.png'
        ]);

        WordLanguage::create([
            'name' => 'Dutch; Flemish',
            'short_name' => 'nl',
            'image' => '/img/flags/nl.png'
        ]);

        WordLanguage::create([
            'name' => 'Norwegian',
            'short_name' => 'no',
            'image' => '/img/flags/no.png'
        ]);

        WordLanguage::create([
            'name' => 'Ndebele, South; South Ndebele',
            'short_name' => 'nr',
            'image' => '/img/flags/nr.png'
        ]);

        WordLanguage::create([
            'name' => 'Oromo',
            'short_name' => 'om',
            'image' => '/img/flags/om.png'
        ]);

        WordLanguage::create([
            'name' => 'Panjabi; Punjabi',
            'short_name' => 'pa',
            'image' => '/img/flags/pa.png'
        ]);

        WordLanguage::create([
            'name' => 'Pushto; Pashto',
            'short_name' => 'ps',
            'image' => '/img/flags/ps.png'
        ]);

        WordLanguage::create([
            'name' => 'Portuguese',
            'short_name' => 'pt',
            'image' => '/img/flags/pt.png'
        ]);

        WordLanguage::create([
            'name' => 'Romanian; Moldavian; Moldovan',
            'short_name' => 'ro',
            'image' => '/img/flags/ro.png'
        ]);

        WordLanguage::create([
            'name' => 'Russian',
            'short_name' => 'ru',
            'image' => '/img/flags/ru.png'
        ]);

        WordLanguage::create([
            'name' => 'Kinyarwanda',
            'short_name' => 'rw',
            'image' => '/img/flags/rw.png'
        ]);

        WordLanguage::create([
            'name' => 'Sanskrit',
            'short_name' => 'sa',
            'image' => '/img/flags/sa.png'
        ]);

        WordLanguage::create([
            'name' => 'Sardinian',
            'short_name' => 'sc',
            'image' => '/img/flags/sc.png'
        ]);

        WordLanguage::create([
            'name' => 'Sindhi',
            'short_name' => 'sd',
            'image' => '/img/flags/sd.png'
        ]);

        WordLanguage::create([
            'name' => 'Northern Sami',
            'short_name' => 'se',
            'image' => '/img/flags/se.png'
        ]);

        WordLanguage::create([
            'name' => 'Sango',
            'short_name' => 'sg',
            'image' => '/img/flags/sg.png'
        ]);

        WordLanguage::create([
            'name' => 'Sinhala; Sinhalese',
            'short_name' => 'si',
            'image' => '/img/flags/si.png'
        ]);

        WordLanguage::create([
            'name' => 'Slovak',
            'short_name' => 'sk',
            'image' => '/img/flags/sk.png'
        ]);

        WordLanguage::create([
            'name' => 'Slovenian',
            'short_name' => 'sl',
            'image' => '/img/flags/sl.png'
        ]);

        WordLanguage::create([
            'name' => 'Samoan',
            'short_name' => 'sm',
            'image' => '/img/flags/sm.png'
        ]);

        WordLanguage::create([
            'name' => 'Shona',
            'short_name' => 'sn',
            'image' => '/img/flags/sn.png'
        ]);

        WordLanguage::create([
            'name' => 'Somali',
            'short_name' => 'so',
            'image' => '/img/flags/so.png'
        ]);

        WordLanguage::create([
            'name' => 'Serbian',
            'short_name' => 'sr',
            'image' => '/img/flags/sr.png'
        ]);

        WordLanguage::create([
            'name' => 'Sotho, Southern',
            'short_name' => 'st',
            'image' => '/img/flags/st.png'
        ]);

        WordLanguage::create([
            'name' => 'Swedish',
            'short_name' => 'sv',
            'image' => '/img/flags/sv.png'
        ]);

        WordLanguage::create([
            'name' => 'Tajik',
            'short_name' => 'tg',
            'image' => '/img/flags/tg.png'
        ]);

        WordLanguage::create([
            'name' => 'Thai',
            'short_name' => 'th',
            'image' => '/img/flags/th.png'
        ]);

        WordLanguage::create([
            'name' => 'Turkmen',
            'short_name' => 'tk',
            'image' => '/img/flags/tk.png'
        ]);

        WordLanguage::create([
            'name' => 'Tagalog',
            'short_name' => 'tl',
            'image' => '/img/flags/tl.png'
        ]);

        WordLanguage::create([
            'name' => 'Tswana',
            'short_name' => 'tn',
            'image' => '/img/flags/tn.png'
        ]);

        WordLanguage::create([
            'name' => 'Tonga (Tonga Islands)',
            'short_name' => 'to',
            'image' => '/img/flags/to.png'
        ]);

        WordLanguage::create([
            'name' => 'Turkish',
            'short_name' => 'tr',
            'image' => '/img/flags/tr.png'
        ]);

        WordLanguage::create([
            'name' => 'Tatar',
            'short_name' => 'tt',
            'image' => '/img/flags/tt.png'
        ]);

        WordLanguage::create([
            'name' => 'Twi',
            'short_name' => 'tw',
            'image' => '/img/flags/tw.png'
        ]);

        WordLanguage::create([
            'name' => 'Uighur; Uyghur',
            'short_name' => 'ug',
            'image' => '/img/flags/ug.png'
        ]);

        WordLanguage::create([
            'name' => 'Uzbek',
            'short_name' => 'uz',
            'image' => '/img/flags/uz.png'
        ]);

        WordLanguage::create([
            'name' => 'Venda',
            'short_name' => 've',
            'image' => '/img/flags/ve.png'
        ]);

        WordLanguage::create([
            'name' => 'Vietnamese',
            'short_name' => 'vi',
            'image' => '/img/flags/vi.png'
        ]);

        WordLanguage::create([
            'name' => 'Zhuang; Chuang',
            'short_name' => 'za',
            'image' => '/img/flags/za.png'
        ]);
    }
}