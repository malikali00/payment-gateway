<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 9/14/2016
 * Time: 8:08 AM
 */

namespace System\Arrays;

class Locations {

    static $STATES = array(
        'AL'=>'Alabama',
        'AK'=>'Alaska',
        'AZ'=>'Arizona',
        'AR'=>'Arkansas',
        'CA'=>'California',
        'CO'=>'Colorado',
        'CT'=>'Connecticut',
        'DE'=>'Delaware',
        'DC'=>'District of Columbia',
        'FL'=>'Florida',
        'GA'=>'Georgia',
        'HI'=>'Hawaii',
        'ID'=>'Idaho',
        'IL'=>'Illinois',
        'IN'=>'Indiana',
        'IA'=>'Iowa',
        'KS'=>'Kansas',
        'KY'=>'Kentucky',
        'LA'=>'Louisiana',
        'ME'=>'Maine',
        'MD'=>'Maryland',
        'MA'=>'Massachusetts',
        'MI'=>'Michigan',
        'MN'=>'Minnesota',
        'MS'=>'Mississippi',
        'MO'=>'Missouri',
        'MT'=>'Montana',
        'NE'=>'Nebraska',
        'NV'=>'Nevada',
        'NH'=>'New Hampshire',
        'NJ'=>'New Jersey',
        'NM'=>'New Mexico',
        'NY'=>'New York',
        'NC'=>'North Carolina',
        'ND'=>'North Dakota',
        'OH'=>'Ohio',
        'OK'=>'Oklahoma',
        'OR'=>'Oregon',
        'PA'=>'Pennsylvania',
        'RI'=>'Rhode Island',
        'SC'=>'South Carolina',
        'SD'=>'South Dakota',
        'TN'=>'Tennessee',
        'TX'=>'Texas',
        'UT'=>'Utah',
        'VT'=>'Vermont',
        'VA'=>'Virginia',
        'WA'=>'Washington',
        'WV'=>'West Virginia',
        'WI'=>'Wisconsin',
        'WY'=>'Wyoming',
        );

    static $COUNTRIES = array (
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua And Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia And Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo',
        'CD' => 'Congo, Democratic Republic',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote D\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands (Malvinas)',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island & Mcdonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran, Islamic Republic Of',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle Of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KR' => 'Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao People\'s Democratic Republic',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia, Federated States Of',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory, Occupied',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts And Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre And Miquelon',
        'VC' => 'Saint Vincent And Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome And Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia And Sandwich Isl.',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard And Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad And Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks And Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Viet Nam',
        'VG' => 'Virgin Islands, British',
        'VI' => 'Virgin Islands, U.S.',
        'WF' => 'Wallis And Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',

        // 3 Digit
        "ABW" => "Aruba",
        "AFG" => "Afghanistan",
        "AGO" => "Angola",
        "AIA" => "Anguilla",
        "ALA" => "�land Islands",
        "ALB" => "Albania",
        "AND" => "Andorra",
        "ARE" => "United Arab Emirates",
        "ARG" => "Argentina",
        "ARM" => "Armenia",
        "ASM" => "American Samoa",
        "ATA" => "Antarctica",
        "ATF" => "French Southern Territories",
        "ATG" => "Antigua and Barbuda",
        "AUS" => "Australia",
        "AUT" => "Austria",
        "AZE" => "Azerbaijan",
        "BDI" => "Burundi",
        "BEL" => "Belgium",
        "BEN" => "Benin",
        "BES" => "Bonaire, Sint Eustatius and Saba",
        "BFA" => "Burkina Faso",
        "BGD" => "Bangladesh",
        "BGR" => "Bulgaria",
        "BHR" => "Bahrain",
        "BHS" => "Bahamas",
        "BIH" => "Bosnia and Herzegovina",
        "BLM" => "Saint Barth�lemy",
        "BLR" => "Belarus",
        "BLZ" => "Belize",
        "BMU" => "Bermuda",
        "BOL" => "Bolivia, Plurinational State of",
        "BRA" => "Brazil",
        "BRB" => "Barbados",
        "BRN" => "Brunei Darussalam",
        "BTN" => "Bhutan",
        "BVT" => "Bouvet Island",
        "BWA" => "Botswana",
        "CAF" => "Central African Republic",
        "CAN" => "Canada",
        "CCK" => "Cocos (Keeling) Islands",
        "CHE" => "Switzerland",
        "CHL" => "Chile",
        "CHN" => "China",
        "CIV" => "C�te d'Ivoire",
        "CMR" => "Cameroon",
        "COD" => "Congo, the Democratic Republic of the",
        "COG" => "Congo",
        "COK" => "Cook Islands",
        "COL" => "Colombia",
        "COM" => "Comoros",
        "CPV" => "Cabo Verde",
        "CRI" => "Costa Rica",
        "CUB" => "Cuba",
        "CUW" => "Cura�ao",
        "CXR" => "Christmas Island",
        "CYM" => "Cayman Islands",
        "CYP" => "Cyprus",
        "CZE" => "Czech Republic",
        "DEU" => "Germany",
        "DJI" => "Djibouti",
        "DMA" => "Dominica",
        "DNK" => "Denmark",
        "DOM" => "Dominican Republic",
        "DZA" => "Algeria",
        "ECU" => "Ecuador",
        "EGY" => "Egypt",
        "ERI" => "Eritrea",
        "ESH" => "Western Sahara",
        "ESP" => "Spain",
        "EST" => "Estonia",
        "ETH" => "Ethiopia",
        "FIN" => "Finland",
        "FJI" => "Fiji",
        "FLK" => "Falkland Islands (Malvinas)",
        "FRA" => "France",
        "FRO" => "Faroe Islands",
        "FSM" => "Micronesia, Federated States of",
        "GAB" => "Gabon",
        "GBR" => "United Kingdom",
        "GEO" => "Georgia",
        "GGY" => "Guernsey",
        "GHA" => "Ghana",
        "GIB" => "Gibraltar",
        "GIN" => "Guinea",
        "GLP" => "Guadeloupe",
        "GMB" => "Gambia",
        "GNB" => "Guinea-Bissau",
        "GNQ" => "Equatorial Guinea",
        "GRC" => "Greece",
        "GRD" => "Grenada",
        "GRL" => "Greenland",
        "GTM" => "Guatemala",
        "GUF" => "French Guiana",
        "GUM" => "Guam",
        "GUY" => "Guyana",
        "HKG" => "Hong Kong",
        "HMD" => "Heard Island and McDonald Islands",
        "HND" => "Honduras",
        "HRV" => "Croatia",
        "HTI" => "Haiti",
        "HUN" => "Hungary",
        "IDN" => "Indonesia",
        "IMN" => "Isle of Man",
        "IND" => "India",
        "IOT" => "British Indian Ocean Territory",
        "IRL" => "Ireland",
        "IRN" => "Iran, Islamic Republic of",
        "IRQ" => "Iraq",
        "ISL" => "Iceland",
        "ISR" => "Israel",
        "ITA" => "Italy",
        "JAM" => "Jamaica",
        "JEY" => "Jersey",
        "JOR" => "Jordan",
        "JPN" => "Japan",
        "KAZ" => "Kazakhstan",
        "KEN" => "Kenya",
        "KGZ" => "Kyrgyzstan",
        "KHM" => "Cambodia",
        "KIR" => "Kiribati",
        "KNA" => "Saint Kitts and Nevis",
        "KOR" => "Korea, Republic of",
        "KWT" => "Kuwait",
        "LAO" => "Lao People's Democratic Republic",
        "LBN" => "Lebanon",
        "LBR" => "Liberia",
        "LBY" => "Libya",
        "LCA" => "Saint Lucia",
        "LIE" => "Liechtenstein",
        "LKA" => "Sri Lanka",
        "LSO" => "Lesotho",
        "LTU" => "Lithuania",
        "LUX" => "Luxembourg",
        "LVA" => "Latvia",
        "MAC" => "Macao",
        "MAF" => "Saint Martin (French part)",
        "MAR" => "Morocco",
        "MCO" => "Monaco",
        "MDA" => "Moldova, Republic of",
        "MDG" => "Madagascar",
        "MDV" => "Maldives",
        "MEX" => "Mexico",
        "MHL" => "Marshall Islands",
        "MKD" => "Macedonia, the former Yugoslav Republic of",
        "MLI" => "Mali",
        "MLT" => "Malta",
        "MMR" => "Myanmar",
        "MNE" => "Montenegro",
        "MNG" => "Mongolia",
        "MNP" => "Northern Mariana Islands",
        "MOZ" => "Mozambique",
        "MRT" => "Mauritania",
        "MSR" => "Montserrat",
        "MTQ" => "Martinique",
        "MUS" => "Mauritius",
        "MWI" => "Malawi",
        "MYS" => "Malaysia",
        "MYT" => "Mayotte",
        "NAM" => "Namibia",
        "NCL" => "New Caledonia",
        "NER" => "Niger",
        "NFK" => "Norfolk Island",
        "NGA" => "Nigeria",
        "NIC" => "Nicaragua",
        "NIU" => "Niue",
        "NLD" => "Netherlands",
        "NOR" => "Norway",
        "NPL" => "Nepal",
        "NRU" => "Nauru",
        "NZL" => "New Zealand",
        "OMN" => "Oman",
        "PAK" => "Pakistan",
        "PAN" => "Panama",
        "PCN" => "Pitcairn",
        "PER" => "Peru",
        "PHL" => "Philippines",
        "PLW" => "Palau",
        "PNG" => "Papua New Guinea",
        "POL" => "Poland",
        "PRI" => "Puerto Rico",
        "PRK" => "Korea, Democratic People's Republic of",
        "PRT" => "Portugal",
        "PRY" => "Paraguay",
        "PSE" => "Palestine, State of",
        "PYF" => "French Polynesia",
        "QAT" => "Qatar",
        "REU" => "R�union",
        "ROU" => "Romania",
        "RUS" => "Russian Federation",
        "RWA" => "Rwanda",
        "SAU" => "Saudi Arabia",
        "SDN" => "Sudan",
        "SEN" => "Senegal",
        "SGP" => "Singapore",
        "SGS" => "South Georgia and the South Sandwich Islands",
        "SHN" => "Saint Helena, Ascension and Tristan da Cunha",
        "SJM" => "Svalbard and Jan Mayen",
        "SLB" => "Solomon Islands",
        "SLE" => "Sierra Leone",
        "SLV" => "El Salvador",
        "SMR" => "San Marino",
        "SOM" => "Somalia",
        "SPM" => "Saint Pierre and Miquelon",
        "SRB" => "Serbia",
        "SSD" => "South Sudan",
        "STP" => "Sao Tome and Principe",
        "SUR" => "Suriname",
        "SVK" => "Slovakia",
        "SVN" => "Slovenia",
        "SWE" => "Sweden",
        "SWZ" => "Swaziland",
        "SXM" => "Sint Maarten (Dutch part)",
        "SYC" => "Seychelles",
        "SYR" => "Syrian Arab Republic",
        "TCA" => "Turks and Caicos Islands",
        "TCD" => "Chad",
        "TGO" => "Togo",
        "THA" => "Thailand",
        "TJK" => "Tajikistan",
        "TKL" => "Tokelau",
        "TKM" => "Turkmenistan",
        "TLS" => "Timor-Leste",
        "TON" => "Tonga",
        "TTO" => "Trinidad and Tobago",
        "TUN" => "Tunisia",
        "TUR" => "Turkey",
        "TUV" => "Tuvalu",
        "TWN" => "Taiwan, Province of China",
        "TZA" => "Tanzania, United Republic of",
        "UGA" => "Uganda",
        "UKR" => "Ukraine",
        "UMI" => "United States Minor Outlying Islands",
        "URY" => "Uruguay",
        "USA" => "United States of America",
        "UZB" => "Uzbekistan",
        "VAT" => "Holy See (Vatican City State)",
        "VCT" => "Saint Vincent and the Grenadines",
        "VEN" => "Venezuela, Bolivarian Republic of",
        "VGB" => "Virgin Islands, British",
        "VIR" => "Virgin Islands, U.S.",
        "VNM" => "Viet Nam",
        "VUT" => "Vanuatu",
        "WLF" => "Wallis and Futuna",
        "WSM" => "Samoa",
        "YEM" => "Yemen",
        "ZAF" => "South Africa",
        "ZMB" => "Zambia",
        "ZWE" => "Zimbabwe",
    );


}