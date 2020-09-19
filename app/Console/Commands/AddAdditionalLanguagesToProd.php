<?php

namespace App\Console\Commands;

use App\WordLanguage;
use Illuminate\Console\Command;

class AddAdditionalLanguagesToProd extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'addlanguagestoprod';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds missing languages to the db, skipping already existing ones.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->addLanguage(
            'English',
            'en',
            '/img/flags/en.png'
        );

        $this->addLanguage(
            'French',
            'fr',
            '/img/flags/fr.png'
        );

        $this->addLanguage(
            'Danish',
            'da',
            '/img/flags/dk.png'
        );

        $this->addLanguage(
            'Polish',
            'pl',
            '/img/flags/pl.png'
        );

        $this->addLanguage(
            'Spanish',
            'es',
            '/img/flags/es.png'
        );

        $this->addLanguage(
            'Avestan',
            'ae',
            '/img/flags/ae.png'
        );

        $this->addLanguage(
            'Afrikaans',
            'af',
            '/img/flags/af.png'
        );

        $this->addLanguage(
            'Amharic',
            'am',
            '/img/flags/am.png'
        );

        $this->addLanguage(
            'Aragonese',
            'an',
            '/img/flags/an.png'
        );

        $this->addLanguage(
            'Arabic',
            'ar',
            '/img/flags/ar.png'
        );

        $this->addLanguage(
            'Assamese',
            'as',
            '/img/flags/as.png'
        );

        $this->addLanguage(
            'Azerbaijani',
            'az',
            '/img/flags/az.png'
        );

        $this->addLanguage(
            'Bashkir',
            'ba',
            '/img/flags/ba.png'
        );

        $this->addLanguage(
            'Belarusian',
            'be',
            '/img/flags/be.png'
        );

        $this->addLanguage(
            'Bulgarian',
            'bg',
            '/img/flags/bg.png'
        );

        $this->addLanguage(
            'Bihari languages',
            'bh',
            '/img/flags/bh.png'
        );

        $this->addLanguage(
            'Bislama',
            'bi',
            '/img/flags/bi.png'
        );

        $this->addLanguage(
            'Bambara',
            'bm',
            '/img/flags/bm.png'
        );

        $this->addLanguage(
            'Bengali',
            'bn',
            '/img/flags/bn.png'
        );

        $this->addLanguage(
            'Tibetan',
            'bo',
            '/img/flags/bo.png'
        );

        $this->addLanguage(
            'Breton',
            'br',
            '/img/flags/br.png'
        );

        $this->addLanguage(
            'Bosnian',
            'bs',
            '/img/flags/bs.png'
        );

        $this->addLanguage(
            'Catalan; Valencian',
            'ca',
            '/img/flags/ca.png'
        );

        $this->addLanguage(
            'Chamorro',
            'ch',
            '/img/flags/ch.png'
        );

        $this->addLanguage(
            'Corsican',
            'co',
            '/img/flags/co.png'
        );

        $this->addLanguage(
            'Cree',
            'cr',
            '/img/flags/cr.png'
        );

        $this->addLanguage(
            'Czech',
            'cs',
            '/img/flags/cs.png'
        );

        $this->addLanguage(
            'Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic',
            'cu',
            '/img/flags/cu.png'
        );

        $this->addLanguage(
            'Chuvash',
            'cv',
            '/img/flags/cv.png'
        );

        $this->addLanguage(
            'Welsh',
            'cy',
            '/img/flags/cy.png'
        );

        $this->addLanguage(
            'German',
            'de',
            '/img/flags/de.png'
        );

        $this->addLanguage(
            'Dzongkha',
            'dz',
            '/img/flags/dz.png'
        );

        $this->addLanguage(
            'Ewe',
            'ee',
            '/img/flags/ee.png'
        );

        $this->addLanguage(
            'Estonian',
            'et',
            '/img/flags/et.png'
        );

        $this->addLanguage(
            'Finnish',
            'fi',
            '/img/flags/fi.png'
        );

        $this->addLanguage(
            'Fijian',
            'fj',
            '/img/flags/fj.png'
        );

        $this->addLanguage(
            'Faroese',
            'fo',
            '/img/flags/fo.png'
        );

        $this->addLanguage(
            'Irish',
            'ga',
            '/img/flags/ga.png'
        );

        $this->addLanguage(
            'Gaelic; Scottish Gaelic',
            'gd',
            '/img/flags/gd.png'
        );

        $this->addLanguage(
            'Galician',
            'gl',
            '/img/flags/gl.png'
        );

        $this->addLanguage(
            'Guarani',
            'gn',
            '/img/flags/gn.png'
        );

        $this->addLanguage(
            'Gujarati',
            'gu',
            '/img/flags/gu.png'
        );

        $this->addLanguage(
            'Croatian',
            'hr',
            '/img/flags/hr.png'
        );

        $this->addLanguage(
            'Haitian; Haitian Creole',
            'ht',
            '/img/flags/ht.png'
        );

        $this->addLanguage(
            'Hungarian',
            'hu',
            '/img/flags/hu.png'
        );

        $this->addLanguage(
            'Indonesian',
            'id',
            '/img/flags/id.png'
        );

        $this->addLanguage(
            'Interlingue; Occidental',
            'ie',
            '/img/flags/ie.png'
        );

        $this->addLanguage(
            'Ido',
            'io',
            '/img/flags/io.png'
        );

        $this->addLanguage(
            'Icelandic',
            'is',
            '/img/flags/is.png'
        );

        $this->addLanguage(
            'Italian',
            'it',
            '/img/flags/it.png'
        );

        $this->addLanguage(
            'Kongo',
            'kg',
            '/img/flags/kg.png'
        );

        $this->addLanguage(
            'Kikuyu; Gikuyu',
            'ki',
            '/img/flags/ki.png'
        );

        $this->addLanguage(
            'Central Khmer',
            'km',
            '/img/flags/km.png'
        );

        $this->addLanguage(
            'Kannada',
            'kn',
            '/img/flags/kn.png'
        );

        $this->addLanguage(
            'Kanuri',
            'kr',
            '/img/flags/kr.png'
        );

        $this->addLanguage(
            'Cornish',
            'kw',
            '/img/flags/kw.png'
        );

        $this->addLanguage(
            'Kirghiz; Kyrgyz',
            'ky',
            '/img/flags/ky.png'
        );

        $this->addLanguage(
            'Latin',
            'la',
            '/img/flags/la.png'
        );

        $this->addLanguage(
            'Luxembourgish; Letzeburgesch',
            'lb',
            '/img/flags/lb.png'
        );

        $this->addLanguage(
            'Limburgan; Limburger; Limburgish',
            'li',
            '/img/flags/li.png'
        );

        $this->addLanguage(
            'Lithuanian',
            'lt',
            '/img/flags/lt.png'
        );

        $this->addLanguage(
            'Luba-Katanga',
            'lu',
            '/img/flags/lu.png'
        );

        $this->addLanguage(
            'Latvian',
            'lv',
            '/img/flags/lv.png'
        );

        $this->addLanguage(
            'Malagasy',
            'mg',
            '/img/flags/mg.png'
        );

        $this->addLanguage(
            'Marshallese',
            'mh',
            '/img/flags/mh.png'
        );

        $this->addLanguage(
            'Macedonian',
            'mk',
            '/img/flags/mk.png'
        );

        $this->addLanguage(
            'Malayalam',
            'ml',
            '/img/flags/ml.png'
        );

        $this->addLanguage(
            'Mongolian',
            'mn',
            '/img/flags/mn.png'
        );

        $this->addLanguage(
            'Marathi',
            'mr',
            '/img/flags/mr.png'
        );

        $this->addLanguage(
            'Malay',
            'ms',
            '/img/flags/ms.png'
        );

        $this->addLanguage(
            'Maltese',
            'mt',
            '/img/flags/mt.png'
        );

        $this->addLanguage(
            'Burmese',
            'my',
            '/img/flags/my.png'
        );

        $this->addLanguage(
            'Nauru',
            'na',
            '/img/flags/na.png'
        );

        $this->addLanguage(
            'Nepali',
            'ne',
            '/img/flags/ne.png'
        );

        $this->addLanguage(
            'Ndonga',
            'ng',
            '/img/flags/ng.png'
        );

        $this->addLanguage(
            'Dutch; Flemish',
            'nl',
            '/img/flags/nl.png'
        );

        $this->addLanguage(
            'Norwegian',
            'no',
            '/img/flags/no.png'
        );

        $this->addLanguage(
            'Ndebele, South; South Ndebele',
            'nr',
            '/img/flags/nr.png'
        );

        $this->addLanguage(
            'Oromo',
            'om',
            '/img/flags/om.png'
        );

        $this->addLanguage(
            'Panjabi; Punjabi',
            'pa',
            '/img/flags/pa.png'
        );

        $this->addLanguage(
            'Pushto; Pashto',
            'ps',
            '/img/flags/ps.png'
        );

        $this->addLanguage(
            'Portuguese',
            'pt',
            '/img/flags/pt.png'
        );

        $this->addLanguage(
            'Romanian; Moldavian; Moldovan',
            'ro',
            '/img/flags/ro.png'
        );

        $this->addLanguage(
            'Russian',
            'ru',
            '/img/flags/ru.png'
        );

        $this->addLanguage(
            'Kinyarwanda',
            'rw',
            '/img/flags/rw.png'
        );

        $this->addLanguage(
            'Sanskrit',
            'sa',
            '/img/flags/sa.png'
        );

        $this->addLanguage(
            'Sardinian',
            'sc',
            '/img/flags/sc.png'
        );

        $this->addLanguage(
            'Sindhi',
            'sd',
            '/img/flags/sd.png'
        );

        $this->addLanguage(
            'Northern Sami',
            'se',
            '/img/flags/se.png'
        );

        $this->addLanguage(
            'Sango',
            'sg',
            '/img/flags/sg.png'
        );

        $this->addLanguage(
            'Sinhala; Sinhalese',
            'si',
            '/img/flags/si.png'
        );

        $this->addLanguage(
            'Slovak',
            'sk',
            '/img/flags/sk.png'
        );

        $this->addLanguage(
            'Slovenian',
            'sl',
            '/img/flags/sl.png'
        );

        $this->addLanguage(
            'Samoan',
            'sm',
            '/img/flags/sm.png'
        );

        $this->addLanguage(
            'Shona',
            'sn',
            '/img/flags/sn.png'
        );

        $this->addLanguage(
            'Somali',
            'so',
            '/img/flags/so.png'
        );

        $this->addLanguage(
            'Serbian',
            'sr',
            '/img/flags/sr.png'
        );

        $this->addLanguage(
            'Sotho, Southern',
            'st',
            '/img/flags/st.png'
        );

        $this->addLanguage(
            'Swedish',
            'sv',
            '/img/flags/sv.png'
        );

        $this->addLanguage(
            'Tajik',
            'tg',
            '/img/flags/tg.png'
        );

        $this->addLanguage(
            'Thai',
            'th',
            '/img/flags/th.png'
        );

        $this->addLanguage(
            'Turkmen',
            'tk',
            '/img/flags/tk.png'
        );

        $this->addLanguage(
            'Tagalog',
            'tl',
            '/img/flags/tl.png'
        );

        $this->addLanguage(
            'Tswana',
            'tn',
            '/img/flags/tn.png'
        );

        $this->addLanguage(
            'Tonga (Tonga Islands)',
            'to',
            '/img/flags/to.png'
        );

        $this->addLanguage(
            'Turkish',
            'tr',
            '/img/flags/tr.png'
        );

        $this->addLanguage(
            'Tatar',
            'tt',
            '/img/flags/tt.png'
        );

        $this->addLanguage(
            'Twi',
            'tw',
            '/img/flags/tw.png'
        );

        $this->addLanguage(
            'Uighur; Uyghur',
            'ug',
            '/img/flags/ug.png'
        );

        $this->addLanguage(
            'Uzbek',
            'uz',
            '/img/flags/uz.png'
        );

        $this->addLanguage(
            'Venda',
            've',
            '/img/flags/ve.png'
        );

        $this->addLanguage(
            'Vietnamese',
            'vi',
            '/img/flags/vi.png'
        );

        $this->addLanguage(
            'Zhuang; Chuang',
            'za',
            '/img/flags/za.png'
        );
    }

    /**
     * @param $name string
     * @param $short_name string
     * @param $image string
     * @return void
     */
    private function addLanguage($name, $short_name, $image)
    {
        if (!$this->languageExists($short_name)) {
            WordLanguage::create([
                'name' => $name,
                'short_name' => $short_name,
                'image' => $image
            ]);
            $this->info('Language added: ' . $name);
        } else {
            $this->info('Language already exists: ' . $name);
        }
    }

    /**
     * @param $short_name string
     * @return bool
     */
    private function languageExists($short_name)
    {
        $language = WordLanguage::where('short_name', $short_name)->count();
        if ($language > 0) {
            return true;
        }

        return false;
    }
}
