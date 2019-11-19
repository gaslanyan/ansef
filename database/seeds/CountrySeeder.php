<?php

use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Country::create([
            'id' => 1,
            'cc_fips' => 'AA',
            'cc_iso' => 'AW',
            'tld' => '.aw',
            'App\Models\Country_name' => 'Aruba',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 2,
            'cc_fips' => 'AC',
            'cc_iso' => 'AG',
            'tld' => '.ag',
            'App\Models\Country_name' => 'Antigua and Barbuda',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 3,
            'cc_fips' => 'AE',
            'cc_iso' => 'AE',
            'tld' => '.ae',
            'App\Models\Country_name' => 'United Arab Emirates',
            'App\Models\Country_phone_code' => 784
        ]);



        App\Models\Country::create([
            'id' => 4,
            'cc_fips' => 'AF',
            'cc_iso' => 'AF',
            'tld' => '.af',
            'App\Models\Country_name' => 'Afghanistan',
            'App\Models\Country_phone_code' => 4
        ]);



        App\Models\Country::create([
            'id' => 5,
            'cc_fips' => 'AG',
            'cc_iso' => 'DZ',
            'tld' => '.dz',
            'App\Models\Country_name' => 'Algeria',
            'App\Models\Country_phone_code' => 28
        ]);



        App\Models\Country::create([
            'id' => 6,
            'cc_fips' => 'AJ',
            'cc_iso' => 'AZ',
            'tld' => '.az',
            'App\Models\Country_name' => 'Azerbaijan',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 7,
            'cc_fips' => 'AL',
            'cc_iso' => 'AL',
            'tld' => '.al',
            'App\Models\Country_name' => 'Albania',
            'App\Models\Country_phone_code' => 8
        ]);



        App\Models\Country::create([
            'id' => 8,
            'cc_fips' => 'AM',
            'cc_iso' => 'AM',
            'tld' => '.am',
            'App\Models\Country_name' => 'Armenia',
            'App\Models\Country_phone_code' => 51
        ]);



        App\Models\Country::create([
            'id' => 9,
            'cc_fips' => 'AN',
            'cc_iso' => 'AD',
            'tld' => '.ad',
            'App\Models\Country_name' => 'Andorra',
            'App\Models\Country_phone_code' => 530
        ]);



        App\Models\Country::create([
            'id' => 10,
            'cc_fips' => 'AO',
            'cc_iso' => 'AO',
            'tld' => '.ao',
            'App\Models\Country_name' => 'Angola',
            'App\Models\Country_phone_code' => 24
        ]);



        App\Models\Country::create([
            'id' => 11,
            'cc_fips' => 'AQ',
            'cc_iso' => 'AS',
            'tld' => '.as',
            'App\Models\Country_name' => 'American Samoa',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 12,
            'cc_fips' => 'AR',
            'cc_iso' => 'AR',
            'tld' => '.ar',
            'App\Models\Country_name' => 'Argentina',
            'App\Models\Country_phone_code' => 32
        ]);



        App\Models\Country::create([
            'id' => 13,
            'cc_fips' => 'AS',
            'cc_iso' => 'AU',
            'tld' => '.au',
            'App\Models\Country_name' => 'Australia',
            'App\Models\Country_phone_code' => 16
        ]);



        App\Models\Country::create([
            'id' => 14,
            'cc_fips' => 'AT',
            'cc_iso' => '-',
            'tld' => '-',
            'App\Models\Country_name' => 'Ashmore and Cartier Islands',
            'App\Models\Country_phone_code' => 40
        ]);



        App\Models\Country::create([
            'id' => 15,
            'cc_fips' => 'AU',
            'cc_iso' => 'AT',
            'tld' => '.at',
            'App\Models\Country_name' => 'Austria',
            'App\Models\Country_phone_code' => 36
        ]);



        App\Models\Country::create([
            'id' => 16,
            'cc_fips' => 'AV',
            'cc_iso' => 'AI',
            'tld' => '.ai',
            'App\Models\Country_name' => 'Anguilla',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 17,
            'cc_fips' => '-',
            'cc_iso' => 'AX',
            'tld' => '.ax',
            'App\Models\Country_name' => 'Åland Islands',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 18,
            'cc_fips' => 'AY',
            'cc_iso' => 'AQ',
            'tld' => '.aq',
            'App\Models\Country_name' => 'Antarctica',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 19,
            'cc_fips' => 'BA',
            'cc_iso' => 'BH',
            'tld' => '.bh',
            'App\Models\Country_name' => 'Bahrain',
            'App\Models\Country_phone_code' => 70
        ]);



        App\Models\Country::create([
            'id' => 20,
            'cc_fips' => 'BB',
            'cc_iso' => 'BB',
            'tld' => '.bb',
            'App\Models\Country_name' => 'Barbados',
            'App\Models\Country_phone_code' => 52
        ]);



        App\Models\Country::create([
            'id' => 21,
            'cc_fips' => 'BC',
            'cc_iso' => 'BW',
            'tld' => '.bw',
            'App\Models\Country_name' => 'Botswana',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 22,
            'cc_fips' => 'BD',
            'cc_iso' => 'BM',
            'tld' => '.bm',
            'App\Models\Country_name' => 'Bermuda',
            'App\Models\Country_phone_code' => 50
        ]);



        App\Models\Country::create([
            'id' => 23,
            'cc_fips' => 'BE',
            'cc_iso' => 'BE',
            'tld' => '.be',
            'App\Models\Country_name' => 'Belgium',
            'App\Models\Country_phone_code' => 56
        ]);



        App\Models\Country::create([
            'id' => 24,
            'cc_fips' => 'BF',
            'cc_iso' => 'BS',
            'tld' => '.bs',
            'App\Models\Country_name' => 'Bahamas, The',
            'App\Models\Country_phone_code' => 854
        ]);



        App\Models\Country::create([
            'id' => 25,
            'cc_fips' => 'BG',
            'cc_iso' => 'BD',
            'tld' => '.bd',
            'App\Models\Country_name' => 'Bangladesh',
            'App\Models\Country_phone_code' => 100
        ]);



        App\Models\Country::create([
            'id' => 26,
            'cc_fips' => 'BH',
            'cc_iso' => 'BZ',
            'tld' => '.bz',
            'App\Models\Country_name' => 'Belize',
            'App\Models\Country_phone_code' => 48
        ]);



        App\Models\Country::create([
            'id' => 27,
            'cc_fips' => 'BK',
            'cc_iso' => 'BA',
            'tld' => '.ba',
            'App\Models\Country_name' => 'Bosnia and Herzegovina',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 28,
            'cc_fips' => 'BL',
            'cc_iso' => 'BO',
            'tld' => '.bo',
            'App\Models\Country_name' => 'Bolivia',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 29,
            'cc_fips' => 'TB',
            'cc_iso' => 'BL',
            'tld' => '.bl',
            'App\Models\Country_name' => 'Saint Barthelemy',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 30,
            'cc_fips' => 'BM',
            'cc_iso' => 'MM',
            'tld' => '.mm',
            'App\Models\Country_name' => 'Myanmar',
            'App\Models\Country_phone_code' => 60
        ]);



        App\Models\Country::create([
            'id' => 31,
            'cc_fips' => 'BN',
            'cc_iso' => 'BJ',
            'tld' => '.bj',
            'App\Models\Country_name' => 'Benin',
            'App\Models\Country_phone_code' => 96
        ]);



        App\Models\Country::create([
            'id' => 32,
            'cc_fips' => 'BO',
            'cc_iso' => 'BY',
            'tld' => '.by',
            'App\Models\Country_name' => 'Belarus',
            'App\Models\Country_phone_code' => 68
        ]);



        App\Models\Country::create([
            'id' => 33,
            'cc_fips' => 'BP',
            'cc_iso' => 'SB',
            'tld' => '.sb',
            'App\Models\Country_name' => 'Solomon Islands',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 34,
            'cc_fips' => 'BQ',
            'cc_iso' => '-',
            'tld' => '-',
            'App\Models\Country_name' => 'Navassa Island',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 35,
            'cc_fips' => 'BR',
            'cc_iso' => 'BR',
            'tld' => '.br',
            'App\Models\Country_name' => 'Brazil',
            'App\Models\Country_phone_code' => 76
        ]);



        App\Models\Country::create([
            'id' => 36,
            'cc_fips' => 'BS',
            'cc_iso' => '-',
            'tld' => '-',
            'App\Models\Country_name' => 'Bassas da India',
            'App\Models\Country_phone_code' => 44
        ]);



        App\Models\Country::create([
            'id' => 37,
            'cc_fips' => 'BT',
            'cc_iso' => 'BT',
            'tld' => '.bt',
            'App\Models\Country_name' => 'Bhutan',
            'App\Models\Country_phone_code' => 64
        ]);



        App\Models\Country::create([
            'id' => 38,
            'cc_fips' => 'BU',
            'cc_iso' => 'BG',
            'tld' => '.bg',
            'App\Models\Country_name' => 'Bulgaria',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 39,
            'cc_fips' => 'BV',
            'cc_iso' => 'BV',
            'tld' => '.bv',
            'App\Models\Country_name' => 'Bouvet Island',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 40,
            'cc_fips' => 'BX',
            'cc_iso' => 'BN',
            'tld' => '.bn',
            'App\Models\Country_name' => 'Brunei',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 41,
            'cc_fips' => 'BY',
            'cc_iso' => 'BI',
            'tld' => '.bi',
            'App\Models\Country_name' => 'Burundi',
            'App\Models\Country_phone_code' => 112
        ]);



        App\Models\Country::create([
            'id' => 42,
            'cc_fips' => 'CA',
            'cc_iso' => 'CA',
            'tld' => '.ca',
            'App\Models\Country_name' => 'Canada',
            'App\Models\Country_phone_code' => 124
        ]);



        App\Models\Country::create([
            'id' => 43,
            'cc_fips' => 'CB',
            'cc_iso' => 'KH',
            'tld' => '.kh',
            'App\Models\Country_name' => 'Cambodia',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 44,
            'cc_fips' => 'CD',
            'cc_iso' => 'TD',
            'tld' => '.td',
            'App\Models\Country_name' => 'Chad',
            'App\Models\Country_phone_code' => 180
        ]);



        App\Models\Country::create([
            'id' => 45,
            'cc_fips' => 'CE',
            'cc_iso' => 'LK',
            'tld' => '.lk',
            'App\Models\Country_name' => 'Sri Lanka',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 46,
            'cc_fips' => 'CF',
            'cc_iso' => 'CG',
            'tld' => '.cg',
            'App\Models\Country_name' => 'Congo, Republic of the',
            'App\Models\Country_phone_code' => 140
        ]);



        App\Models\Country::create([
            'id' => 47,
            'cc_fips' => 'CG',
            'cc_iso' => 'CD',
            'tld' => '.cd',
            'App\Models\Country_name' => 'Congo, Democratic Republic of the',
            'App\Models\Country_phone_code' => 178
        ]);



        App\Models\Country::create([
            'id' => 48,
            'cc_fips' => 'CH',
            'cc_iso' => 'CN',
            'tld' => '.cn',
            'App\Models\Country_name' => 'China',
            'App\Models\Country_phone_code' => 756
        ]);



        App\Models\Country::create([
            'id' => 49,
            'cc_fips' => 'CI',
            'cc_iso' => 'CL',
            'tld' => '.cl',
            'App\Models\Country_name' => 'Chile',
            'App\Models\Country_phone_code' => 384
        ]);



        App\Models\Country::create([
            'id' => 50,
            'cc_fips' => 'CJ',
            'cc_iso' => 'KY',
            'tld' => '.ky',
            'App\Models\Country_name' => 'Cayman Islands',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 51,
            'cc_fips' => 'CK',
            'cc_iso' => 'CC',
            'tld' => '.cc',
            'App\Models\Country_name' => 'Cocos (Keeling) Islands',
            'App\Models\Country_phone_code' => 184
        ]);



        App\Models\Country::create([
            'id' => 52,
            'cc_fips' => 'CM',
            'cc_iso' => 'CM',
            'tld' => '.cm',
            'App\Models\Country_name' => 'Cameroon',
            'App\Models\Country_phone_code' => 120
        ]);



        App\Models\Country::create([
            'id' => 53,
            'cc_fips' => 'CN',
            'cc_iso' => 'KM',
            'tld' => '.km',
            'App\Models\Country_name' => 'Comoros',
            'App\Models\Country_phone_code' => 156
        ]);



        App\Models\Country::create([
            'id' => 54,
            'cc_fips' => 'CO',
            'cc_iso' => 'CO',
            'tld' => '.co',
            'App\Models\Country_name' => 'Colombia',
            'App\Models\Country_phone_code' => 170
        ]);



        App\Models\Country::create([
            'id' => 55,
            'cc_fips' => 'CQ',
            'cc_iso' => 'MP',
            'tld' => '.mp',
            'App\Models\Country_name' => 'Northern Mariana Islands',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 56,
            'cc_fips' => 'CR',
            'cc_iso' => '-',
            'tld' => '-',
            'App\Models\Country_name' => 'Coral Sea Islands',
            'App\Models\Country_phone_code' => 188
        ]);



        App\Models\Country::create([
            'id' => 57,
            'cc_fips' => 'CS',
            'cc_iso' => 'CR',
            'tld' => '.cr',
            'App\Models\Country_name' => 'Costa Rica',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 58,
            'cc_fips' => 'CT',
            'cc_iso' => 'CF',
            'tld' => '.cf',
            'App\Models\Country_name' => 'Central African Republic',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 59,
            'cc_fips' => 'CU',
            'cc_iso' => 'CU',
            'tld' => '.cu',
            'App\Models\Country_name' => 'Cuba',
            'App\Models\Country_phone_code' => 192
        ]);



        App\Models\Country::create([
            'id' => 60,
            'cc_fips' => 'CV',
            'cc_iso' => 'CV',
            'tld' => '.cv',
            'App\Models\Country_name' => 'Cape Verde',
            'App\Models\Country_phone_code' => 132
        ]);



        App\Models\Country::create([
            'id' => 61,
            'cc_fips' => 'CW',
            'cc_iso' => 'CK',
            'tld' => '.ck',
            'App\Models\Country_name' => 'Cook Islands',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 62,
            'cc_fips' => 'CY',
            'cc_iso' => 'CY',
            'tld' => '.cy',
            'App\Models\Country_name' => 'Cyprus',
            'App\Models\Country_phone_code' => 196
        ]);



        App\Models\Country::create([
            'id' => 63,
            'cc_fips' => 'DA',
            'cc_iso' => 'DK',
            'tld' => '.dk',
            'App\Models\Country_name' => 'Denmark',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 64,
            'cc_fips' => 'DJ',
            'cc_iso' => 'DJ',
            'tld' => '.dj',
            'App\Models\Country_name' => 'Djibouti',
            'App\Models\Country_phone_code' => 262
        ]);



        App\Models\Country::create([
            'id' => 65,
            'cc_fips' => 'DO',
            'cc_iso' => 'DM',
            'tld' => '.dm',
            'App\Models\Country_name' => 'Dominica',
            'App\Models\Country_phone_code' => 214
        ]);



        App\Models\Country::create([
            'id' => 66,
            'cc_fips' => 'DR',
            'cc_iso' => 'DO',
            'tld' => '.do',
            'App\Models\Country_name' => 'Dominican Republic',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 67,
            'cc_fips' => 'DX',
            'cc_iso' => '-',
            'tld' => '-',
            'App\Models\Country_name' => 'Dhekelia Sovereign Base Area',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 68,
            'cc_fips' => 'EC',
            'cc_iso' => 'EC',
            'tld' => '.ec',
            'App\Models\Country_name' => 'Ecuador',
            'App\Models\Country_phone_code' => 218
        ]);



        App\Models\Country::create([
            'id' => 69,
            'cc_fips' => 'EG',
            'cc_iso' => 'EG',
            'tld' => '.eg',
            'App\Models\Country_name' => 'Egypt',
            'App\Models\Country_phone_code' => 818
        ]);



        App\Models\Country::create([
            'id' => 70,
            'cc_fips' => 'EI',
            'cc_iso' => 'IE',
            'tld' => '.ie',
            'App\Models\Country_name' => 'Ireland',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 71,
            'cc_fips' => 'EK',
            'cc_iso' => 'GQ',
            'tld' => '.gq',
            'App\Models\Country_name' => 'Equatorial Guinea',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 72,
            'cc_fips' => 'EN',
            'cc_iso' => 'EE',
            'tld' => '.ee',
            'App\Models\Country_name' => 'Estonia',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 73,
            'cc_fips' => 'ER',
            'cc_iso' => 'ER',
            'tld' => '.er',
            'App\Models\Country_name' => 'Eritrea',
            'App\Models\Country_phone_code' => 232
        ]);



        App\Models\Country::create([
            'id' => 74,
            'cc_fips' => 'ES',
            'cc_iso' => 'SV',
            'tld' => '.sv',
            'App\Models\Country_name' => 'El Salvador',
            'App\Models\Country_phone_code' => 724
        ]);



        App\Models\Country::create([
            'id' => 75,
            'cc_fips' => 'ET',
            'cc_iso' => 'ET',
            'tld' => '.et',
            'App\Models\Country_name' => 'Ethiopia',
            'App\Models\Country_phone_code' => 231
        ]);



        App\Models\Country::create([
            'id' => 76,
            'cc_fips' => 'EU',
            'cc_iso' => '-',
            'tld' => '-',
            'App\Models\Country_name' => 'Europa Island',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 77,
            'cc_fips' => 'EZ',
            'cc_iso' => 'CZ',
            'tld' => '.cz',
            'App\Models\Country_name' => 'Czech Republic',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 78,
            'cc_fips' => 'FG',
            'cc_iso' => 'GF',
            'tld' => '.gf',
            'App\Models\Country_name' => 'French Guiana',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 79,
            'cc_fips' => 'FI',
            'cc_iso' => 'FI',
            'tld' => '.fi',
            'App\Models\Country_name' => 'Finland',
            'App\Models\Country_phone_code' => 246
        ]);



        App\Models\Country::create([
            'id' => 80,
            'cc_fips' => 'FJ',
            'cc_iso' => 'FJ',
            'tld' => '.fj',
            'App\Models\Country_name' => 'Fiji',
            'App\Models\Country_phone_code' => 242
        ]);



        App\Models\Country::create([
            'id' => 81,
            'cc_fips' => 'FK',
            'cc_iso' => 'FK',
            'tld' => '.fk',
            'App\Models\Country_name' => 'Falkland Islands (Islas Malvinas)',
            'App\Models\Country_phone_code' => 238
        ]);



        App\Models\Country::create([
            'id' => 82,
            'cc_fips' => 'FM',
            'cc_iso' => 'FM',
            'tld' => '.fm',
            'App\Models\Country_name' => 'Micronesia, Federated States of',
            'App\Models\Country_phone_code' => 583
        ]);



        App\Models\Country::create([
            'id' => 83,
            'cc_fips' => 'FO',
            'cc_iso' => 'FO',
            'tld' => '.fo',
            'App\Models\Country_name' => 'Faroe Islands',
            'App\Models\Country_phone_code' => 234
        ]);



        App\Models\Country::create([
            'id' => 84,
            'cc_fips' => 'FP',
            'cc_iso' => 'PF',
            'tld' => '.pf',
            'App\Models\Country_name' => 'French Polynesia',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 85,
            'cc_fips' => 'FR',
            'cc_iso' => 'FR',
            'tld' => '.fr',
            'App\Models\Country_name' => 'France',
            'App\Models\Country_phone_code' => 250
        ]);



        App\Models\Country::create([
            'id' => 86,
            'cc_fips' => 'FS',
            'cc_iso' => 'TF',
            'tld' => '.tf',
            'App\Models\Country_name' => 'French Southern and Antarctic Lands',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 87,
            'cc_fips' => 'GA',
            'cc_iso' => 'GM',
            'tld' => '.gm',
            'App\Models\Country_name' => 'Gambia, The',
            'App\Models\Country_phone_code' => 266
        ]);



        App\Models\Country::create([
            'id' => 88,
            'cc_fips' => 'GB',
            'cc_iso' => 'GA',
            'tld' => '.ga',
            'App\Models\Country_name' => 'Gabon',
            'App\Models\Country_phone_code' => 826
        ]);



        App\Models\Country::create([
            'id' => 89,
            'cc_fips' => 'GG',
            'cc_iso' => 'GE',
            'tld' => '.ge',
            'App\Models\Country_name' => 'Georgia',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 90,
            'cc_fips' => 'GH',
            'cc_iso' => 'GH',
            'tld' => '.gh',
            'App\Models\Country_name' => 'Ghana',
            'App\Models\Country_phone_code' => 288
        ]);



        App\Models\Country::create([
            'id' => 91,
            'cc_fips' => 'GI',
            'cc_iso' => 'GI',
            'tld' => '.gi',
            'App\Models\Country_name' => 'Gibraltar',
            'App\Models\Country_phone_code' => 292
        ]);



        App\Models\Country::create([
            'id' => 92,
            'cc_fips' => 'GJ',
            'cc_iso' => 'GD',
            'tld' => '.gd',
            'App\Models\Country_name' => 'Grenada',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 93,
            'cc_fips' => 'GK',
            'cc_iso' => 'GG',
            'tld' => '.gg',
            'App\Models\Country_name' => 'Guernsey',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 94,
            'cc_fips' => 'GL',
            'cc_iso' => 'GL',
            'tld' => '.gl',
            'App\Models\Country_name' => 'Greenland',
            'App\Models\Country_phone_code' => 304
        ]);



        App\Models\Country::create([
            'id' => 95,
            'cc_fips' => 'GM',
            'cc_iso' => 'DE',
            'tld' => '.de',
            'App\Models\Country_name' => 'Germany',
            'App\Models\Country_phone_code' => 270
        ]);



        App\Models\Country::create([
            'id' => 96,
            'cc_fips' => 'GO',
            'cc_iso' => '-',
            'tld' => '-',
            'App\Models\Country_name' => 'Glorioso Islands',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 97,
            'cc_fips' => 'GP',
            'cc_iso' => 'GP',
            'tld' => '.gp',
            'App\Models\Country_name' => 'Guadeloupe',
            'App\Models\Country_phone_code' => 312
        ]);



        App\Models\Country::create([
            'id' => 98,
            'cc_fips' => 'GQ',
            'cc_iso' => 'GU',
            'tld' => '.gu',
            'App\Models\Country_name' => 'Guam',
            'App\Models\Country_phone_code' => 226
        ]);



        App\Models\Country::create([
            'id' => 99,
            'cc_fips' => 'GR',
            'cc_iso' => 'GR',
            'tld' => '.gr',
            'App\Models\Country_name' => 'Greece',
            'App\Models\Country_phone_code' => 300
        ]);



        App\Models\Country::create([
            'id' => 100,
            'cc_fips' => 'GT',
            'cc_iso' => 'GT',
            'tld' => '.gt',
            'App\Models\Country_name' => 'Guatemala',
            'App\Models\Country_phone_code' => 320
        ]);



        App\Models\Country::create([
            'id' => 101,
            'cc_fips' => 'GV',
            'cc_iso' => 'GN',
            'tld' => '.gn',
            'App\Models\Country_name' => 'Guinea',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 102,
            'cc_fips' => 'GY',
            'cc_iso' => 'GY',
            'tld' => '.gy',
            'App\Models\Country_name' => 'Guyana',
            'App\Models\Country_phone_code' => 328
        ]);



        App\Models\Country::create([
            'id' => 103,
            'cc_fips' => 'GZ',
            'cc_iso' => '-',
            'tld' => '-',
            'App\Models\Country_name' => 'Gaza Strip',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 104,
            'cc_fips' => 'HA',
            'cc_iso' => 'HT',
            'tld' => '.ht',
            'App\Models\Country_name' => 'Haiti',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 105,
            'cc_fips' => 'HK',
            'cc_iso' => 'HK',
            'tld' => '.hk',
            'App\Models\Country_name' => 'Hong Kong',
            'App\Models\Country_phone_code' => 344
        ]);



        App\Models\Country::create([
            'id' => 106,
            'cc_fips' => 'HM',
            'cc_iso' => 'HM',
            'tld' => '.hm',
            'App\Models\Country_name' => 'Heard Island and McDonald Islands',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 107,
            'cc_fips' => 'HO',
            'cc_iso' => 'HN',
            'tld' => '.hn',
            'App\Models\Country_name' => 'Honduras',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 108,
            'cc_fips' => 'HR',
            'cc_iso' => 'HR',
            'tld' => '.hr',
            'App\Models\Country_name' => 'Croatia',
            'App\Models\Country_phone_code' => 191
        ]);



        App\Models\Country::create([
            'id' => 109,
            'cc_fips' => 'HU',
            'cc_iso' => 'HU',
            'tld' => '.hu',
            'App\Models\Country_name' => 'Hungary',
            'App\Models\Country_phone_code' => 348
        ]);



        App\Models\Country::create([
            'id' => 110,
            'cc_fips' => 'IC',
            'cc_iso' => 'IS',
            'tld' => '.is',
            'App\Models\Country_name' => 'Iceland',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 111,
            'cc_fips' => 'ID',
            'cc_iso' => 'ID',
            'tld' => '.id',
            'App\Models\Country_name' => 'Indonesia',
            'App\Models\Country_phone_code' => 360
        ]);



        App\Models\Country::create([
            'id' => 112,
            'cc_fips' => 'IM',
            'cc_iso' => 'IM',
            'tld' => '.im',
            'App\Models\Country_name' => 'Isle of Man',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 113,
            'cc_fips' => 'IN',
            'cc_iso' => 'IN',
            'tld' => '.in',
            'App\Models\Country_name' => 'India',
            'App\Models\Country_phone_code' => 356
        ]);



        App\Models\Country::create([
            'id' => 114,
            'cc_fips' => 'IO',
            'cc_iso' => 'IO',
            'tld' => '.io',
            'App\Models\Country_name' => 'British Indian Ocean Territory',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 115,
            'cc_fips' => 'IP',
            'cc_iso' => '-',
            'tld' => '-',
            'App\Models\Country_name' => 'Clipperton Island',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 116,
            'cc_fips' => 'IR',
            'cc_iso' => 'IR',
            'tld' => '.ir',
            'App\Models\Country_name' => 'Iran',
            'App\Models\Country_phone_code' => 364
        ]);



        App\Models\Country::create([
            'id' => 117,
            'cc_fips' => 'IS',
            'cc_iso' => 'IL',
            'tld' => '.il',
            'App\Models\Country_name' => 'Israel',
            'App\Models\Country_phone_code' => 352
        ]);



        App\Models\Country::create([
            'id' => 118,
            'cc_fips' => 'IT',
            'cc_iso' => 'IT',
            'tld' => '.it',
            'App\Models\Country_name' => 'Italy',
            'App\Models\Country_phone_code' => 380
        ]);



        App\Models\Country::create([
            'id' => 119,
            'cc_fips' => 'IV',
            'cc_iso' => 'CI',
            'tld' => '.ci',
            'App\Models\Country_name' => 'Cote d\'Ivoire',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 120,
            'cc_fips' => 'IZ',
            'cc_iso' => 'IQ',
            'tld' => '.iq',
            'App\Models\Country_name' => 'Iraq',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 121,
            'cc_fips' => 'JA',
            'cc_iso' => 'JP',
            'tld' => '.jp',
            'App\Models\Country_name' => 'Japan',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 122,
            'cc_fips' => 'JE',
            'cc_iso' => 'JE',
            'tld' => '.je',
            'App\Models\Country_name' => 'Jersey',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 123,
            'cc_fips' => 'JM',
            'cc_iso' => 'JM',
            'tld' => '.jm',
            'App\Models\Country_name' => 'Jamaica',
            'App\Models\Country_phone_code' => 388
        ]);



        App\Models\Country::create([
            'id' => 124,
            'cc_fips' => 'JN',
            'cc_iso' => 'SJ',
            'tld' => '-',
            'App\Models\Country_name' => 'Jan Mayen',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 125,
            'cc_fips' => 'JO',
            'cc_iso' => 'JO',
            'tld' => '.jo',
            'App\Models\Country_name' => 'Jordan',
            'App\Models\Country_phone_code' => 400
        ]);



        App\Models\Country::create([
            'id' => 126,
            'cc_fips' => 'JU',
            'cc_iso' => '-',
            'tld' => '-',
            'App\Models\Country_name' => 'Juan de Nova Island',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 127,
            'cc_fips' => 'KE',
            'cc_iso' => 'KE',
            'tld' => '.ke',
            'App\Models\Country_name' => 'Kenya',
            'App\Models\Country_phone_code' => 404
        ]);



        App\Models\Country::create([
            'id' => 128,
            'cc_fips' => 'KG',
            'cc_iso' => 'KG',
            'tld' => '.kg',
            'App\Models\Country_name' => 'Kyrgyzstan',
            'App\Models\Country_phone_code' => 417
        ]);



        App\Models\Country::create([
            'id' => 129,
            'cc_fips' => 'KN',
            'cc_iso' => 'KP',
            'tld' => '.kp',
            'App\Models\Country_name' => 'Korea, North',
            'App\Models\Country_phone_code' => 659
        ]);



        App\Models\Country::create([
            'id' => 130,
            'cc_fips' => 'KR',
            'cc_iso' => 'KI',
            'tld' => '.ki',
            'App\Models\Country_name' => 'Kiribati',
            'App\Models\Country_phone_code' => 410
        ]);



        App\Models\Country::create([
            'id' => 131,
            'cc_fips' => 'KS',
            'cc_iso' => 'KR',
            'tld' => '.kr',
            'App\Models\Country_name' => 'Korea, South',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 132,
            'cc_fips' => 'KT',
            'cc_iso' => 'CX',
            'tld' => '.cx',
            'App\Models\Country_name' => 'Christmas Island',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 133,
            'cc_fips' => 'KU',
            'cc_iso' => 'KW',
            'tld' => '.kw',
            'App\Models\Country_name' => 'Kuwait',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 134,
            'cc_fips' => 'KV',
            'cc_iso' => 'XK',
            'tld' => '-',
            'App\Models\Country_name' => 'Kosovo',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 135,
            'cc_fips' => 'KZ',
            'cc_iso' => 'KZ',
            'tld' => '.kz',
            'App\Models\Country_name' => 'Kazakhstan',
            'App\Models\Country_phone_code' => 398
        ]);



        App\Models\Country::create([
            'id' => 136,
            'cc_fips' => 'LA',
            'cc_iso' => 'LA',
            'tld' => '.la',
            'App\Models\Country_name' => 'Laos',
            'App\Models\Country_phone_code' => 418
        ]);



        App\Models\Country::create([
            'id' => 137,
            'cc_fips' => 'LE',
            'cc_iso' => 'LB',
            'tld' => '.lb',
            'App\Models\Country_name' => 'Lebanon',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 138,
            'cc_fips' => 'LG',
            'cc_iso' => 'LV',
            'tld' => '.lv',
            'App\Models\Country_name' => 'Latvia',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 139,
            'cc_fips' => 'LH',
            'cc_iso' => 'LT',
            'tld' => '.lt',
            'App\Models\Country_name' => 'Lithuania',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 140,
            'cc_fips' => 'LI',
            'cc_iso' => 'LR',
            'tld' => '.lr',
            'App\Models\Country_name' => 'Liberia',
            'App\Models\Country_phone_code' => 438
        ]);



        App\Models\Country::create([
            'id' => 141,
            'cc_fips' => 'LO',
            'cc_iso' => 'SK',
            'tld' => '.sk',
            'App\Models\Country_name' => 'Slovakia',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 142,
            'cc_fips' => '-',
            'cc_iso' => 'UM',
            'tld' => '.us',
            'App\Models\Country_name' => 'United States Minor Outlying Islands',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 143,
            'cc_fips' => 'LS',
            'cc_iso' => 'LI',
            'tld' => '.li',
            'App\Models\Country_name' => 'Liechtenstein',
            'App\Models\Country_phone_code' => 426
        ]);



        App\Models\Country::create([
            'id' => 144,
            'cc_fips' => 'LT',
            'cc_iso' => 'LS',
            'tld' => '.ls',
            'App\Models\Country_name' => 'Lesotho',
            'App\Models\Country_phone_code' => 440
        ]);



        App\Models\Country::create([
            'id' => 145,
            'cc_fips' => 'LU',
            'cc_iso' => 'LU',
            'tld' => '.lu',
            'App\Models\Country_name' => 'Luxembourg',
            'App\Models\Country_phone_code' => 442
        ]);



        App\Models\Country::create([
            'id' => 146,
            'cc_fips' => 'LY',
            'cc_iso' => 'LY',
            'tld' => '.ly',
            'App\Models\Country_name' => 'Libya',
            'App\Models\Country_phone_code' => 434
        ]);



        App\Models\Country::create([
            'id' => 147,
            'cc_fips' => 'MA',
            'cc_iso' => 'MG',
            'tld' => '.mg',
            'App\Models\Country_name' => 'Madagascar',
            'App\Models\Country_phone_code' => 504
        ]);



        App\Models\Country::create([
            'id' => 148,
            'cc_fips' => 'MB',
            'cc_iso' => 'MQ',
            'tld' => '.mq',
            'App\Models\Country_name' => 'Martinique',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 149,
            'cc_fips' => 'MC',
            'cc_iso' => 'MO',
            'tld' => '.mo',
            'App\Models\Country_name' => 'Macau',
            'App\Models\Country_phone_code' => 492
        ]);



        App\Models\Country::create([
            'id' => 150,
            'cc_fips' => 'MD',
            'cc_iso' => 'MD',
            'tld' => '.md',
            'App\Models\Country_name' => 'Moldova, Republic of',
            'App\Models\Country_phone_code' => 498
        ]);



        App\Models\Country::create([
            'id' => 151,
            'cc_fips' => 'MF',
            'cc_iso' => 'YT',
            'tld' => '.yt',
            'App\Models\Country_name' => 'Mayotte',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 152,
            'cc_fips' => 'MG',
            'cc_iso' => 'MN',
            'tld' => '.mn',
            'App\Models\Country_name' => 'Mongolia',
            'App\Models\Country_phone_code' => 450
        ]);



        App\Models\Country::create([
            'id' => 153,
            'cc_fips' => 'MH',
            'cc_iso' => 'MS',
            'tld' => '.ms',
            'App\Models\Country_name' => 'Montserrat',
            'App\Models\Country_phone_code' => 584
        ]);



        App\Models\Country::create([
            'id' => 154,
            'cc_fips' => 'MI',
            'cc_iso' => 'MW',
            'tld' => '.mw',
            'App\Models\Country_name' => 'Malawi',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 155,
            'cc_fips' => 'MJ',
            'cc_iso' => 'ME',
            'tld' => '.me',
            'App\Models\Country_name' => 'Montenegro',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 156,
            'cc_fips' => 'MK',
            'cc_iso' => 'MK',
            'tld' => '.mk',
            'App\Models\Country_name' => 'The Former Yugoslav Republic of Macedonia',
            'App\Models\Country_phone_code' => 807
        ]);



        App\Models\Country::create([
            'id' => 157,
            'cc_fips' => 'ML',
            'cc_iso' => 'ML',
            'tld' => '.ml',
            'App\Models\Country_name' => 'Mali',
            'App\Models\Country_phone_code' => 466
        ]);



        App\Models\Country::create([
            'id' => 158,
            'cc_fips' => 'MN',
            'cc_iso' => 'MC',
            'tld' => '.mc',
            'App\Models\Country_name' => 'Monaco',
            'App\Models\Country_phone_code' => 496
        ]);



        App\Models\Country::create([
            'id' => 159,
            'cc_fips' => 'MO',
            'cc_iso' => 'MA',
            'tld' => '.ma',
            'App\Models\Country_name' => 'Morocco',
            'App\Models\Country_phone_code' => 446
        ]);



        App\Models\Country::create([
            'id' => 160,
            'cc_fips' => 'MP',
            'cc_iso' => 'MU',
            'tld' => '.mu',
            'App\Models\Country_name' => 'Mauritius',
            'App\Models\Country_phone_code' => 580
        ]);



        App\Models\Country::create([
            'id' => 161,
            'cc_fips' => 'MR',
            'cc_iso' => 'MR',
            'tld' => '.mr',
            'App\Models\Country_name' => 'Mauritania',
            'App\Models\Country_phone_code' => 478
        ]);



        App\Models\Country::create([
            'id' => 162,
            'cc_fips' => 'MT',
            'cc_iso' => 'MT',
            'tld' => '.mt',
            'App\Models\Country_name' => 'Malta',
            'App\Models\Country_phone_code' => 470
        ]);



        App\Models\Country::create([
            'id' => 163,
            'cc_fips' => 'MU',
            'cc_iso' => 'OM',
            'tld' => '.om',
            'App\Models\Country_name' => 'Oman',
            'App\Models\Country_phone_code' => 480
        ]);



        App\Models\Country::create([
            'id' => 164,
            'cc_fips' => 'MV',
            'cc_iso' => 'MV',
            'tld' => '.mv',
            'App\Models\Country_name' => 'Maldives',
            'App\Models\Country_phone_code' => 462
        ]);



        App\Models\Country::create([
            'id' => 165,
            'cc_fips' => 'MX',
            'cc_iso' => 'MX',
            'tld' => '.mx',
            'App\Models\Country_name' => 'Mexico',
            'App\Models\Country_phone_code' => 484
        ]);



        App\Models\Country::create([
            'id' => 166,
            'cc_fips' => 'MY',
            'cc_iso' => 'MY',
            'tld' => '.my',
            'App\Models\Country_name' => 'Malaysia',
            'App\Models\Country_phone_code' => 458
        ]);



        App\Models\Country::create([
            'id' => 167,
            'cc_fips' => 'MZ',
            'cc_iso' => 'MZ',
            'tld' => '.mz',
            'App\Models\Country_name' => 'Mozambique',
            'App\Models\Country_phone_code' => 508
        ]);



        App\Models\Country::create([
            'id' => 168,
            'cc_fips' => 'NC',
            'cc_iso' => 'NC',
            'tld' => '.nc',
            'App\Models\Country_name' => 'New Caledonia',
            'App\Models\Country_phone_code' => 540
        ]);



        App\Models\Country::create([
            'id' => 169,
            'cc_fips' => 'NE',
            'cc_iso' => 'NU',
            'tld' => '.nu',
            'App\Models\Country_name' => 'Niue',
            'App\Models\Country_phone_code' => 562
        ]);



        App\Models\Country::create([
            'id' => 170,
            'cc_fips' => 'NF',
            'cc_iso' => 'NF',
            'tld' => '.nf',
            'App\Models\Country_name' => 'Norfolk Island',
            'App\Models\Country_phone_code' => 574
        ]);



        App\Models\Country::create([
            'id' => 171,
            'cc_fips' => 'NG',
            'cc_iso' => 'NE',
            'tld' => '.ne',
            'App\Models\Country_name' => 'Niger',
            'App\Models\Country_phone_code' => 566
        ]);



        App\Models\Country::create([
            'id' => 172,
            'cc_fips' => 'NH',
            'cc_iso' => 'VU',
            'tld' => '.vu',
            'App\Models\Country_name' => 'Vanuatu',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 173,
            'cc_fips' => 'NI',
            'cc_iso' => 'NG',
            'tld' => '.ng',
            'App\Models\Country_name' => 'Nigeria',
            'App\Models\Country_phone_code' => 558
        ]);



        App\Models\Country::create([
            'id' => 174,
            'cc_fips' => 'NL',
            'cc_iso' => 'NL',
            'tld' => '.nl',
            'App\Models\Country_name' => 'Netherlands',
            'App\Models\Country_phone_code' => 528
        ]);



        App\Models\Country::create([
            'id' => 175,
            'cc_fips' => 'NM',
            'cc_iso' => '',
            'tld' => '',
            'App\Models\Country_name' => 'No Man\'s Land',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 176,
            'cc_fips' => 'NO',
            'cc_iso' => 'NO',
            'tld' => '.no',
            'App\Models\Country_name' => 'Norway',
            'App\Models\Country_phone_code' => 578
        ]);



        App\Models\Country::create([
            'id' => 177,
            'cc_fips' => 'NP',
            'cc_iso' => 'NP',
            'tld' => '.np',
            'App\Models\Country_name' => 'Nepal',
            'App\Models\Country_phone_code' => 524
        ]);



        App\Models\Country::create([
            'id' => 178,
            'cc_fips' => 'NR',
            'cc_iso' => 'NR',
            'tld' => '.nr',
            'App\Models\Country_name' => 'Nauru',
            'App\Models\Country_phone_code' => 520
        ]);



        App\Models\Country::create([
            'id' => 179,
            'cc_fips' => 'NS',
            'cc_iso' => 'SR',
            'tld' => '.sr',
            'App\Models\Country_name' => 'Suriname',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 180,
            'cc_fips' => '-',
            'cc_iso' => 'BQ',
            'tld' => '.bq',
            'App\Models\Country_name' => 'Bonaire, Sint Eustatius and Saba',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 181,
            'cc_fips' => 'NU',
            'cc_iso' => 'NI',
            'tld' => '.ni',
            'App\Models\Country_name' => 'Nicaragua',
            'App\Models\Country_phone_code' => 570
        ]);



        App\Models\Country::create([
            'id' => 182,
            'cc_fips' => 'NZ',
            'cc_iso' => 'NZ',
            'tld' => '.nz',
            'App\Models\Country_name' => 'New Zealand',
            'App\Models\Country_phone_code' => 554
        ]);



        App\Models\Country::create([
            'id' => 183,
            'cc_fips' => 'PA',
            'cc_iso' => 'PY',
            'tld' => '.py',
            'App\Models\Country_name' => 'Paraguay',
            'App\Models\Country_phone_code' => 591
        ]);



        App\Models\Country::create([
            'id' => 184,
            'cc_fips' => 'PC',
            'cc_iso' => 'PN',
            'tld' => '.pn',
            'App\Models\Country_name' => 'Pitcairn Islands',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 185,
            'cc_fips' => 'PE',
            'cc_iso' => 'PE',
            'tld' => '.pe',
            'App\Models\Country_name' => 'Peru',
            'App\Models\Country_phone_code' => 604
        ]);



        App\Models\Country::create([
            'id' => 186,
            'cc_fips' => 'PF',
            'cc_iso' => '-',
            'tld' => '-',
            'App\Models\Country_name' => 'Paracel Islands',
            'App\Models\Country_phone_code' => 258
        ]);



        App\Models\Country::create([
            'id' => 187,
            'cc_fips' => 'PG',
            'cc_iso' => '-',
            'tld' => '-',
            'App\Models\Country_name' => 'Spratly Islands',
            'App\Models\Country_phone_code' => 598
        ]);



        App\Models\Country::create([
            'id' => 188,
            'cc_fips' => 'PK',
            'cc_iso' => 'PK',
            'tld' => '.pk',
            'App\Models\Country_name' => 'Pakistan',
            'App\Models\Country_phone_code' => 586
        ]);



        App\Models\Country::create([
            'id' => 189,
            'cc_fips' => 'PL',
            'cc_iso' => 'PL',
            'tld' => '.pl',
            'App\Models\Country_name' => 'Poland',
            'App\Models\Country_phone_code' => 616
        ]);



        App\Models\Country::create([
            'id' => 190,
            'cc_fips' => 'PM',
            'cc_iso' => 'PA',
            'tld' => '.pa',
            'App\Models\Country_name' => 'Panama',
            'App\Models\Country_phone_code' => 666
        ]);



        App\Models\Country::create([
            'id' => 191,
            'cc_fips' => 'PO',
            'cc_iso' => 'PT',
            'tld' => '.pt',
            'App\Models\Country_name' => 'Portugal',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 192,
            'cc_fips' => 'PP',
            'cc_iso' => 'PG',
            'tld' => '.pg',
            'App\Models\Country_name' => 'Papua New Guinea',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 193,
            'cc_fips' => 'PS',
            'cc_iso' => 'PW',
            'tld' => '.pw',
            'App\Models\Country_name' => 'Palau',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 194,
            'cc_fips' => 'PU',
            'cc_iso' => 'GW',
            'tld' => '.gw',
            'App\Models\Country_name' => 'Guinea-Bissau',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 195,
            'cc_fips' => 'QA',
            'cc_iso' => 'QA',
            'tld' => '.qa',
            'App\Models\Country_name' => 'Qatar',
            'App\Models\Country_phone_code' => 634
        ]);



        App\Models\Country::create([
            'id' => 196,
            'cc_fips' => 'RE',
            'cc_iso' => 'RE',
            'tld' => '.re',
            'App\Models\Country_name' => 'Reunion',
            'App\Models\Country_phone_code' => 638
        ]);



        App\Models\Country::create([
            'id' => 197,
            'cc_fips' => 'RI',
            'cc_iso' => 'RS',
            'tld' => '.rs',
            'App\Models\Country_name' => 'Serbia',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 198,
            'cc_fips' => 'RM',
            'cc_iso' => 'MH',
            'tld' => '.mh',
            'App\Models\Country_name' => 'Marshall Islands',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 199,
            'cc_fips' => 'RN',
            'cc_iso' => 'MF',
            'tld' => '-',
            'App\Models\Country_name' => 'Saint Martin',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 200,
            'cc_fips' => 'RO',
            'cc_iso' => 'RO',
            'tld' => '.ro',
            'App\Models\Country_name' => 'Romania',
            'App\Models\Country_phone_code' => 642
        ]);



        App\Models\Country::create([
            'id' => 201,
            'cc_fips' => 'RP',
            'cc_iso' => 'PH',
            'tld' => '.ph',
            'App\Models\Country_name' => 'Philippines',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 202,
            'cc_fips' => 'RQ',
            'cc_iso' => 'PR',
            'tld' => '.pr',
            'App\Models\Country_name' => 'Puerto Rico',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 203,
            'cc_fips' => 'RS',
            'cc_iso' => 'RU',
            'tld' => '.ru',
            'App\Models\Country_name' => 'Russia',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 204,
            'cc_fips' => 'RW',
            'cc_iso' => 'RW',
            'tld' => '.rw',
            'App\Models\Country_name' => 'Rwanda',
            'App\Models\Country_phone_code' => 646
        ]);



        App\Models\Country::create([
            'id' => 205,
            'cc_fips' => 'SA',
            'cc_iso' => 'SA',
            'tld' => '.sa',
            'App\Models\Country_name' => 'Saudi Arabia',
            'App\Models\Country_phone_code' => 682
        ]);



        App\Models\Country::create([
            'id' => 206,
            'cc_fips' => 'SB',
            'cc_iso' => 'PM',
            'tld' => '.pm',
            'App\Models\Country_name' => 'Saint Pierre and Miquelon',
            'App\Models\Country_phone_code' => 90
        ]);



        App\Models\Country::create([
            'id' => 207,
            'cc_fips' => 'SC',
            'cc_iso' => 'KN',
            'tld' => '.kn',
            'App\Models\Country_name' => 'Saint Kitts and Nevis',
            'App\Models\Country_phone_code' => 690
        ]);



        App\Models\Country::create([
            'id' => 208,
            'cc_fips' => 'SE',
            'cc_iso' => 'SC',
            'tld' => '.sc',
            'App\Models\Country_name' => 'Seychelles',
            'App\Models\Country_phone_code' => 752
        ]);



        App\Models\Country::create([
            'id' => 209,
            'cc_fips' => 'SF',
            'cc_iso' => 'ZA',
            'tld' => '.za',
            'App\Models\Country_name' => 'South Africa',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 210,
            'cc_fips' => 'SG',
            'cc_iso' => 'SN',
            'tld' => '.sn',
            'App\Models\Country_name' => 'Senegal',
            'App\Models\Country_phone_code' => 702
        ]);



        App\Models\Country::create([
            'id' => 211,
            'cc_fips' => 'SH',
            'cc_iso' => 'SH',
            'tld' => '.sh',
            'App\Models\Country_name' => 'Saint Helena',
            'App\Models\Country_phone_code' => 654
        ]);



        App\Models\Country::create([
            'id' => 212,
            'cc_fips' => 'SI',
            'cc_iso' => 'SI',
            'tld' => '.si',
            'App\Models\Country_name' => 'Slovenia',
            'App\Models\Country_phone_code' => 705
        ]);



        App\Models\Country::create([
            'id' => 213,
            'cc_fips' => 'SL',
            'cc_iso' => 'SL',
            'tld' => '.sl',
            'App\Models\Country_name' => 'Sierra Leone',
            'App\Models\Country_phone_code' => 694
        ]);



        App\Models\Country::create([
            'id' => 214,
            'cc_fips' => 'SM',
            'cc_iso' => 'SM',
            'tld' => '.sm',
            'App\Models\Country_name' => 'San Marino',
            'App\Models\Country_phone_code' => 674
        ]);



        App\Models\Country::create([
            'id' => 215,
            'cc_fips' => 'SN',
            'cc_iso' => 'SG',
            'tld' => '.sg',
            'App\Models\Country_name' => 'Singapore',
            'App\Models\Country_phone_code' => 686
        ]);



        App\Models\Country::create([
            'id' => 216,
            'cc_fips' => 'SO',
            'cc_iso' => 'SO',
            'tld' => '.so',
            'App\Models\Country_name' => 'Somalia',
            'App\Models\Country_phone_code' => 706
        ]);



        App\Models\Country::create([
            'id' => 217,
            'cc_fips' => 'SP',
            'cc_iso' => 'ES',
            'tld' => '.es',
            'App\Models\Country_name' => 'Spain',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 218,
            'cc_fips' => '-',
            'cc_iso' => 'SS',
            'tld' => '.ss',
            'App\Models\Country_name' => 'South Sudan',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 219,
            'cc_fips' => 'ST',
            'cc_iso' => 'LC',
            'tld' => '.lc',
            'App\Models\Country_name' => 'Saint Lucia',
            'App\Models\Country_phone_code' => 678
        ]);



        App\Models\Country::create([
            'id' => 220,
            'cc_fips' => 'SU',
            'cc_iso' => 'SD',
            'tld' => '.sd',
            'App\Models\Country_name' => 'Sudan',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 221,
            'cc_fips' => 'SV',
            'cc_iso' => 'SJ',
            'tld' => '.sj',
            'App\Models\Country_name' => 'Svalbard',
            'App\Models\Country_phone_code' => 222
        ]);



        App\Models\Country::create([
            'id' => 222,
            'cc_fips' => 'SW',
            'cc_iso' => 'SE',
            'tld' => '.se',
            'App\Models\Country_name' => 'Sweden',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 223,
            'cc_fips' => 'SX',
            'cc_iso' => 'GS',
            'tld' => '.gs',
            'App\Models\Country_name' => 'South Georgia and the Islands',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 224,
            'cc_fips' => 'NN',
            'cc_iso' => 'SX',
            'tld' => '.sx',
            'App\Models\Country_name' => 'Sint Maarten',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 225,
            'cc_fips' => 'SY',
            'cc_iso' => 'SY',
            'tld' => '.sy',
            'App\Models\Country_name' => 'Syrian Arab Republic',
            'App\Models\Country_phone_code' => 760
        ]);



        App\Models\Country::create([
            'id' => 226,
            'cc_fips' => 'SZ',
            'cc_iso' => 'CH',
            'tld' => '.ch',
            'App\Models\Country_name' => 'Switzerland',
            'App\Models\Country_phone_code' => 748
        ]);



        App\Models\Country::create([
            'id' => 227,
            'cc_fips' => 'TD',
            'cc_iso' => 'TT',
            'tld' => '.tt',
            'App\Models\Country_name' => 'Trinidad and Tobago',
            'App\Models\Country_phone_code' => 148
        ]);



        App\Models\Country::create([
            'id' => 228,
            'cc_fips' => 'TE',
            'cc_iso' => '-',
            'tld' => '-',
            'App\Models\Country_name' => 'Tromelin Island',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 229,
            'cc_fips' => 'TH',
            'cc_iso' => 'TH',
            'tld' => '.th',
            'App\Models\Country_name' => 'Thailand',
            'App\Models\Country_phone_code' => 764
        ]);



        App\Models\Country::create([
            'id' => 230,
            'cc_fips' => 'TI',
            'cc_iso' => 'TJ',
            'tld' => '.tj',
            'App\Models\Country_name' => 'Tajikistan',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 231,
            'cc_fips' => 'TK',
            'cc_iso' => 'TC',
            'tld' => '.tc',
            'App\Models\Country_name' => 'Turks and Caicos Islands',
            'App\Models\Country_phone_code' => 772
        ]);



        App\Models\Country::create([
            'id' => 232,
            'cc_fips' => 'TL',
            'cc_iso' => 'TK',
            'tld' => '.tk',
            'App\Models\Country_name' => 'Tokelau',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 233,
            'cc_fips' => 'TN',
            'cc_iso' => 'TO',
            'tld' => '.to',
            'App\Models\Country_name' => 'Tonga',
            'App\Models\Country_phone_code' => 788
        ]);



        App\Models\Country::create([
            'id' => 234,
            'cc_fips' => 'TO',
            'cc_iso' => 'TG',
            'tld' => '.tg',
            'App\Models\Country_name' => 'Togo',
            'App\Models\Country_phone_code' => 776
        ]);



        App\Models\Country::create([
            'id' => 235,
            'cc_fips' => 'TP',
            'cc_iso' => 'ST',
            'tld' => '.st',
            'App\Models\Country_name' => 'Sao Tome and Principe',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 236,
            'cc_fips' => 'TS',
            'cc_iso' => 'TN',
            'tld' => '.tn',
            'App\Models\Country_name' => 'Tunisia',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 237,
            'cc_fips' => 'TT',
            'cc_iso' => 'TL',
            'tld' => '.tl',
            'App\Models\Country_name' => 'East Timor',
            'App\Models\Country_phone_code' => 780
        ]);



        App\Models\Country::create([
            'id' => 238,
            'cc_fips' => 'TU',
            'cc_iso' => 'TR',
            'tld' => '.tr',
            'App\Models\Country_name' => 'Turkey',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 239,
            'cc_fips' => 'TV',
            'cc_iso' => 'TV',
            'tld' => '.tv',
            'App\Models\Country_name' => 'Tuvalu',
            'App\Models\Country_phone_code' => 798
        ]);



        App\Models\Country::create([
            'id' => 240,
            'cc_fips' => 'TW',
            'cc_iso' => 'TW',
            'tld' => '.tw',
            'App\Models\Country_name' => 'Taiwan',
            'App\Models\Country_phone_code' => 158
        ]);



        App\Models\Country::create([
            'id' => 241,
            'cc_fips' => 'TX',
            'cc_iso' => 'TM',
            'tld' => '.tm',
            'App\Models\Country_name' => 'Turkmenistan',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 242,
            'cc_fips' => 'TZ',
            'cc_iso' => 'TZ',
            'tld' => '.tz',
            'App\Models\Country_name' => 'Tanzania, United Republic of',
            'App\Models\Country_phone_code' => 834
        ]);



        App\Models\Country::create([
            'id' => 243,
            'cc_fips' => 'UC',
            'cc_iso' => 'CW',
            'tld' => '.cw',
            'App\Models\Country_name' => 'Curacao',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 244,
            'cc_fips' => 'UG',
            'cc_iso' => 'UG',
            'tld' => '.ug',
            'App\Models\Country_name' => 'Uganda',
            'App\Models\Country_phone_code' => 800
        ]);



        App\Models\Country::create([
            'id' => 245,
            'cc_fips' => 'UK',
            'cc_iso' => 'GB',
            'tld' => '.uk',
            'App\Models\Country_name' => 'United Kingdom',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 246,
            'cc_fips' => 'UP',
            'cc_iso' => 'UA',
            'tld' => '.ua',
            'App\Models\Country_name' => 'Ukraine',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 247,
            'cc_fips' => 'US',
            'cc_iso' => 'US',
            'tld' => '.us',
            'App\Models\Country_name' => 'United States',
            'App\Models\Country_phone_code' => 840
        ]);



        App\Models\Country::create([
            'id' => 248,
            'cc_fips' => 'UV',
            'cc_iso' => 'BF',
            'tld' => '.bf',
            'App\Models\Country_name' => 'Burkina Faso',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 249,
            'cc_fips' => 'UY',
            'cc_iso' => 'UY',
            'tld' => '.uy',
            'App\Models\Country_name' => 'Uruguay',
            'App\Models\Country_phone_code' => 858
        ]);



        App\Models\Country::create([
            'id' => 250,
            'cc_fips' => 'UZ',
            'cc_iso' => 'UZ',
            'tld' => '.uz',
            'App\Models\Country_name' => 'Uzbekistan',
            'App\Models\Country_phone_code' => 860
        ]);



        App\Models\Country::create([
            'id' => 251,
            'cc_fips' => 'VC',
            'cc_iso' => 'VC',
            'tld' => '.vc',
            'App\Models\Country_name' => 'Saint Vincent and the Grenadines',
            'App\Models\Country_phone_code' => 670
        ]);



        App\Models\Country::create([
            'id' => 252,
            'cc_fips' => 'VE',
            'cc_iso' => 'VE',
            'tld' => '.ve',
            'App\Models\Country_name' => 'Venezuela',
            'App\Models\Country_phone_code' => 862
        ]);



        App\Models\Country::create([
            'id' => 253,
            'cc_fips' => 'VI',
            'cc_iso' => 'VG',
            'tld' => '.vg',
            'App\Models\Country_name' => 'British Virgin Islands',
            'App\Models\Country_phone_code' => 850
        ]);



        App\Models\Country::create([
            'id' => 254,
            'cc_fips' => 'VM',
            'cc_iso' => 'VN',
            'tld' => '.vn',
            'App\Models\Country_name' => 'Vietnam',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 255,
            'cc_fips' => 'VQ',
            'cc_iso' => 'VI',
            'tld' => '.vi',
            'App\Models\Country_name' => 'Virgin Islands (US)',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 256,
            'cc_fips' => 'VT',
            'cc_iso' => 'VA',
            'tld' => '.va',
            'App\Models\Country_name' => 'Holy See (Vatican City)',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 257,
            'cc_fips' => 'WA',
            'cc_iso' => 'NA',
            'tld' => '.na',
            'App\Models\Country_name' => 'Namibia',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 258,
            'cc_fips' => 'WE',
            'cc_iso' => 'PS',
            'tld' => '.ps',
            'App\Models\Country_name' => 'Palestine, State of',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 259,
            'cc_fips' => 'WF',
            'cc_iso' => 'WF',
            'tld' => '.wf',
            'App\Models\Country_name' => 'Wallis and Futuna',
            'App\Models\Country_phone_code' => 876
        ]);



        App\Models\Country::create([
            'id' => 260,
            'cc_fips' => 'WI',
            'cc_iso' => 'EH',
            'tld' => '.eh',
            'App\Models\Country_name' => 'Western Sahara',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 261,
            'cc_fips' => 'WS',
            'cc_iso' => 'WS',
            'tld' => '.ws',
            'App\Models\Country_name' => 'Samoa',
            'App\Models\Country_phone_code' => 882
        ]);



        App\Models\Country::create([
            'id' => 262,
            'cc_fips' => 'WZ',
            'cc_iso' => 'SZ',
            'tld' => '.sz',
            'App\Models\Country_name' => 'Swaziland',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 263,
            'cc_fips' => 'YI',
            'cc_iso' => 'CS',
            'tld' => '.yu',
            'App\Models\Country_name' => 'Serbia and Montenegro',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 264,
            'cc_fips' => 'YM',
            'cc_iso' => 'YE',
            'tld' => '.ye',
            'App\Models\Country_name' => 'Yemen',
            'App\Models\Country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 265,
            'cc_fips' => 'ZA',
            'cc_iso' => 'ZM',
            'tld' => '.zm',
            'App\Models\Country_name' => 'Zambia',
            'App\Models\Country_phone_code' => 710
        ]);



        App\Models\Country::create([
            'id' => 266,
            'cc_fips' => 'ZI',
            'cc_iso' => 'ZW',
            'tld' => '.zw',
            'App\Models\Country_name' => 'Zimbabwe',
            'App\Models\Country_phone_code' => NULL
        ]);
    }
}
