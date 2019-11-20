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
            'country_name' => 'Aruba',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 2,
            'cc_fips' => 'AC',
            'cc_iso' => 'AG',
            'tld' => '.ag',
            'country_name' => 'Antigua and Barbuda',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 3,
            'cc_fips' => 'AE',
            'cc_iso' => 'AE',
            'tld' => '.ae',
            'country_name' => 'United Arab Emirates',
            'country_phone_code' => 784
        ]);



        App\Models\Country::create([
            'id' => 4,
            'cc_fips' => 'AF',
            'cc_iso' => 'AF',
            'tld' => '.af',
            'country_name' => 'Afghanistan',
            'country_phone_code' => 4
        ]);



        App\Models\Country::create([
            'id' => 5,
            'cc_fips' => 'AG',
            'cc_iso' => 'DZ',
            'tld' => '.dz',
            'country_name' => 'Algeria',
            'country_phone_code' => 28
        ]);



        App\Models\Country::create([
            'id' => 6,
            'cc_fips' => 'AJ',
            'cc_iso' => 'AZ',
            'tld' => '.az',
            'country_name' => 'Azerbaijan',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 7,
            'cc_fips' => 'AL',
            'cc_iso' => 'AL',
            'tld' => '.al',
            'country_name' => 'Albania',
            'country_phone_code' => 8
        ]);



        App\Models\Country::create([
            'id' => 8,
            'cc_fips' => 'AM',
            'cc_iso' => 'AM',
            'tld' => '.am',
            'country_name' => 'Armenia',
            'country_phone_code' => 51
        ]);



        App\Models\Country::create([
            'id' => 9,
            'cc_fips' => 'AN',
            'cc_iso' => 'AD',
            'tld' => '.ad',
            'country_name' => 'Andorra',
            'country_phone_code' => 530
        ]);



        App\Models\Country::create([
            'id' => 10,
            'cc_fips' => 'AO',
            'cc_iso' => 'AO',
            'tld' => '.ao',
            'country_name' => 'Angola',
            'country_phone_code' => 24
        ]);



        App\Models\Country::create([
            'id' => 11,
            'cc_fips' => 'AQ',
            'cc_iso' => 'AS',
            'tld' => '.as',
            'country_name' => 'American Samoa',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 12,
            'cc_fips' => 'AR',
            'cc_iso' => 'AR',
            'tld' => '.ar',
            'country_name' => 'Argentina',
            'country_phone_code' => 32
        ]);



        App\Models\Country::create([
            'id' => 13,
            'cc_fips' => 'AS',
            'cc_iso' => 'AU',
            'tld' => '.au',
            'country_name' => 'Australia',
            'country_phone_code' => 16
        ]);



        App\Models\Country::create([
            'id' => 14,
            'cc_fips' => 'AT',
            'cc_iso' => '-',
            'tld' => '-',
            'country_name' => 'Ashmore and Cartier Islands',
            'country_phone_code' => 40
        ]);



        App\Models\Country::create([
            'id' => 15,
            'cc_fips' => 'AU',
            'cc_iso' => 'AT',
            'tld' => '.at',
            'country_name' => 'Austria',
            'country_phone_code' => 36
        ]);



        App\Models\Country::create([
            'id' => 16,
            'cc_fips' => 'AV',
            'cc_iso' => 'AI',
            'tld' => '.ai',
            'country_name' => 'Anguilla',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 17,
            'cc_fips' => '-',
            'cc_iso' => 'AX',
            'tld' => '.ax',
            'country_name' => 'Åland Islands',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 18,
            'cc_fips' => 'AY',
            'cc_iso' => 'AQ',
            'tld' => '.aq',
            'country_name' => 'Antarctica',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 19,
            'cc_fips' => 'BA',
            'cc_iso' => 'BH',
            'tld' => '.bh',
            'country_name' => 'Bahrain',
            'country_phone_code' => 70
        ]);



        App\Models\Country::create([
            'id' => 20,
            'cc_fips' => 'BB',
            'cc_iso' => 'BB',
            'tld' => '.bb',
            'country_name' => 'Barbados',
            'country_phone_code' => 52
        ]);



        App\Models\Country::create([
            'id' => 21,
            'cc_fips' => 'BC',
            'cc_iso' => 'BW',
            'tld' => '.bw',
            'country_name' => 'Botswana',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 22,
            'cc_fips' => 'BD',
            'cc_iso' => 'BM',
            'tld' => '.bm',
            'country_name' => 'Bermuda',
            'country_phone_code' => 50
        ]);



        App\Models\Country::create([
            'id' => 23,
            'cc_fips' => 'BE',
            'cc_iso' => 'BE',
            'tld' => '.be',
            'country_name' => 'Belgium',
            'country_phone_code' => 56
        ]);



        App\Models\Country::create([
            'id' => 24,
            'cc_fips' => 'BF',
            'cc_iso' => 'BS',
            'tld' => '.bs',
            'country_name' => 'Bahamas, The',
            'country_phone_code' => 854
        ]);



        App\Models\Country::create([
            'id' => 25,
            'cc_fips' => 'BG',
            'cc_iso' => 'BD',
            'tld' => '.bd',
            'country_name' => 'Bangladesh',
            'country_phone_code' => 100
        ]);



        App\Models\Country::create([
            'id' => 26,
            'cc_fips' => 'BH',
            'cc_iso' => 'BZ',
            'tld' => '.bz',
            'country_name' => 'Belize',
            'country_phone_code' => 48
        ]);



        App\Models\Country::create([
            'id' => 27,
            'cc_fips' => 'BK',
            'cc_iso' => 'BA',
            'tld' => '.ba',
            'country_name' => 'Bosnia and Herzegovina',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 28,
            'cc_fips' => 'BL',
            'cc_iso' => 'BO',
            'tld' => '.bo',
            'country_name' => 'Bolivia',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 29,
            'cc_fips' => 'TB',
            'cc_iso' => 'BL',
            'tld' => '.bl',
            'country_name' => 'Saint Barthelemy',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 30,
            'cc_fips' => 'BM',
            'cc_iso' => 'MM',
            'tld' => '.mm',
            'country_name' => 'Myanmar',
            'country_phone_code' => 60
        ]);



        App\Models\Country::create([
            'id' => 31,
            'cc_fips' => 'BN',
            'cc_iso' => 'BJ',
            'tld' => '.bj',
            'country_name' => 'Benin',
            'country_phone_code' => 96
        ]);



        App\Models\Country::create([
            'id' => 32,
            'cc_fips' => 'BO',
            'cc_iso' => 'BY',
            'tld' => '.by',
            'country_name' => 'Belarus',
            'country_phone_code' => 68
        ]);



        App\Models\Country::create([
            'id' => 33,
            'cc_fips' => 'BP',
            'cc_iso' => 'SB',
            'tld' => '.sb',
            'country_name' => 'Solomon Islands',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 34,
            'cc_fips' => 'BQ',
            'cc_iso' => '-',
            'tld' => '-',
            'country_name' => 'Navassa Island',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 35,
            'cc_fips' => 'BR',
            'cc_iso' => 'BR',
            'tld' => '.br',
            'country_name' => 'Brazil',
            'country_phone_code' => 76
        ]);



        App\Models\Country::create([
            'id' => 36,
            'cc_fips' => 'BS',
            'cc_iso' => '-',
            'tld' => '-',
            'country_name' => 'Bassas da India',
            'country_phone_code' => 44
        ]);



        App\Models\Country::create([
            'id' => 37,
            'cc_fips' => 'BT',
            'cc_iso' => 'BT',
            'tld' => '.bt',
            'country_name' => 'Bhutan',
            'country_phone_code' => 64
        ]);



        App\Models\Country::create([
            'id' => 38,
            'cc_fips' => 'BU',
            'cc_iso' => 'BG',
            'tld' => '.bg',
            'country_name' => 'Bulgaria',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 39,
            'cc_fips' => 'BV',
            'cc_iso' => 'BV',
            'tld' => '.bv',
            'country_name' => 'Bouvet Island',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 40,
            'cc_fips' => 'BX',
            'cc_iso' => 'BN',
            'tld' => '.bn',
            'country_name' => 'Brunei',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 41,
            'cc_fips' => 'BY',
            'cc_iso' => 'BI',
            'tld' => '.bi',
            'country_name' => 'Burundi',
            'country_phone_code' => 112
        ]);



        App\Models\Country::create([
            'id' => 42,
            'cc_fips' => 'CA',
            'cc_iso' => 'CA',
            'tld' => '.ca',
            'country_name' => 'Canada',
            'country_phone_code' => 124
        ]);



        App\Models\Country::create([
            'id' => 43,
            'cc_fips' => 'CB',
            'cc_iso' => 'KH',
            'tld' => '.kh',
            'country_name' => 'Cambodia',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 44,
            'cc_fips' => 'CD',
            'cc_iso' => 'TD',
            'tld' => '.td',
            'country_name' => 'Chad',
            'country_phone_code' => 180
        ]);



        App\Models\Country::create([
            'id' => 45,
            'cc_fips' => 'CE',
            'cc_iso' => 'LK',
            'tld' => '.lk',
            'country_name' => 'Sri Lanka',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 46,
            'cc_fips' => 'CF',
            'cc_iso' => 'CG',
            'tld' => '.cg',
            'country_name' => 'Congo, Republic of the',
            'country_phone_code' => 140
        ]);



        App\Models\Country::create([
            'id' => 47,
            'cc_fips' => 'CG',
            'cc_iso' => 'CD',
            'tld' => '.cd',
            'country_name' => 'Congo, Democratic Republic of the',
            'country_phone_code' => 178
        ]);



        App\Models\Country::create([
            'id' => 48,
            'cc_fips' => 'CH',
            'cc_iso' => 'CN',
            'tld' => '.cn',
            'country_name' => 'China',
            'country_phone_code' => 756
        ]);



        App\Models\Country::create([
            'id' => 49,
            'cc_fips' => 'CI',
            'cc_iso' => 'CL',
            'tld' => '.cl',
            'country_name' => 'Chile',
            'country_phone_code' => 384
        ]);



        App\Models\Country::create([
            'id' => 50,
            'cc_fips' => 'CJ',
            'cc_iso' => 'KY',
            'tld' => '.ky',
            'country_name' => 'Cayman Islands',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 51,
            'cc_fips' => 'CK',
            'cc_iso' => 'CC',
            'tld' => '.cc',
            'country_name' => 'Cocos (Keeling) Islands',
            'country_phone_code' => 184
        ]);



        App\Models\Country::create([
            'id' => 52,
            'cc_fips' => 'CM',
            'cc_iso' => 'CM',
            'tld' => '.cm',
            'country_name' => 'Cameroon',
            'country_phone_code' => 120
        ]);



        App\Models\Country::create([
            'id' => 53,
            'cc_fips' => 'CN',
            'cc_iso' => 'KM',
            'tld' => '.km',
            'country_name' => 'Comoros',
            'country_phone_code' => 156
        ]);



        App\Models\Country::create([
            'id' => 54,
            'cc_fips' => 'CO',
            'cc_iso' => 'CO',
            'tld' => '.co',
            'country_name' => 'Colombia',
            'country_phone_code' => 170
        ]);



        App\Models\Country::create([
            'id' => 55,
            'cc_fips' => 'CQ',
            'cc_iso' => 'MP',
            'tld' => '.mp',
            'country_name' => 'Northern Mariana Islands',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 56,
            'cc_fips' => 'CR',
            'cc_iso' => '-',
            'tld' => '-',
            'country_name' => 'Coral Sea Islands',
            'country_phone_code' => 188
        ]);



        App\Models\Country::create([
            'id' => 57,
            'cc_fips' => 'CS',
            'cc_iso' => 'CR',
            'tld' => '.cr',
            'country_name' => 'Costa Rica',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 58,
            'cc_fips' => 'CT',
            'cc_iso' => 'CF',
            'tld' => '.cf',
            'country_name' => 'Central African Republic',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 59,
            'cc_fips' => 'CU',
            'cc_iso' => 'CU',
            'tld' => '.cu',
            'country_name' => 'Cuba',
            'country_phone_code' => 192
        ]);



        App\Models\Country::create([
            'id' => 60,
            'cc_fips' => 'CV',
            'cc_iso' => 'CV',
            'tld' => '.cv',
            'country_name' => 'Cape Verde',
            'country_phone_code' => 132
        ]);



        App\Models\Country::create([
            'id' => 61,
            'cc_fips' => 'CW',
            'cc_iso' => 'CK',
            'tld' => '.ck',
            'country_name' => 'Cook Islands',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 62,
            'cc_fips' => 'CY',
            'cc_iso' => 'CY',
            'tld' => '.cy',
            'country_name' => 'Cyprus',
            'country_phone_code' => 196
        ]);



        App\Models\Country::create([
            'id' => 63,
            'cc_fips' => 'DA',
            'cc_iso' => 'DK',
            'tld' => '.dk',
            'country_name' => 'Denmark',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 64,
            'cc_fips' => 'DJ',
            'cc_iso' => 'DJ',
            'tld' => '.dj',
            'country_name' => 'Djibouti',
            'country_phone_code' => 262
        ]);



        App\Models\Country::create([
            'id' => 65,
            'cc_fips' => 'DO',
            'cc_iso' => 'DM',
            'tld' => '.dm',
            'country_name' => 'Dominica',
            'country_phone_code' => 214
        ]);



        App\Models\Country::create([
            'id' => 66,
            'cc_fips' => 'DR',
            'cc_iso' => 'DO',
            'tld' => '.do',
            'country_name' => 'Dominican Republic',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 67,
            'cc_fips' => 'DX',
            'cc_iso' => '-',
            'tld' => '-',
            'country_name' => 'Dhekelia Sovereign Base Area',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 68,
            'cc_fips' => 'EC',
            'cc_iso' => 'EC',
            'tld' => '.ec',
            'country_name' => 'Ecuador',
            'country_phone_code' => 218
        ]);



        App\Models\Country::create([
            'id' => 69,
            'cc_fips' => 'EG',
            'cc_iso' => 'EG',
            'tld' => '.eg',
            'country_name' => 'Egypt',
            'country_phone_code' => 818
        ]);



        App\Models\Country::create([
            'id' => 70,
            'cc_fips' => 'EI',
            'cc_iso' => 'IE',
            'tld' => '.ie',
            'country_name' => 'Ireland',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 71,
            'cc_fips' => 'EK',
            'cc_iso' => 'GQ',
            'tld' => '.gq',
            'country_name' => 'Equatorial Guinea',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 72,
            'cc_fips' => 'EN',
            'cc_iso' => 'EE',
            'tld' => '.ee',
            'country_name' => 'Estonia',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 73,
            'cc_fips' => 'ER',
            'cc_iso' => 'ER',
            'tld' => '.er',
            'country_name' => 'Eritrea',
            'country_phone_code' => 232
        ]);



        App\Models\Country::create([
            'id' => 74,
            'cc_fips' => 'ES',
            'cc_iso' => 'SV',
            'tld' => '.sv',
            'country_name' => 'El Salvador',
            'country_phone_code' => 724
        ]);



        App\Models\Country::create([
            'id' => 75,
            'cc_fips' => 'ET',
            'cc_iso' => 'ET',
            'tld' => '.et',
            'country_name' => 'Ethiopia',
            'country_phone_code' => 231
        ]);



        App\Models\Country::create([
            'id' => 76,
            'cc_fips' => 'EU',
            'cc_iso' => '-',
            'tld' => '-',
            'country_name' => 'Europa Island',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 77,
            'cc_fips' => 'EZ',
            'cc_iso' => 'CZ',
            'tld' => '.cz',
            'country_name' => 'Czech Republic',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 78,
            'cc_fips' => 'FG',
            'cc_iso' => 'GF',
            'tld' => '.gf',
            'country_name' => 'French Guiana',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 79,
            'cc_fips' => 'FI',
            'cc_iso' => 'FI',
            'tld' => '.fi',
            'country_name' => 'Finland',
            'country_phone_code' => 246
        ]);



        App\Models\Country::create([
            'id' => 80,
            'cc_fips' => 'FJ',
            'cc_iso' => 'FJ',
            'tld' => '.fj',
            'country_name' => 'Fiji',
            'country_phone_code' => 242
        ]);



        App\Models\Country::create([
            'id' => 81,
            'cc_fips' => 'FK',
            'cc_iso' => 'FK',
            'tld' => '.fk',
            'country_name' => 'Falkland Islands (Islas Malvinas)',
            'country_phone_code' => 238
        ]);



        App\Models\Country::create([
            'id' => 82,
            'cc_fips' => 'FM',
            'cc_iso' => 'FM',
            'tld' => '.fm',
            'country_name' => 'Micronesia, Federated States of',
            'country_phone_code' => 583
        ]);



        App\Models\Country::create([
            'id' => 83,
            'cc_fips' => 'FO',
            'cc_iso' => 'FO',
            'tld' => '.fo',
            'country_name' => 'Faroe Islands',
            'country_phone_code' => 234
        ]);



        App\Models\Country::create([
            'id' => 84,
            'cc_fips' => 'FP',
            'cc_iso' => 'PF',
            'tld' => '.pf',
            'country_name' => 'French Polynesia',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 85,
            'cc_fips' => 'FR',
            'cc_iso' => 'FR',
            'tld' => '.fr',
            'country_name' => 'France',
            'country_phone_code' => 250
        ]);



        App\Models\Country::create([
            'id' => 86,
            'cc_fips' => 'FS',
            'cc_iso' => 'TF',
            'tld' => '.tf',
            'country_name' => 'French Southern and Antarctic Lands',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 87,
            'cc_fips' => 'GA',
            'cc_iso' => 'GM',
            'tld' => '.gm',
            'country_name' => 'Gambia, The',
            'country_phone_code' => 266
        ]);



        App\Models\Country::create([
            'id' => 88,
            'cc_fips' => 'GB',
            'cc_iso' => 'GA',
            'tld' => '.ga',
            'country_name' => 'Gabon',
            'country_phone_code' => 826
        ]);



        App\Models\Country::create([
            'id' => 89,
            'cc_fips' => 'GG',
            'cc_iso' => 'GE',
            'tld' => '.ge',
            'country_name' => 'Georgia',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 90,
            'cc_fips' => 'GH',
            'cc_iso' => 'GH',
            'tld' => '.gh',
            'country_name' => 'Ghana',
            'country_phone_code' => 288
        ]);



        App\Models\Country::create([
            'id' => 91,
            'cc_fips' => 'GI',
            'cc_iso' => 'GI',
            'tld' => '.gi',
            'country_name' => 'Gibraltar',
            'country_phone_code' => 292
        ]);



        App\Models\Country::create([
            'id' => 92,
            'cc_fips' => 'GJ',
            'cc_iso' => 'GD',
            'tld' => '.gd',
            'country_name' => 'Grenada',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 93,
            'cc_fips' => 'GK',
            'cc_iso' => 'GG',
            'tld' => '.gg',
            'country_name' => 'Guernsey',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 94,
            'cc_fips' => 'GL',
            'cc_iso' => 'GL',
            'tld' => '.gl',
            'country_name' => 'Greenland',
            'country_phone_code' => 304
        ]);



        App\Models\Country::create([
            'id' => 95,
            'cc_fips' => 'GM',
            'cc_iso' => 'DE',
            'tld' => '.de',
            'country_name' => 'Germany',
            'country_phone_code' => 270
        ]);



        App\Models\Country::create([
            'id' => 96,
            'cc_fips' => 'GO',
            'cc_iso' => '-',
            'tld' => '-',
            'country_name' => 'Glorioso Islands',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 97,
            'cc_fips' => 'GP',
            'cc_iso' => 'GP',
            'tld' => '.gp',
            'country_name' => 'Guadeloupe',
            'country_phone_code' => 312
        ]);



        App\Models\Country::create([
            'id' => 98,
            'cc_fips' => 'GQ',
            'cc_iso' => 'GU',
            'tld' => '.gu',
            'country_name' => 'Guam',
            'country_phone_code' => 226
        ]);



        App\Models\Country::create([
            'id' => 99,
            'cc_fips' => 'GR',
            'cc_iso' => 'GR',
            'tld' => '.gr',
            'country_name' => 'Greece',
            'country_phone_code' => 300
        ]);



        App\Models\Country::create([
            'id' => 100,
            'cc_fips' => 'GT',
            'cc_iso' => 'GT',
            'tld' => '.gt',
            'country_name' => 'Guatemala',
            'country_phone_code' => 320
        ]);



        App\Models\Country::create([
            'id' => 101,
            'cc_fips' => 'GV',
            'cc_iso' => 'GN',
            'tld' => '.gn',
            'country_name' => 'Guinea',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 102,
            'cc_fips' => 'GY',
            'cc_iso' => 'GY',
            'tld' => '.gy',
            'country_name' => 'Guyana',
            'country_phone_code' => 328
        ]);



        App\Models\Country::create([
            'id' => 103,
            'cc_fips' => 'GZ',
            'cc_iso' => '-',
            'tld' => '-',
            'country_name' => 'Gaza Strip',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 104,
            'cc_fips' => 'HA',
            'cc_iso' => 'HT',
            'tld' => '.ht',
            'country_name' => 'Haiti',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 105,
            'cc_fips' => 'HK',
            'cc_iso' => 'HK',
            'tld' => '.hk',
            'country_name' => 'Hong Kong',
            'country_phone_code' => 344
        ]);



        App\Models\Country::create([
            'id' => 106,
            'cc_fips' => 'HM',
            'cc_iso' => 'HM',
            'tld' => '.hm',
            'country_name' => 'Heard Island and McDonald Islands',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 107,
            'cc_fips' => 'HO',
            'cc_iso' => 'HN',
            'tld' => '.hn',
            'country_name' => 'Honduras',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 108,
            'cc_fips' => 'HR',
            'cc_iso' => 'HR',
            'tld' => '.hr',
            'country_name' => 'Croatia',
            'country_phone_code' => 191
        ]);



        App\Models\Country::create([
            'id' => 109,
            'cc_fips' => 'HU',
            'cc_iso' => 'HU',
            'tld' => '.hu',
            'country_name' => 'Hungary',
            'country_phone_code' => 348
        ]);



        App\Models\Country::create([
            'id' => 110,
            'cc_fips' => 'IC',
            'cc_iso' => 'IS',
            'tld' => '.is',
            'country_name' => 'Iceland',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 111,
            'cc_fips' => 'ID',
            'cc_iso' => 'ID',
            'tld' => '.id',
            'country_name' => 'Indonesia',
            'country_phone_code' => 360
        ]);



        App\Models\Country::create([
            'id' => 112,
            'cc_fips' => 'IM',
            'cc_iso' => 'IM',
            'tld' => '.im',
            'country_name' => 'Isle of Man',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 113,
            'cc_fips' => 'IN',
            'cc_iso' => 'IN',
            'tld' => '.in',
            'country_name' => 'India',
            'country_phone_code' => 356
        ]);



        App\Models\Country::create([
            'id' => 114,
            'cc_fips' => 'IO',
            'cc_iso' => 'IO',
            'tld' => '.io',
            'country_name' => 'British Indian Ocean Territory',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 115,
            'cc_fips' => 'IP',
            'cc_iso' => '-',
            'tld' => '-',
            'country_name' => 'Clipperton Island',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 116,
            'cc_fips' => 'IR',
            'cc_iso' => 'IR',
            'tld' => '.ir',
            'country_name' => 'Iran',
            'country_phone_code' => 364
        ]);



        App\Models\Country::create([
            'id' => 117,
            'cc_fips' => 'IS',
            'cc_iso' => 'IL',
            'tld' => '.il',
            'country_name' => 'Israel',
            'country_phone_code' => 352
        ]);



        App\Models\Country::create([
            'id' => 118,
            'cc_fips' => 'IT',
            'cc_iso' => 'IT',
            'tld' => '.it',
            'country_name' => 'Italy',
            'country_phone_code' => 380
        ]);



        App\Models\Country::create([
            'id' => 119,
            'cc_fips' => 'IV',
            'cc_iso' => 'CI',
            'tld' => '.ci',
            'country_name' => 'Cote d\'Ivoire',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 120,
            'cc_fips' => 'IZ',
            'cc_iso' => 'IQ',
            'tld' => '.iq',
            'country_name' => 'Iraq',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 121,
            'cc_fips' => 'JA',
            'cc_iso' => 'JP',
            'tld' => '.jp',
            'country_name' => 'Japan',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 122,
            'cc_fips' => 'JE',
            'cc_iso' => 'JE',
            'tld' => '.je',
            'country_name' => 'Jersey',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 123,
            'cc_fips' => 'JM',
            'cc_iso' => 'JM',
            'tld' => '.jm',
            'country_name' => 'Jamaica',
            'country_phone_code' => 388
        ]);



        App\Models\Country::create([
            'id' => 124,
            'cc_fips' => 'JN',
            'cc_iso' => 'SJ',
            'tld' => '-',
            'country_name' => 'Jan Mayen',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 125,
            'cc_fips' => 'JO',
            'cc_iso' => 'JO',
            'tld' => '.jo',
            'country_name' => 'Jordan',
            'country_phone_code' => 400
        ]);



        App\Models\Country::create([
            'id' => 126,
            'cc_fips' => 'JU',
            'cc_iso' => '-',
            'tld' => '-',
            'country_name' => 'Juan de Nova Island',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 127,
            'cc_fips' => 'KE',
            'cc_iso' => 'KE',
            'tld' => '.ke',
            'country_name' => 'Kenya',
            'country_phone_code' => 404
        ]);



        App\Models\Country::create([
            'id' => 128,
            'cc_fips' => 'KG',
            'cc_iso' => 'KG',
            'tld' => '.kg',
            'country_name' => 'Kyrgyzstan',
            'country_phone_code' => 417
        ]);



        App\Models\Country::create([
            'id' => 129,
            'cc_fips' => 'KN',
            'cc_iso' => 'KP',
            'tld' => '.kp',
            'country_name' => 'Korea, North',
            'country_phone_code' => 659
        ]);



        App\Models\Country::create([
            'id' => 130,
            'cc_fips' => 'KR',
            'cc_iso' => 'KI',
            'tld' => '.ki',
            'country_name' => 'Kiribati',
            'country_phone_code' => 410
        ]);



        App\Models\Country::create([
            'id' => 131,
            'cc_fips' => 'KS',
            'cc_iso' => 'KR',
            'tld' => '.kr',
            'country_name' => 'Korea, South',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 132,
            'cc_fips' => 'KT',
            'cc_iso' => 'CX',
            'tld' => '.cx',
            'country_name' => 'Christmas Island',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 133,
            'cc_fips' => 'KU',
            'cc_iso' => 'KW',
            'tld' => '.kw',
            'country_name' => 'Kuwait',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 134,
            'cc_fips' => 'KV',
            'cc_iso' => 'XK',
            'tld' => '-',
            'country_name' => 'Kosovo',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 135,
            'cc_fips' => 'KZ',
            'cc_iso' => 'KZ',
            'tld' => '.kz',
            'country_name' => 'Kazakhstan',
            'country_phone_code' => 398
        ]);



        App\Models\Country::create([
            'id' => 136,
            'cc_fips' => 'LA',
            'cc_iso' => 'LA',
            'tld' => '.la',
            'country_name' => 'Laos',
            'country_phone_code' => 418
        ]);



        App\Models\Country::create([
            'id' => 137,
            'cc_fips' => 'LE',
            'cc_iso' => 'LB',
            'tld' => '.lb',
            'country_name' => 'Lebanon',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 138,
            'cc_fips' => 'LG',
            'cc_iso' => 'LV',
            'tld' => '.lv',
            'country_name' => 'Latvia',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 139,
            'cc_fips' => 'LH',
            'cc_iso' => 'LT',
            'tld' => '.lt',
            'country_name' => 'Lithuania',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 140,
            'cc_fips' => 'LI',
            'cc_iso' => 'LR',
            'tld' => '.lr',
            'country_name' => 'Liberia',
            'country_phone_code' => 438
        ]);



        App\Models\Country::create([
            'id' => 141,
            'cc_fips' => 'LO',
            'cc_iso' => 'SK',
            'tld' => '.sk',
            'country_name' => 'Slovakia',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 142,
            'cc_fips' => '-',
            'cc_iso' => 'UM',
            'tld' => '.us',
            'country_name' => 'United States Minor Outlying Islands',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 143,
            'cc_fips' => 'LS',
            'cc_iso' => 'LI',
            'tld' => '.li',
            'country_name' => 'Liechtenstein',
            'country_phone_code' => 426
        ]);



        App\Models\Country::create([
            'id' => 144,
            'cc_fips' => 'LT',
            'cc_iso' => 'LS',
            'tld' => '.ls',
            'country_name' => 'Lesotho',
            'country_phone_code' => 440
        ]);



        App\Models\Country::create([
            'id' => 145,
            'cc_fips' => 'LU',
            'cc_iso' => 'LU',
            'tld' => '.lu',
            'country_name' => 'Luxembourg',
            'country_phone_code' => 442
        ]);



        App\Models\Country::create([
            'id' => 146,
            'cc_fips' => 'LY',
            'cc_iso' => 'LY',
            'tld' => '.ly',
            'country_name' => 'Libya',
            'country_phone_code' => 434
        ]);



        App\Models\Country::create([
            'id' => 147,
            'cc_fips' => 'MA',
            'cc_iso' => 'MG',
            'tld' => '.mg',
            'country_name' => 'Madagascar',
            'country_phone_code' => 504
        ]);



        App\Models\Country::create([
            'id' => 148,
            'cc_fips' => 'MB',
            'cc_iso' => 'MQ',
            'tld' => '.mq',
            'country_name' => 'Martinique',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 149,
            'cc_fips' => 'MC',
            'cc_iso' => 'MO',
            'tld' => '.mo',
            'country_name' => 'Macau',
            'country_phone_code' => 492
        ]);



        App\Models\Country::create([
            'id' => 150,
            'cc_fips' => 'MD',
            'cc_iso' => 'MD',
            'tld' => '.md',
            'country_name' => 'Moldova, Republic of',
            'country_phone_code' => 498
        ]);



        App\Models\Country::create([
            'id' => 151,
            'cc_fips' => 'MF',
            'cc_iso' => 'YT',
            'tld' => '.yt',
            'country_name' => 'Mayotte',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 152,
            'cc_fips' => 'MG',
            'cc_iso' => 'MN',
            'tld' => '.mn',
            'country_name' => 'Mongolia',
            'country_phone_code' => 450
        ]);



        App\Models\Country::create([
            'id' => 153,
            'cc_fips' => 'MH',
            'cc_iso' => 'MS',
            'tld' => '.ms',
            'country_name' => 'Montserrat',
            'country_phone_code' => 584
        ]);



        App\Models\Country::create([
            'id' => 154,
            'cc_fips' => 'MI',
            'cc_iso' => 'MW',
            'tld' => '.mw',
            'country_name' => 'Malawi',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 155,
            'cc_fips' => 'MJ',
            'cc_iso' => 'ME',
            'tld' => '.me',
            'country_name' => 'Montenegro',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 156,
            'cc_fips' => 'MK',
            'cc_iso' => 'MK',
            'tld' => '.mk',
            'country_name' => 'The Former Yugoslav Republic of Macedonia',
            'country_phone_code' => 807
        ]);



        App\Models\Country::create([
            'id' => 157,
            'cc_fips' => 'ML',
            'cc_iso' => 'ML',
            'tld' => '.ml',
            'country_name' => 'Mali',
            'country_phone_code' => 466
        ]);



        App\Models\Country::create([
            'id' => 158,
            'cc_fips' => 'MN',
            'cc_iso' => 'MC',
            'tld' => '.mc',
            'country_name' => 'Monaco',
            'country_phone_code' => 496
        ]);



        App\Models\Country::create([
            'id' => 159,
            'cc_fips' => 'MO',
            'cc_iso' => 'MA',
            'tld' => '.ma',
            'country_name' => 'Morocco',
            'country_phone_code' => 446
        ]);



        App\Models\Country::create([
            'id' => 160,
            'cc_fips' => 'MP',
            'cc_iso' => 'MU',
            'tld' => '.mu',
            'country_name' => 'Mauritius',
            'country_phone_code' => 580
        ]);



        App\Models\Country::create([
            'id' => 161,
            'cc_fips' => 'MR',
            'cc_iso' => 'MR',
            'tld' => '.mr',
            'country_name' => 'Mauritania',
            'country_phone_code' => 478
        ]);



        App\Models\Country::create([
            'id' => 162,
            'cc_fips' => 'MT',
            'cc_iso' => 'MT',
            'tld' => '.mt',
            'country_name' => 'Malta',
            'country_phone_code' => 470
        ]);



        App\Models\Country::create([
            'id' => 163,
            'cc_fips' => 'MU',
            'cc_iso' => 'OM',
            'tld' => '.om',
            'country_name' => 'Oman',
            'country_phone_code' => 480
        ]);



        App\Models\Country::create([
            'id' => 164,
            'cc_fips' => 'MV',
            'cc_iso' => 'MV',
            'tld' => '.mv',
            'country_name' => 'Maldives',
            'country_phone_code' => 462
        ]);



        App\Models\Country::create([
            'id' => 165,
            'cc_fips' => 'MX',
            'cc_iso' => 'MX',
            'tld' => '.mx',
            'country_name' => 'Mexico',
            'country_phone_code' => 484
        ]);



        App\Models\Country::create([
            'id' => 166,
            'cc_fips' => 'MY',
            'cc_iso' => 'MY',
            'tld' => '.my',
            'country_name' => 'Malaysia',
            'country_phone_code' => 458
        ]);



        App\Models\Country::create([
            'id' => 167,
            'cc_fips' => 'MZ',
            'cc_iso' => 'MZ',
            'tld' => '.mz',
            'country_name' => 'Mozambique',
            'country_phone_code' => 508
        ]);



        App\Models\Country::create([
            'id' => 168,
            'cc_fips' => 'NC',
            'cc_iso' => 'NC',
            'tld' => '.nc',
            'country_name' => 'New Caledonia',
            'country_phone_code' => 540
        ]);



        App\Models\Country::create([
            'id' => 169,
            'cc_fips' => 'NE',
            'cc_iso' => 'NU',
            'tld' => '.nu',
            'country_name' => 'Niue',
            'country_phone_code' => 562
        ]);



        App\Models\Country::create([
            'id' => 170,
            'cc_fips' => 'NF',
            'cc_iso' => 'NF',
            'tld' => '.nf',
            'country_name' => 'Norfolk Island',
            'country_phone_code' => 574
        ]);



        App\Models\Country::create([
            'id' => 171,
            'cc_fips' => 'NG',
            'cc_iso' => 'NE',
            'tld' => '.ne',
            'country_name' => 'Niger',
            'country_phone_code' => 566
        ]);



        App\Models\Country::create([
            'id' => 172,
            'cc_fips' => 'NH',
            'cc_iso' => 'VU',
            'tld' => '.vu',
            'country_name' => 'Vanuatu',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 173,
            'cc_fips' => 'NI',
            'cc_iso' => 'NG',
            'tld' => '.ng',
            'country_name' => 'Nigeria',
            'country_phone_code' => 558
        ]);



        App\Models\Country::create([
            'id' => 174,
            'cc_fips' => 'NL',
            'cc_iso' => 'NL',
            'tld' => '.nl',
            'country_name' => 'Netherlands',
            'country_phone_code' => 528
        ]);



        App\Models\Country::create([
            'id' => 175,
            'cc_fips' => 'NM',
            'cc_iso' => '',
            'tld' => '',
            'country_name' => 'No Man\'s Land',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 176,
            'cc_fips' => 'NO',
            'cc_iso' => 'NO',
            'tld' => '.no',
            'country_name' => 'Norway',
            'country_phone_code' => 578
        ]);



        App\Models\Country::create([
            'id' => 177,
            'cc_fips' => 'NP',
            'cc_iso' => 'NP',
            'tld' => '.np',
            'country_name' => 'Nepal',
            'country_phone_code' => 524
        ]);



        App\Models\Country::create([
            'id' => 178,
            'cc_fips' => 'NR',
            'cc_iso' => 'NR',
            'tld' => '.nr',
            'country_name' => 'Nauru',
            'country_phone_code' => 520
        ]);



        App\Models\Country::create([
            'id' => 179,
            'cc_fips' => 'NS',
            'cc_iso' => 'SR',
            'tld' => '.sr',
            'country_name' => 'Suriname',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 180,
            'cc_fips' => '-',
            'cc_iso' => 'BQ',
            'tld' => '.bq',
            'country_name' => 'Bonaire, Sint Eustatius and Saba',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 181,
            'cc_fips' => 'NU',
            'cc_iso' => 'NI',
            'tld' => '.ni',
            'country_name' => 'Nicaragua',
            'country_phone_code' => 570
        ]);



        App\Models\Country::create([
            'id' => 182,
            'cc_fips' => 'NZ',
            'cc_iso' => 'NZ',
            'tld' => '.nz',
            'country_name' => 'New Zealand',
            'country_phone_code' => 554
        ]);



        App\Models\Country::create([
            'id' => 183,
            'cc_fips' => 'PA',
            'cc_iso' => 'PY',
            'tld' => '.py',
            'country_name' => 'Paraguay',
            'country_phone_code' => 591
        ]);



        App\Models\Country::create([
            'id' => 184,
            'cc_fips' => 'PC',
            'cc_iso' => 'PN',
            'tld' => '.pn',
            'country_name' => 'Pitcairn Islands',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 185,
            'cc_fips' => 'PE',
            'cc_iso' => 'PE',
            'tld' => '.pe',
            'country_name' => 'Peru',
            'country_phone_code' => 604
        ]);



        App\Models\Country::create([
            'id' => 186,
            'cc_fips' => 'PF',
            'cc_iso' => '-',
            'tld' => '-',
            'country_name' => 'Paracel Islands',
            'country_phone_code' => 258
        ]);



        App\Models\Country::create([
            'id' => 187,
            'cc_fips' => 'PG',
            'cc_iso' => '-',
            'tld' => '-',
            'country_name' => 'Spratly Islands',
            'country_phone_code' => 598
        ]);



        App\Models\Country::create([
            'id' => 188,
            'cc_fips' => 'PK',
            'cc_iso' => 'PK',
            'tld' => '.pk',
            'country_name' => 'Pakistan',
            'country_phone_code' => 586
        ]);



        App\Models\Country::create([
            'id' => 189,
            'cc_fips' => 'PL',
            'cc_iso' => 'PL',
            'tld' => '.pl',
            'country_name' => 'Poland',
            'country_phone_code' => 616
        ]);



        App\Models\Country::create([
            'id' => 190,
            'cc_fips' => 'PM',
            'cc_iso' => 'PA',
            'tld' => '.pa',
            'country_name' => 'Panama',
            'country_phone_code' => 666
        ]);



        App\Models\Country::create([
            'id' => 191,
            'cc_fips' => 'PO',
            'cc_iso' => 'PT',
            'tld' => '.pt',
            'country_name' => 'Portugal',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 192,
            'cc_fips' => 'PP',
            'cc_iso' => 'PG',
            'tld' => '.pg',
            'country_name' => 'Papua New Guinea',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 193,
            'cc_fips' => 'PS',
            'cc_iso' => 'PW',
            'tld' => '.pw',
            'country_name' => 'Palau',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 194,
            'cc_fips' => 'PU',
            'cc_iso' => 'GW',
            'tld' => '.gw',
            'country_name' => 'Guinea-Bissau',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 195,
            'cc_fips' => 'QA',
            'cc_iso' => 'QA',
            'tld' => '.qa',
            'country_name' => 'Qatar',
            'country_phone_code' => 634
        ]);



        App\Models\Country::create([
            'id' => 196,
            'cc_fips' => 'RE',
            'cc_iso' => 'RE',
            'tld' => '.re',
            'country_name' => 'Reunion',
            'country_phone_code' => 638
        ]);



        App\Models\Country::create([
            'id' => 197,
            'cc_fips' => 'RI',
            'cc_iso' => 'RS',
            'tld' => '.rs',
            'country_name' => 'Serbia',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 198,
            'cc_fips' => 'RM',
            'cc_iso' => 'MH',
            'tld' => '.mh',
            'country_name' => 'Marshall Islands',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 199,
            'cc_fips' => 'RN',
            'cc_iso' => 'MF',
            'tld' => '-',
            'country_name' => 'Saint Martin',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 200,
            'cc_fips' => 'RO',
            'cc_iso' => 'RO',
            'tld' => '.ro',
            'country_name' => 'Romania',
            'country_phone_code' => 642
        ]);



        App\Models\Country::create([
            'id' => 201,
            'cc_fips' => 'RP',
            'cc_iso' => 'PH',
            'tld' => '.ph',
            'country_name' => 'Philippines',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 202,
            'cc_fips' => 'RQ',
            'cc_iso' => 'PR',
            'tld' => '.pr',
            'country_name' => 'Puerto Rico',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 203,
            'cc_fips' => 'RS',
            'cc_iso' => 'RU',
            'tld' => '.ru',
            'country_name' => 'Russia',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 204,
            'cc_fips' => 'RW',
            'cc_iso' => 'RW',
            'tld' => '.rw',
            'country_name' => 'Rwanda',
            'country_phone_code' => 646
        ]);



        App\Models\Country::create([
            'id' => 205,
            'cc_fips' => 'SA',
            'cc_iso' => 'SA',
            'tld' => '.sa',
            'country_name' => 'Saudi Arabia',
            'country_phone_code' => 682
        ]);



        App\Models\Country::create([
            'id' => 206,
            'cc_fips' => 'SB',
            'cc_iso' => 'PM',
            'tld' => '.pm',
            'country_name' => 'Saint Pierre and Miquelon',
            'country_phone_code' => 90
        ]);



        App\Models\Country::create([
            'id' => 207,
            'cc_fips' => 'SC',
            'cc_iso' => 'KN',
            'tld' => '.kn',
            'country_name' => 'Saint Kitts and Nevis',
            'country_phone_code' => 690
        ]);



        App\Models\Country::create([
            'id' => 208,
            'cc_fips' => 'SE',
            'cc_iso' => 'SC',
            'tld' => '.sc',
            'country_name' => 'Seychelles',
            'country_phone_code' => 752
        ]);



        App\Models\Country::create([
            'id' => 209,
            'cc_fips' => 'SF',
            'cc_iso' => 'ZA',
            'tld' => '.za',
            'country_name' => 'South Africa',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 210,
            'cc_fips' => 'SG',
            'cc_iso' => 'SN',
            'tld' => '.sn',
            'country_name' => 'Senegal',
            'country_phone_code' => 702
        ]);



        App\Models\Country::create([
            'id' => 211,
            'cc_fips' => 'SH',
            'cc_iso' => 'SH',
            'tld' => '.sh',
            'country_name' => 'Saint Helena',
            'country_phone_code' => 654
        ]);



        App\Models\Country::create([
            'id' => 212,
            'cc_fips' => 'SI',
            'cc_iso' => 'SI',
            'tld' => '.si',
            'country_name' => 'Slovenia',
            'country_phone_code' => 705
        ]);



        App\Models\Country::create([
            'id' => 213,
            'cc_fips' => 'SL',
            'cc_iso' => 'SL',
            'tld' => '.sl',
            'country_name' => 'Sierra Leone',
            'country_phone_code' => 694
        ]);



        App\Models\Country::create([
            'id' => 214,
            'cc_fips' => 'SM',
            'cc_iso' => 'SM',
            'tld' => '.sm',
            'country_name' => 'San Marino',
            'country_phone_code' => 674
        ]);



        App\Models\Country::create([
            'id' => 215,
            'cc_fips' => 'SN',
            'cc_iso' => 'SG',
            'tld' => '.sg',
            'country_name' => 'Singapore',
            'country_phone_code' => 686
        ]);



        App\Models\Country::create([
            'id' => 216,
            'cc_fips' => 'SO',
            'cc_iso' => 'SO',
            'tld' => '.so',
            'country_name' => 'Somalia',
            'country_phone_code' => 706
        ]);



        App\Models\Country::create([
            'id' => 217,
            'cc_fips' => 'SP',
            'cc_iso' => 'ES',
            'tld' => '.es',
            'country_name' => 'Spain',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 218,
            'cc_fips' => '-',
            'cc_iso' => 'SS',
            'tld' => '.ss',
            'country_name' => 'South Sudan',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 219,
            'cc_fips' => 'ST',
            'cc_iso' => 'LC',
            'tld' => '.lc',
            'country_name' => 'Saint Lucia',
            'country_phone_code' => 678
        ]);



        App\Models\Country::create([
            'id' => 220,
            'cc_fips' => 'SU',
            'cc_iso' => 'SD',
            'tld' => '.sd',
            'country_name' => 'Sudan',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 221,
            'cc_fips' => 'SV',
            'cc_iso' => 'SJ',
            'tld' => '.sj',
            'country_name' => 'Svalbard',
            'country_phone_code' => 222
        ]);



        App\Models\Country::create([
            'id' => 222,
            'cc_fips' => 'SW',
            'cc_iso' => 'SE',
            'tld' => '.se',
            'country_name' => 'Sweden',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 223,
            'cc_fips' => 'SX',
            'cc_iso' => 'GS',
            'tld' => '.gs',
            'country_name' => 'South Georgia and the Islands',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 224,
            'cc_fips' => 'NN',
            'cc_iso' => 'SX',
            'tld' => '.sx',
            'country_name' => 'Sint Maarten',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 225,
            'cc_fips' => 'SY',
            'cc_iso' => 'SY',
            'tld' => '.sy',
            'country_name' => 'Syrian Arab Republic',
            'country_phone_code' => 760
        ]);



        App\Models\Country::create([
            'id' => 226,
            'cc_fips' => 'SZ',
            'cc_iso' => 'CH',
            'tld' => '.ch',
            'country_name' => 'Switzerland',
            'country_phone_code' => 748
        ]);



        App\Models\Country::create([
            'id' => 227,
            'cc_fips' => 'TD',
            'cc_iso' => 'TT',
            'tld' => '.tt',
            'country_name' => 'Trinidad and Tobago',
            'country_phone_code' => 148
        ]);



        App\Models\Country::create([
            'id' => 228,
            'cc_fips' => 'TE',
            'cc_iso' => '-',
            'tld' => '-',
            'country_name' => 'Tromelin Island',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 229,
            'cc_fips' => 'TH',
            'cc_iso' => 'TH',
            'tld' => '.th',
            'country_name' => 'Thailand',
            'country_phone_code' => 764
        ]);



        App\Models\Country::create([
            'id' => 230,
            'cc_fips' => 'TI',
            'cc_iso' => 'TJ',
            'tld' => '.tj',
            'country_name' => 'Tajikistan',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 231,
            'cc_fips' => 'TK',
            'cc_iso' => 'TC',
            'tld' => '.tc',
            'country_name' => 'Turks and Caicos Islands',
            'country_phone_code' => 772
        ]);



        App\Models\Country::create([
            'id' => 232,
            'cc_fips' => 'TL',
            'cc_iso' => 'TK',
            'tld' => '.tk',
            'country_name' => 'Tokelau',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 233,
            'cc_fips' => 'TN',
            'cc_iso' => 'TO',
            'tld' => '.to',
            'country_name' => 'Tonga',
            'country_phone_code' => 788
        ]);



        App\Models\Country::create([
            'id' => 234,
            'cc_fips' => 'TO',
            'cc_iso' => 'TG',
            'tld' => '.tg',
            'country_name' => 'Togo',
            'country_phone_code' => 776
        ]);



        App\Models\Country::create([
            'id' => 235,
            'cc_fips' => 'TP',
            'cc_iso' => 'ST',
            'tld' => '.st',
            'country_name' => 'Sao Tome and Principe',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 236,
            'cc_fips' => 'TS',
            'cc_iso' => 'TN',
            'tld' => '.tn',
            'country_name' => 'Tunisia',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 237,
            'cc_fips' => 'TT',
            'cc_iso' => 'TL',
            'tld' => '.tl',
            'country_name' => 'East Timor',
            'country_phone_code' => 780
        ]);



        App\Models\Country::create([
            'id' => 238,
            'cc_fips' => 'TU',
            'cc_iso' => 'TR',
            'tld' => '.tr',
            'country_name' => 'Turkey',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 239,
            'cc_fips' => 'TV',
            'cc_iso' => 'TV',
            'tld' => '.tv',
            'country_name' => 'Tuvalu',
            'country_phone_code' => 798
        ]);



        App\Models\Country::create([
            'id' => 240,
            'cc_fips' => 'TW',
            'cc_iso' => 'TW',
            'tld' => '.tw',
            'country_name' => 'Taiwan',
            'country_phone_code' => 158
        ]);



        App\Models\Country::create([
            'id' => 241,
            'cc_fips' => 'TX',
            'cc_iso' => 'TM',
            'tld' => '.tm',
            'country_name' => 'Turkmenistan',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 242,
            'cc_fips' => 'TZ',
            'cc_iso' => 'TZ',
            'tld' => '.tz',
            'country_name' => 'Tanzania, United Republic of',
            'country_phone_code' => 834
        ]);



        App\Models\Country::create([
            'id' => 243,
            'cc_fips' => 'UC',
            'cc_iso' => 'CW',
            'tld' => '.cw',
            'country_name' => 'Curacao',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 244,
            'cc_fips' => 'UG',
            'cc_iso' => 'UG',
            'tld' => '.ug',
            'country_name' => 'Uganda',
            'country_phone_code' => 800
        ]);



        App\Models\Country::create([
            'id' => 245,
            'cc_fips' => 'UK',
            'cc_iso' => 'GB',
            'tld' => '.uk',
            'country_name' => 'United Kingdom',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 246,
            'cc_fips' => 'UP',
            'cc_iso' => 'UA',
            'tld' => '.ua',
            'country_name' => 'Ukraine',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 247,
            'cc_fips' => 'US',
            'cc_iso' => 'US',
            'tld' => '.us',
            'country_name' => 'United States',
            'country_phone_code' => 840
        ]);



        App\Models\Country::create([
            'id' => 248,
            'cc_fips' => 'UV',
            'cc_iso' => 'BF',
            'tld' => '.bf',
            'country_name' => 'Burkina Faso',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 249,
            'cc_fips' => 'UY',
            'cc_iso' => 'UY',
            'tld' => '.uy',
            'country_name' => 'Uruguay',
            'country_phone_code' => 858
        ]);



        App\Models\Country::create([
            'id' => 250,
            'cc_fips' => 'UZ',
            'cc_iso' => 'UZ',
            'tld' => '.uz',
            'country_name' => 'Uzbekistan',
            'country_phone_code' => 860
        ]);



        App\Models\Country::create([
            'id' => 251,
            'cc_fips' => 'VC',
            'cc_iso' => 'VC',
            'tld' => '.vc',
            'country_name' => 'Saint Vincent and the Grenadines',
            'country_phone_code' => 670
        ]);



        App\Models\Country::create([
            'id' => 252,
            'cc_fips' => 'VE',
            'cc_iso' => 'VE',
            'tld' => '.ve',
            'country_name' => 'Venezuela',
            'country_phone_code' => 862
        ]);



        App\Models\Country::create([
            'id' => 253,
            'cc_fips' => 'VI',
            'cc_iso' => 'VG',
            'tld' => '.vg',
            'country_name' => 'British Virgin Islands',
            'country_phone_code' => 850
        ]);



        App\Models\Country::create([
            'id' => 254,
            'cc_fips' => 'VM',
            'cc_iso' => 'VN',
            'tld' => '.vn',
            'country_name' => 'Vietnam',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 255,
            'cc_fips' => 'VQ',
            'cc_iso' => 'VI',
            'tld' => '.vi',
            'country_name' => 'Virgin Islands (US)',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 256,
            'cc_fips' => 'VT',
            'cc_iso' => 'VA',
            'tld' => '.va',
            'country_name' => 'Holy See (Vatican City)',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 257,
            'cc_fips' => 'WA',
            'cc_iso' => 'NA',
            'tld' => '.na',
            'country_name' => 'Namibia',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 258,
            'cc_fips' => 'WE',
            'cc_iso' => 'PS',
            'tld' => '.ps',
            'country_name' => 'Palestine, State of',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 259,
            'cc_fips' => 'WF',
            'cc_iso' => 'WF',
            'tld' => '.wf',
            'country_name' => 'Wallis and Futuna',
            'country_phone_code' => 876
        ]);



        App\Models\Country::create([
            'id' => 260,
            'cc_fips' => 'WI',
            'cc_iso' => 'EH',
            'tld' => '.eh',
            'country_name' => 'Western Sahara',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 261,
            'cc_fips' => 'WS',
            'cc_iso' => 'WS',
            'tld' => '.ws',
            'country_name' => 'Samoa',
            'country_phone_code' => 882
        ]);



        App\Models\Country::create([
            'id' => 262,
            'cc_fips' => 'WZ',
            'cc_iso' => 'SZ',
            'tld' => '.sz',
            'country_name' => 'Swaziland',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 263,
            'cc_fips' => 'YI',
            'cc_iso' => 'CS',
            'tld' => '.yu',
            'country_name' => 'Serbia and Montenegro',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 264,
            'cc_fips' => 'YM',
            'cc_iso' => 'YE',
            'tld' => '.ye',
            'country_name' => 'Yemen',
            'country_phone_code' => NULL
        ]);



        App\Models\Country::create([
            'id' => 265,
            'cc_fips' => 'ZA',
            'cc_iso' => 'ZM',
            'tld' => '.zm',
            'country_name' => 'Zambia',
            'country_phone_code' => 710
        ]);



        App\Models\Country::create([
            'id' => 266,
            'cc_fips' => 'ZI',
            'cc_iso' => 'ZW',
            'tld' => '.zw',
            'country_name' => 'Zimbabwe',
            'country_phone_code' => NULL
        ]);
    }
}
