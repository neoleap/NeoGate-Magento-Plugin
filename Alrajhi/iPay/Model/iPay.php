<?php


namespace Alrajhi\iPay\Model;

use Magento\Sales\Api\Data\TransactionInterface;
use Magento\Sales\Model\Order;

class iPay extends \Magento\Payment\Model\Method\AbstractMethod {

    const PAYMENT_IPAY_CODE = 'ipay';
    
    protected $_code = self::PAYMENT_IPAY_CODE;
	
	protected $AES_IV="PGKEYENCDECIVSPC"; //For Encryption/Decryption
	protected $AES_METHOD="AES-256-CBC";

    /**
     *
     * @var \Magento\Framework\UrlInterface 
     */
    protected $_urlBuilder;
    protected $_supportedCurrencyCodes = array(
        'AFN', 'ALL', 'DZD', 'ARS', 'AUD', 'AZN', 'BSD', 'BDT', 'BBD',
        'BZD', 'BMD', 'BOB', 'BWP', 'BRL', 'GBP', 'BND', 'BGN', 'CAD',
        'CLP', 'CNY', 'COP', 'CRC', 'HRK', 'CZK', 'DKK', 'DOP', 'XCD',
        'EGP', 'EUR', 'FJD', 'GTQ', 'HKD', 'HNL', 'HUF', 'INR', 'IDR',
        'ILS', 'JMD', 'JPY', 'KZT', 'KES', 'LAK', 'MMK', 'LBP', 'LRD',
        'MOP', 'MYR', 'MVR', 'MRO', 'MUR', 'MXN', 'MAD', 'NPR', 'TWD',
        'NZD', 'NIO', 'NOK', 'PKR', 'PGK', 'PEN', 'PHP', 'PLN', 'QAR',
        'RON', 'RUB', 'WST', 'SAR', 'SCR', 'SGF', 'SBD', 'ZAR', 'KRW',
        'LKR', 'SEK', 'CHF', 'SYP', 'THB', 'TOP', 'TTD', 'TRY', 'UAH',
        'AED', 'USD', 'VUV', 'VND', 'XOF', 'YER'
    );
    
	protected $countryArray = array(
	'AF'=>array('name'=>'Afghanistan','code'=>'004'),
	'AX'=>array('name'=>'Ã…land Islands','code'=>'248'),
	'AL'=>array('name'=>'Albania','code'=>'008'),
	'DZ'=>array('name'=>'Algeria','code'=>'012'),
	'AS'=>array('name'=>'American Samoa','code'=>'016'),
	'AD'=>array('name'=>'Andorra','code'=>'020'),
	'AO'=>array('name'=>'Angola','code'=>'024'),
	'AI'=>array('name'=>'Anguilla','code'=>'660'),
	'AQ'=>array('name'=>'Antarcticaâ€Š[a]','code'=>'010'),
	'AG'=>array('name'=>'Antigua and Barbuda','code'=>'028'),
	'AR'=>array('name'=>'Argentina','code'=>'032'),
	'AM'=>array('name'=>'Armenia','code'=>'051'),
	'AW'=>array('name'=>'Aruba','code'=>'533'),
	'AU'=>array('name'=>'Australiaâ€Š[b]','code'=>'036'),
	'AT'=>array('name'=>'Austria','code'=>'040'),
	'AZ'=>array('name'=>'Azerbaijan','code'=>'031'),
	'BS'=>array('name'=>'Bahamas (the)','code'=>'044'),
	'BH'=>array('name'=>'Bahrain','code'=>'048'),
	'BD'=>array('name'=>'Bangladesh','code'=>'050'),
	'BB'=>array('name'=>'Barbados','code'=>'052'),
	'BY'=>array('name'=>'Belarus','code'=>'112'),
	'BE'=>array('name'=>'Belgium','code'=>'056'),
	'BZ'=>array('name'=>'Belize','code'=>'084'),
	'BJ'=>array('name'=>'Benin','code'=>'204'),
	'BM'=>array('name'=>'Bermuda','code'=>'060'),
	'BT'=>array('name'=>'Bhutan','code'=>'064'),
	'BO'=>array('name'=>'Bolivia (Plurinational State of)','code'=>'068'),
	'BQ'=>array('name'=>'Bonaire','code'=>'535'),
	'BA'=>array('name'=>'Bosnia and Herzegovina','code'=>'070'),
	'BW'=>array('name'=>'Botswana','code'=>'072'),
	'BV'=>array('name'=>'Bouvet Island','code'=>'074'),
	'BR'=>array('name'=>'Brazil','code'=>'076'),
	'IO'=>array('name'=>'British Indian Ocean Territory (the)','code'=>'086'),
	'BN'=>array('name'=>'Brunei Darussalamâ€Š[e]','code'=>'096'),
	'BG'=>array('name'=>'Bulgaria','code'=>'100'),
	'BF'=>array('name'=>'Burkina Faso','code'=>'854'),
	'BI'=>array('name'=>'Burundi','code'=>'108'),
	'CV'=>array('name'=>'Cabo Verdeâ€Š[f]','code'=>'132'),
	'KH'=>array('name'=>'Cambodia','code'=>'116'),
	'CM'=>array('name'=>'Cameroon','code'=>'120'),
	'CA'=>array('name'=>'Canada','code'=>'124'),
	'KY'=>array('name'=>'Cayman Islands (the)','code'=>'136'),
	'CF'=>array('name'=>'Central African Republic (the)','code'=>'140'),
	'TD'=>array('name'=>'Chad','code'=>'148'),
	'CL'=>array('name'=>'Chile','code'=>'152'),
	'CN'=>array('name'=>'China','code'=>'156'),
	'CX'=>array('name'=>'Christmas Island','code'=>'162'),
	'CC'=>array('name'=>'Cocos (Keeling) Islands (the)','code'=>'166'),
	'CO'=>array('name'=>'Colombia','code'=>'170'),
	'KM'=>array('name'=>'Comoros (the)','code'=>'174'),
	'CD'=>array('name'=>'Congo (the Democratic Republic of the)','code'=>'180'),
	'CG'=>array('name'=>'Congo (the)â€Š[g]','code'=>'178'),
	'CK'=>array('name'=>'Cook Islands (the)','code'=>'184'),
	'CR'=>array('name'=>'Costa Rica','code'=>'188'),
	'CI'=>array('name'=>'CÃ´te dIvoire','code'=>'384'),
	'HR'=>array('name'=>'Croatia','code'=>'191'),
	'CU'=>array('name'=>'Cuba','code'=>'192'),
	'CW'=>array('name'=>'CuraÃ§ao','code'=>'531'),
	'CY'=>array('name'=>'Cyprus','code'=>'196'),
	'CZ'=>array('name'=>'Czechiaâ€Š[i]','code'=>'203'),
	'DK'=>array('name'=>'Denmark','code'=>'208'),
	'DJ'=>array('name'=>'Djibouti','code'=>'262'),
	'DM'=>array('name'=>'Dominica','code'=>'212'),
	'DO'=>array('name'=>'Dominican Republic (the)','code'=>'214'),
	'EC'=>array('name'=>'Ecuador','code'=>'218'),
	'EG'=>array('name'=>'Egypt','code'=>'818'),
	'SV'=>array('name'=>'El Salvador','code'=>'222'),
	'GQ'=>array('name'=>'Equatorial Guinea','code'=>'226'),
	'ER'=>array('name'=>'Eritrea','code'=>'232'),
	'EE'=>array('name'=>'Estonia','code'=>'233'),
	'SZ'=>array('name'=>'Eswatiniâ€Š[j]','code'=>'748'),
	'ET'=>array('name'=>'Ethiopia','code'=>'231'),
	'FK'=>array('name'=>'Falkland Islands (the) [Malvinas]â€Š[k]','code'=>'238'),
	'FO'=>array('name'=>'Faroe Islands (the)','code'=>'234'),
	'FJ'=>array('name'=>'Fiji','code'=>'242'),
	'FI'=>array('name'=>'Finland','code'=>'246'),
	'FR'=>array('name'=>'Franceâ€Š[l]','code'=>'250'),
	'GF'=>array('name'=>'French Guiana','code'=>'254'),
	'PF'=>array('name'=>'French Polynesia','code'=>'258'),
	'TF'=>array('name'=>'French Southern Territories (the)â€Š[m]','code'=>'260'),
	'GA'=>array('name'=>'Gabon','code'=>'266'),
	'GM'=>array('name'=>'Gambia (the)','code'=>'270'),
	'GE'=>array('name'=>'Georgia','code'=>'268'),
	'DE'=>array('name'=>'Germany','code'=>'276'),
	'GH'=>array('name'=>'Ghana','code'=>'288'),
	'GI'=>array('name'=>'Gibraltar','code'=>'292'),
	'GR'=>array('name'=>'Greece','code'=>'300'),
	'GL'=>array('name'=>'Greenland','code'=>'304'),
	'GD'=>array('name'=>'Grenada','code'=>'308'),
	'GP'=>array('name'=>'Guadeloupe','code'=>'312'),
	'GU'=>array('name'=>'Guam','code'=>'316'),
	'GT'=>array('name'=>'Guatemala','code'=>'320'),
	'GG'=>array('name'=>'Guernsey','code'=>'831'),
	'GN'=>array('name'=>'Guinea','code'=>'324'),
	'GW'=>array('name'=>'Guinea-Bissau','code'=>'624'),
	'GY'=>array('name'=>'Guyana','code'=>'328'),
	'HT'=>array('name'=>'Haiti','code'=>'332'),
	'HM'=>array('name'=>'Heard Island and McDonald Islands','code'=>'334'),
	'VA'=>array('name'=>'Holy See (the)â€Š[n]','code'=>'336'),
	'HN'=>array('name'=>'Honduras','code'=>'340'),
	'HK'=>array('name'=>'Hong Kong','code'=>'344'),
	'HU'=>array('name'=>'Hungary','code'=>'348'),
	'IS'=>array('name'=>'Iceland','code'=>'352'),
	'IN'=>array('name'=>'India','code'=>'356'),
	'ID'=>array('name'=>'Indonesia','code'=>'360'),
	'IR'=>array('name'=>'Iran (Islamic Republic of)','code'=>'364'),
	'IQ'=>array('name'=>'Iraq','code'=>'368'),
	'IE'=>array('name'=>'Ireland','code'=>'372'),
	'IM'=>array('name'=>'Isle of Man','code'=>'833'),
	'IL'=>array('name'=>'Israel','code'=>'376'),
	'IT'=>array('name'=>'Italy','code'=>'380'),
	'JM'=>array('name'=>'Jamaica','code'=>'388'),	
	'JP'=>array('name'=>'Japan','code'=>'392'),
	'JE'=>array('name'=>'Jersey','code'=>'832'),
	'JO'=>array('name'=>'Jordan','code'=>'400'),
	'KZ'=>array('name'=>'Kazakhstan','code'=>'398'),
	'KE'=>array('name'=>'Kenya','code'=>'404'),
	'KI'=>array('name'=>'Kiribati','code'=>'296'),
	'KP'=>array('name'=>'Korea','code'=>'408'),
	'KR'=>array('name'=>'Korea (the Republic of)â€Š[p]','code'=>'410'),
	'KW'=>array('name'=>'Kuwait','code'=>'414'),
	'KG'=>array('name'=>'Kyrgyzstan','code'=>'417'),
	'LA'=>array('name'=>'Lao Peoples Democratic Republic','code'=>'418'),
	'LV'=>array('name'=>'Latvia','code'=>'428'),
	'LB'=>array('name'=>'Lebanon','code'=>'422'),
	'LS'=>array('name'=>'Lesotho','code'=>'426'),
	'LR'=>array('name'=>'Liberia','code'=>'430'),
	'LY'=>array('name'=>'Libya','code'=>'434'),
	'LI'=>array('name'=>'Liechtenstein','code'=>'438'),
	'LT'=>array('name'=>'Lithuania','code'=>'440'),
	'LU'=>array('name'=>'Luxembourg','code'=>'442'),
	'MO'=>array('name'=>'Macaoâ€Š[r]','code'=>'446'),
	'MK'=>array('name'=>'North Macedoniaâ€Š[s]','code'=>'807'),
	'MG'=>array('name'=>'Madagascar','code'=>'450'),
	'MW'=>array('name'=>'Malawi','code'=>'454'),
	'MY'=>array('name'=>'Malaysia','code'=>'458'),
	'MV'=>array('name'=>'Maldives','code'=>'462'),
	'ML'=>array('name'=>'Mali','code'=>'466'),
	'MT'=>array('name'=>'Malta','code'=>'470'),
	'MH'=>array('name'=>'Marshall Islands (the)','code'=>'584'),
	'MQ'=>array('name'=>'Martinique','code'=>'474'),
	'MR'=>array('name'=>'Mauritania','code'=>'478'),
	'MU'=>array('name'=>'Mauritius','code'=>'480'),
	'YT'=>array('name'=>'Mayotte','code'=>'175'),
	'MX'=>array('name'=>'Mexico','code'=>'484'),
	'FM'=>array('name'=>'Micronesia (Federated States of)','code'=>'583'),
	'MD'=>array('name'=>'Moldova (the Republic of)','code'=>'498'),
	'MC'=>array('name'=>'Monaco','code'=>'492'),
	'MN'=>array('name'=>'Mongolia','code'=>'496'),
	'ME'=>array('name'=>'Montenegro','code'=>'499'),
	'MS'=>array('name'=>'Montserrat','code'=>'500'),
	'MA'=>array('name'=>'Morocco','code'=>'504'),
	'MZ'=>array('name'=>'Mozambique','code'=>'508'),
	'MM'=>array('name'=>'Myanmarâ€Š[t]','code'=>'104'),
	'NA'=>array('name'=>'Namibia','code'=>'516'),
	'NR'=>array('name'=>'Nauru','code'=>'520'),
	'NP'=>array('name'=>'Nepal','code'=>'524'),
	'NL'=>array('name'=>'Netherlands (the)','code'=>'528'),
	'NC'=>array('name'=>'New Caledonia','code'=>'540'),
	'NZ'=>array('name'=>'New Zealand','code'=>'554'),
	'NI'=>array('name'=>'Nicaragua','code'=>'558'),
	'NE'=>array('name'=>'Niger (the)','code'=>'562'),
	'NG'=>array('name'=>'Nigeria','code'=>'566'),
	'NU'=>array('name'=>'Niue','code'=>'570'),
	'NF'=>array('name'=>'Norfolk Island','code'=>'574'),
	'MP'=>array('name'=>'Northern Mariana Islands (the)','code'=>'580'),
	'NO'=>array('name'=>'Norway','code'=>'578'),
	'OM'=>array('name'=>'Oman','code'=>'512'),
	'PK'=>array('name'=>'Pakistan','code'=>'586'),
	'PW'=>array('name'=>'Palau','code'=>'585'),
	'PS'=>array('name'=>'Palestine, State of','code'=>'275'),
	'PA'=>array('name'=>'Panama','code'=>'591'),
	'PG'=>array('name'=>'Papua New Guinea','code'=>'598'),
	'PY'=>array('name'=>'Paraguay','code'=>'600'),
	'PE'=>array('name'=>'Peru','code'=>'604'),
	'PH'=>array('name'=>'Philippines (the)','code'=>'608'),
	'PN'=>array('name'=>'Pitcairnâ€Š[u]','code'=>'612'),
	'PL'=>array('name'=>'Poland','code'=>'616'),
	'PT'=>array('name'=>'Portugal','code'=>'620'),
	'PR'=>array('name'=>'Puerto Rico','code'=>'630'),
	'QA'=>array('name'=>'Qatar','code'=>'634'),
	'RE'=>array('name'=>'RÃ©union','code'=>'638'),
	'RO'=>array('name'=>'Romania','code'=>'642'),
	'RU'=>array('name'=>'Russian Federation (the)â€Š[v]','code'=>'643'),
	'RW'=>array('name'=>'Rwanda','code'=>'646'),
	'BL'=>array('name'=>'Saint BarthÃ©lemy','code'=>'652'),
	'SH'=>array('name'=>'Saint Helena, Ascension and Tristan da Cunha','code'=>'654'),
	'KN'=>array('name'=>'Saint Kitts and Nevis','code'=>'659'),
	'LC'=>array('name'=>'Saint Lucia','code'=>'662'),
	'MF'=>array('name'=>'Saint Martin (French part)','code'=>'663'),
	'PM'=>array('name'=>'Saint Pierre and Miquelon','code'=>'666'),
	'VC'=>array('name'=>'Saint Vincent and the Grenadines','code'=>'670'),
	'WS'=>array('name'=>'Samoa','code'=>'882'),
	'SM'=>array('name'=>'San Marino','code'=>'674'),
	'ST'=>array('name'=>'Sao Tome and Principe','code'=>'678'),
	'SA'=>array('name'=>'Saudi Arabia','code'=>'682'),
	'SN'=>array('name'=>'Senegal','code'=>'686'),
	'RS'=>array('name'=>'Serbia','code'=>'688'),
	'SC'=>array('name'=>'Seychelles','code'=>'690'),
	'SL'=>array('name'=>'Sierra Leone','code'=>'694'),
	'SG'=>array('name'=>'Singapore','code'=>'702'),
	'SX'=>array('name'=>'Sint Maarten (Dutch part)','code'=>'534'),
	'SK'=>array('name'=>'Slovakia','code'=>'703'),
	'SI'=>array('name'=>'Slovenia','code'=>'705'),
	'SB'=>array('name'=>'Solomon Islands','code'=>'90'),
	'SO'=>array('name'=>'Somalia','code'=>'706'),
	'ZA'=>array('name'=>'South Africa','code'=>'710'),
	'GS'=>array('name'=>'South Georgia and the South Sandwich Islands','code'=>'239'),
	'SS'=>array('name'=>'South Sudan','code'=>'728'),
	'ES'=>array('name'=>'Spain','code'=>'724'),
	'LK'=>array('name'=>'Sri Lanka','code'=>'144'),
	'SD'=>array('name'=>'Sudan (the)','code'=>'729'),
	'SR'=>array('name'=>'Suriname','code'=>'740'),
	'SJ'=>array('name'=>'Svalbard','code'=>'744'),
	'SE'=>array('name'=>'Sweden','code'=>'752'),
	'CH'=>array('name'=>'Switzerland','code'=>'756'),
	'SY'=>array('name'=>'Syrian Arab Republic (the)â€Š[x]','code'=>'760'),
	'TW'=>array('name'=>'Taiwan (Province of China)â€Š[y]','code'=>'158'),
	'TJ'=>array('name'=>'Tajikistan','code'=>'762'),
	'TZ'=>array('name'=>'Tanzania, the United Republic of','code'=>'834'),
	'TH'=>array('name'=>'Thailand','code'=>'764'),
	'TL'=>array('name'=>'Timor-Lesteâ€Š[aa]','code'=>'626'),
	'TG'=>array('name'=>'Togo','code'=>'768'),
	'TK'=>array('name'=>'Tokelau','code'=>'772'),
	'TO'=>array('name'=>'Tonga','code'=>'776'),
	'TT'=>array('name'=>'Trinidad and Tobago','code'=>'780'),
	'TN'=>array('name'=>'Tunisia','code'=>'788'),
	'TR'=>array('name'=>'Turkey','code'=>'792'),
	'TM'=>array('name'=>'Turkmenistan','code'=>'795'),
	'TC'=>array('name'=>'Turks and Caicos Islands (the)','code'=>'796'),
	'TV'=>array('name'=>'Tuvalu','code'=>'798'),
	'UG'=>array('name'=>'Uganda','code'=>'800'),
	'UA'=>array('name'=>'Ukraine','code'=>'804'),
	'AE'=>array('name'=>'United Arab Emirates (the)','code'=>'784'),
	'GB'=>array('name'=>'United Kingdom of Great Britain and Northern Ireland (the)','code'=>'826'),
	'UM'=>array('name'=>'United States Minor Outlying Islands (the)â€Š[ac]','code'=>'581'),
	'US'=>array('name'=>'United States of America (the)','code'=>'840'),
	'UY'=>array('name'=>'Uruguay','code'=>'858'),
	'UZ'=>array('name'=>'Uzbekistan','code'=>'860'),
	'VU'=>array('name'=>'Vanuatu','code'=>'548'),
	'VE'=>array('name'=>'Venezuela (Bolivarian Republic of)','code'=>'862'),
	'VN'=>array('name'=>'Viet Namâ€Š[ae]','code'=>'704'),
	'VG'=>array('name'=>'Virgin Islands (British)â€Š[af]','code'=>'92'),
	'VI'=>array('name'=>'Virgin Islands (U.S.)â€Š[ag]','code'=>'850'),
	'WF'=>array('name'=>'Wallis and Futuna','code'=>'876'),
	'EH'=>array('name'=>'Western Saharaâ€Š[ah]','code'=>'732'),
	'YE'=>array('name'=>'Yemen','code'=>'887'),
	'ZM'=>array('name'=>'Zambia','code'=>'894'),
	'ZW'=>array('name'=>'Zimbabwe','code'=>'716')
	);
	
	protected $currencyArray = array(
	'AFA'=>array('name'=>'Afghanistan Afghani','code'=>'004'),
	'ALL'=>array('name'=>'Albanian Lek','code'=>'008'),
	'DZD'=>array('name'=>'Algerian Dinar','code'=>'012'),
	'USD'=>array('name'=>'US Dollar','code'=>'840'),
	'ESP'=>array('name'=>'Spanish Peseta','code'=>'724'),
	'FRF'=>array('name'=>'French Franc','code'=>'250'),
	'ADP'=>array('name'=>'Andorran Peseta','code'=>'020'),
	'AOA'=>array('name'=>'Kwanza','code'=>'973'),
	'XCD'=>array('name'=>'East Caribbean Dollar','code'=>'951'),
	'XCD'=>array('name'=>'East Caribbean Dollar','code'=>'951'),
	'ARS'=>array('name'=>'Argentine Peso','code'=>'032'),
	'AMD'=>array('name'=>'Armenian Dram','code'=>'051'),
	'AWG'=>array('name'=>'Aruban Guilder','code'=>'533'),
	'AUD'=>array('name'=>'Australian Dollar','code'=>'036'),
	'ATS'=>array('name'=>'Austrian Schilling','code'=>'040'),
	'AZM'=>array('name'=>'Azerbaijanian Manat','code'=>'031'),
	'BSD'=>array('name'=>'Bahamian Dollar','code'=>'044'),
	'BHD'=>array('name'=>'Bahraini Dinar','code'=>'048'),
	'BDT'=>array('name'=>'Bangladeshi Taka','code'=>'050'),
	'BBD'=>array('name'=>'Barbados Dollar','code'=>'052'),
	'BYB'=>array('name'=>'Belarussian Ruble','code'=>'112'),
	'RYR'=>array('name'=>'Belarussian Ruble','code'=>'974'),
	'BEF'=>array('name'=>'Belgian Franc','code'=>'056'),
	'BZD'=>array('name'=>'Belize Dollar','code'=>'084'),
	'XOF'=>array('name'=>'CFA Franc (BCEAO)','code'=>'952'),
	'BMD'=>array('name'=>'Bermuda Dollar','code'=>'060'),
	'INR'=>array('name'=>'Indian Rupee','code'=>'356'),
	'BTN'=>array('name'=>'Ngultrum','code'=>'064'),
	'BOB'=>array('name'=>'Boliviano','code'=>'068'),
	'BOV'=>array('name'=>'Mvdol','code'=>'984'),
	'BAM'=>array('name'=>'Convertible Marks','code'=>'977'),
	'BWP'=>array('name'=>'Pula','code'=>'072'),
	'NOK'=>array('name'=>'Norwegian Krone','code'=>'578'),
	'BRL'=>array('name'=>'Brazil Real','code'=>'986'),	
	'BND'=>array('name'=>'Brunei Dollar','code'=>'096'),
	'BGL'=>array('name'=>'Lev','code'=>'100'),
	'BGN'=>array('name'=>'Bulgarian Lev','code'=>'975'),	
	'BIF'=>array('name'=>'Burundi Franc','code'=>'108'),
	'KHR'=>array('name'=>'Cambodian Riel','code'=>'116'),
	'XAF'=>array('name'=>'CFA Franc (BEAC)','code'=>'950'),
	'CAD'=>array('name'=>'Canadian Dollar','code'=>'124'),
	'CVE'=>array('name'=>'Cape Verde Escudo','code'=>'132'),
	'KYD'=>array('name'=>'Cayman Islands Dollar','code'=>'136'),
	'XAF'=>array('name'=>'CFA Franc (BEAC)','code'=>'950'),
	'XAF'=>array('name'=>'CFA Franc (BEAC)','code'=>'950'),
	'CLP'=>array('name'=>'Chilean Peso','code'=>'152'),
	'CLF'=>array('name'=>'Unidates de fomento','code'=>'990'),
	'CNY'=>array('name'=>'Yuan Renminbi','code'=>'156'),
	'HKD'=>array('name'=>'Hong Kong Dollar','code'=>'344'),
	'MOP'=>array('name'=>'Pataca','code'=>'446'),	
	'COP'=>array('name'=>'Colombian Peso','code'=>'170'),
	'KMF'=>array('name'=>'Comoro Franc','code'=>'174'),
	'XAF'=>array('name'=>'CFA Franc (BEAC)','code'=>'950'),
	'CDF'=>array('name'=>'Franc Congolais','code'=>'976'),
	'NZD'=>array('name'=>'New Zealand Dollar','code'=>'554'),
	'CRC'=>array('name'=>'Costa Rican Colon','code'=>'188'),	
	'HRK'=>array('name'=>'Croatian Kuna','code'=>'191'),
	'CUP'=>array('name'=>'Cuban Peso','code'=>'192'),
	'CYP'=>array('name'=>'Cyprus Pound','code'=>'196'),
	'CZK'=>array('name'=>'Czech Koruna','code'=>'203'),
	'DKK'=>array('name'=>'Danish Krone','code'=>'208'),
	'DJF'=>array('name'=>'Djibouti Franc','code'=>'262'),
	'XCD'=>array('name'=>'East Caribbean Dollar','code'=>'951'),
	'DOP'=>array('name'=>'Dominican Peso','code'=>'214'),
	'TPE'=>array('name'=>'Timor Escudo','code'=>'626'),
	'IDE'=>array('name'=>'Rupiah','code'=>'360'),
	'ECS'=>array('name'=>'Sucre','code'=>'218'),
	'ECV'=>array('name'=>'Unidad de Valor Constante (UVC)','code'=>'983'),
	'EGP'=>array('name'=>'Egyptian Pound','code'=>'818'),
	'SVC'=>array('name'=>'El Salvador Colon','code'=>'222'),
	'XAF'=>array('name'=>'CFA Franc (BEAC)','code'=>'950'),
	'ERN'=>array('name'=>'Nafka','code'=>'232'),
	'EEK'=>array('name'=>'Kroon','code'=>'233'),
	'ETB'=>array('name'=>'Ethiopian Birr','code'=>'230'),
	'DKK'=>array('name'=>'Danish Krone','code'=>'208'),
	'XEU'=>array('name'=>'euro','code'=>'954'),
	'EUR'=>array('name'=>'European Currency Unit','code'=>'978'),
	'FKP'=>array('name'=>'Falkland Islands Pound','code'=>'238'),
	'FJD'=>array('name'=>'Fiji Dollar','code'=>'242'),
	'FIM'=>array('name'=>'Finnish Markka','code'=>'246'),
	'FRF'=>array('name'=>'French Franc','code'=>'250'),
	'FRF'=>array('name'=>'French Franc','code'=>'250'),
	'XPF'=>array('name'=>'CFP Franc','code'=>'953'),
	'XPF'=>array('name'=>'CFP Franc','code'=>'953'),
	'XAF'=>array('name'=>'CFA Franc (BEAC)','code'=>'950'),
	'GMD'=>array('name'=>'Dalasi','code'=>'270'),
	'GEL'=>array('name'=>'Lari','code'=>'981'),
	'DEM'=>array('name'=>'Deutsche Mark','code'=>'276'),
	'GHC'=>array('name'=>'Ghana Cedi','code'=>'288'),
	'GIP'=>array('name'=>'Gibraltar Pound','code'=>'292'),
	'GRD'=>array('name'=>'Drachma','code'=>'300'),
	'DKK'=>array('name'=>'Danish Krone','code'=>'208'),
	'XCD'=>array('name'=>'East Caribbean Dollar','code'=>'951'),
	'FRF'=>array('name'=>'French Franc','code'=>'250'),	
	'GTQ'=>array('name'=>'Guatemalan Quetzal','code'=>'320'),
	'GNF'=>array('name'=>'Guinea Franc','code'=>'324'),
	'GWP'=>array('name'=>'Guinea-Bissau Peso','code'=>'624'),	
	'GYD'=>array('name'=>'Guyana Dollar','code'=>'328'),
	'HTG'=>array('name'=>'Haiti Gourde','code'=>'332'),		
	'ITL'=>array('name'=>'Italian Lira','code'=>'380'),
	'HNL'=>array('name'=>'Honduran Lempira','code'=>'340'),
	'HUF'=>array('name'=>'Forint','code'=>'348'),
	'ISK'=>array('name'=>'Iceland Krona','code'=>'352'),	
	'IDR'=>array('name'=>'Indonesian Rupiah','code'=>'360'),
	'XDR'=>array('name'=>'SDR','code'=>'960'),
	'IRR'=>array('name'=>'Iranian Rial','code'=>'364'),
	'IQD'=>array('name'=>'Iraqi Dinar','code'=>'368'),
	'IEP'=>array('name'=>'Irish Pound','code'=>'372'),
	'ILS'=>array('name'=>'New Israeli Sheqel','code'=>'376'),
	'ITL'=>array('name'=>'Italian Lira','code'=>'380'),
	'JMD'=>array('name'=>'Jamaican Dollar','code'=>'388'),
	'JPY'=>array('name'=>'Yen','code'=>'392'),
	'JOD'=>array('name'=>'Jordanian Dinar','code'=>'400'),
	'KZT'=>array('name'=>'Kazakhstan Tenge','code'=>'398'),
	'KES'=>array('name'=>'Kenyan Shilling','code'=>'404'),	
	'KPW'=>array('name'=>'North Korean Won','code'=>'408'),
	'KRW'=>array('name'=>'South Korean Won','code'=>'410'),
	'KWD'=>array('name'=>'Kuwaiti Dinar','code'=>'414'),
	'KGS'=>array('name'=>'Kyrgyzstan Som','code'=>'417'),
	'LAK'=>array('name'=>'Laos Kip','code'=>'418'),
	'LVL'=>array('name'=>'Latvian Lats','code'=>'428'),
	'LBP'=>array('name'=>'Lebanese Pound','code'=>'422'),
	'ZAR'=>array('name'=>'Rand','code'=>'710'),
	'LSL'=>array('name'=>'Loti','code'=>'426'),
	'LRD'=>array('name'=>'Liberian Dollar','code'=>'430'),
	'LYD'=>array('name'=>'Libyan Dinar','code'=>'434'),
	'CHF'=>array('name'=>'Swiss Franc','code'=>'756'),
	'LTL'=>array('name'=>'Lithuanian Litas','code'=>'440'),
	'LUF'=>array('name'=>'Luxembourg Franc','code'=>'442'),
	'MKD'=>array('name'=>'Macedonian Denar','code'=>'807'),
	'MGF'=>array('name'=>'Malagasy Franc','code'=>'450'),
	'MWK'=>array('name'=>'Kwacha','code'=>'454'),
	'MYR'=>array('name'=>'Malaysian Ringgit','code'=>'458'),
	'MVR'=>array('name'=>'Maldives Rufiyaa','code'=>'462'),	
	'MTL'=>array('name'=>'Maltese Lira','code'=>'470'),	
	'FRF'=>array('name'=>'French Franc','code'=>'250'),
	'MRO'=>array('name'=>'Mauritanian Ouguiya','code'=>'478'),
	'MUR'=>array('name'=>'Mauritius Rupee','code'=>'480'),
	'MXN'=>array('name'=>'Mexican Peso','code'=>'484'),
	'MXV'=>array('name'=>'Mexican Unidad de Inversion (UDI)','code'=>'979'),	
	'MDL'=>array('name'=>'Moldovan Leu','code'=>'498'),
	'FRF'=>array('name'=>'French Franc','code'=>'250'),
	'MNT'=>array('name'=>'Mongolian Tugrik','code'=>'496'),
	'XCD'=>array('name'=>'East Caribbean Dollar','code'=>'951'),
	'MAD'=>array('name'=>'Moroccan Dirham','code'=>'504'),
	'MZM'=>array('name'=>'Mozambique Metical','code'=>'508'),
	'MMK'=>array('name'=>'Myanmar Kyat','code'=>'104'),
	'ZAR'=>array('name'=>'Rand','code'=>'710'),
	'NAD'=>array('name'=>'Namibia Dollar','code'=>'516'),	
	'NPR'=>array('name'=>'Nepalese Rupee','code'=>'524'),
	'ANG'=>array('name'=>'Netherlands Antillian Guilder','code'=>'532'),
	'NLG'=>array('name'=>'Netherlands Gulder','code'=>'528'),
	'XPF'=>array('name'=>'CFP Franc','code'=>'953'),
	'NZD'=>array('name'=>'New Zealand Dollar','code'=>'554'),
	'NIO'=>array('name'=>'Nicaraguan Cordoba Oro','code'=>'558'),	
	'NGN'=>array('name'=>'Nigerian Naira','code'=>'566'),
	'NZD'=>array('name'=>'New Zealand Dollar','code'=>'554'),	
	'NOK'=>array('name'=>'Norwegian Krone','code'=>'578'),
	'OMR'=>array('name'=>'Rial Omani','code'=>'512'),
	'PKR'=>array('name'=>'Pakistan Rupee','code'=>'586'),	
	'PAB'=>array('name'=>'Balboa','code'=>'590'),
	'PGK'=>array('name'=>'Papua New Guinea Kina','code'=>'598'),
	'PYG'=>array('name'=>'Paraguay Guarani','code'=>'600'),
	'PEN'=>array('name'=>'Peru Nuevo Sol','code'=>'604'),
	'PHP'=>array('name'=>'Philippine Peso','code'=>'608'),
	'NZD'=>array('name'=>'New Zealand Dollar','code'=>'554'),
	'PLN'=>array('name'=>'Poland Zloty','code'=>'985'),
	'PTE'=>array('name'=>'Portuguese Escudo','code'=>'620'),
	'USD'=>array('name'=>'US Dollar','code'=>'840'),
	'QAR'=>array('name'=>'Qatari Rial','code'=>'634'),
	'FRF'=>array('name'=>'French Franc','code'=>'250'),
	'RON'=>array('name'=>'Romanian Leu','code'=>'642'),
	'RUR'=>array('name'=>'Russian Ruble','code'=>'810'),
	'RUB'=>array('name'=>'Russian Ruble','code'=>'643'),
	'RWF'=>array('name'=>'Rwanda Franc','code'=>'646'),
	'XCD'=>array('name'=>'East Caribbean Dollar','code'=>'951'),
	'FRF'=>array('name'=>'East Caribbean Dollar','code'=>'951'),
	'XCD'=>array('name'=>'French Franc','code'=>'250'),
	'XCD'=>array('name'=>'East Caribbean Dollar','code'=>'951'),
	'SHP'=>array('name'=>'St. Helena Pound','code'=>'654'),
	'WST'=>array('name'=>'Tala','code'=>'882'),
	'ITL'=>array('name'=>'Italian Lira','code'=>'380'),
	'STD'=>array('name'=>'Sao Tome and Principe Dobra','code'=>'678'),
	'SAR'=>array('name'=>'Saudi Riyal','code'=>'682'),	
	'SCR'=>array('name'=>'Seychelles Rupee','code'=>'690'),
	'SLL'=>array('name'=>'Sierra Leone Leone','code'=>'694'),
	'SGD'=>array('name'=>'Singapore Dollar','code'=>'702'),
	'SKK'=>array('name'=>'Slovak Koruna','code'=>'703'),
	'SIT'=>array('name'=>'Slovenia Tolar','code'=>'705'),
	'SBD'=>array('name'=>'Solomon Islands Dollar','code'=>'90'),
	'SOS'=>array('name'=>'Somalia Shilling','code'=>'706'),
	'ZAR'=>array('name'=>'South African Rand','code'=>'710'),
	'ESP'=>array('name'=>'Spanish Peseta','code'=>'724'),
	'LKR'=>array('name'=>'Sri Lanka Rupee','code'=>'144'),
	'SDP'=>array('name'=>'Sudanese Dinar','code'=>'736'),
	'SRG'=>array('name'=>'Suriname Guilder','code'=>'740'),
	'NOK'=>array('name'=>'Norwegian Krone','code'=>'578'),
	'SZL'=>array('name'=>'Swaziland Lilangeni','code'=>'748'),
	'SEK'=>array('name'=>'Swedish Krona','code'=>'752'),
	'CHF'=>array('name'=>'Swiss Franc','code'=>'756'),
	'SYP'=>array('name'=>'Syrian Pound','code'=>'760'),
	'TWD'=>array('name'=>'New Taiwan Dollar','code'=>'901'),
	'TJR'=>array('name'=>'Tajik Ruble','code'=>'762'),
	'TZS'=>array('name'=>'Tanzanian Shilling','code'=>'834'),
	'THB'=>array('name'=>'Thai Baht','code'=>'764'),	
	'NZD'=>array('name'=>'New Zealand Dollar','code'=>'554'),
	'TOP'=>array('name'=>'Tonga Paanga','code'=>'776'),
	'TTD'=>array('name'=>'Trinidad and Tobago Dollar','code'=>'780'),
	'TND'=>array('name'=>'Tunisian Dinar','code'=>'788'),
	'TRL'=>array('name'=>'Turkish Lira','code'=>'792'),
	'TMM'=>array('name'=>'Manat','code'=>'795'),		
	'UGX'=>array('name'=>'Ugandan Shilling','code'=>'800'),
	'UAH'=>array('name'=>'Hryvnia','code'=>'980'),
	'AED'=>array('name'=>'UAE Dirham','code'=>'784'),
	'GBP'=>array('name'=>'Pound Sterling','code'=>'826'),
	'UYU'=>array('name'=>'Peso Uruguayo','code'=>'858'),
	'UZS'=>array('name'=>'Uzbekistan Sum','code'=>'860'),
	'VUV'=>array('name'=>'Vanuatu Vatu','code'=>'548'),
	'VEB'=>array('name'=>'Venezuela Bolivar','code'=>'862'),
	'VND'=>array('name'=>'Viet Nam Dong','code'=>'704'),	
	'XPF'=>array('name'=>'CFP Franc','code'=>'953'),
	'MAD'=>array('name'=>'Moroccan Dirham','code'=>'504'),
	'YER'=>array('name'=>'Yemeni Rial','code'=>'886'),
	'YUN'=>array('name'=>'Yugoslavian Dinar','code'=>'891'),
	'ZRN'=>array('name'=>'Unknown','code'=>'180'),
	'ZMK'=>array('name'=>'Zambia Kwacha','code'=>'894'),
	'ZWD'=>array('name'=>'Zimbabwe Dollar','code'=>'716')	
	);
	
    private $checkoutSession;	 
	
	//protected $formKey;
    /**
     * 
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
	 * @param \Magento\Sales\Model\OrderFactory $orderFactory,
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
      public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,		
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Alrajhi\iPay\Helper\iPay $helper,		
        \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender,
        \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory,
        \Magento\Checkout\Model\Session $checkoutSession      
              
    ) {
        $this->helper = $helper;				
        $this->orderSender = $orderSender;
        $this->httpClientFactory = $httpClientFactory;
        $this->checkoutSession = $checkoutSession;

		//Magento 2.3.2 specific
		/*$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
		$FormKey = $objectManager->get('Magento\Framework\Data\Form\FormKey'); 
		$this->formKey = $FormKey->getFormKey();	
		*/
        parent::__construct(
            $context,
            $registry,			
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger
        );

    }

    public function canUseForCurrency($currencyCode) {
        if (!in_array($currencyCode, $this->_supportedCurrencyCodes)) {
            return false;
        }
        return true;
    }

    public function getRedirectUrl() {
        return $this->helper->getUrl($this->getConfigData('redirect_url'));
    }

    public function getReturnUrl() {
        return $this->helper->getUrl($this->getConfigData('return_url'));
    }

    public function getCancelUrl() {
        return $this->helper->getUrl($this->getConfigData('cancel_url'));
    }

    /**
     * Return url according to environment
     * @return string
     */
    public function getCgiUrl() {     
        return $this->getConfigData('gatewayurl');
    }
	
	
    public function buildCheckoutRequest() {
        $order = $this->checkoutSession->getLastRealOrder();
        
		$baddress = $order->getBillingAddress()->getStreet1();
		if ($order->getBillingAddress()->getStreet2()!= "")
		$baddress = $baddress.' '.$order->getBillingAddress()->getStreet2();
	
		$saddress = $order->getShippingAddress()->getStreet1();
		if ($order->getShippingAddress()->getStreet2() != "")
		$saddress = $saddress.' '.$order->getShippingAddress()->getStreet2();
      	
		$country= $this->countryArray[$order->getShippingAddress()->getCountryId()];
		$currency = $this->currencyArray[$order->getOrderCurrencyCode()];	
		
        $params = array();
        
		$ReqAction = "action=1&"; //Purchase only
		$ReqAmount = "amt=".round($order->getBaseGrandTotal(), 2)."&";
		$ReqTrackId = "trackid=".substr(hash('sha256', mt_rand() . microtime()), 0, 20)."&";
		$ReqTranportalId = "id=".$this->getConfigData('portalid')."&";
		$ReqTranportalPassword = "password=".$this->getConfigData('portalpwd')."&";
		$ReqCurrency = "currencycode=".$currency['code']."&"; 
		$ReqLangid = "langid=USA&";
	
		/* Shipping */
		$Reqship_To_Postalcd = "ship_To_Postalcd=".$order->getShippingAddress()->getPostcode()."&";
		$Reqship_To_Address = "ship_To_Address=".$saddress."&";
		$Reqship_To_LastName = "ship_To_LastName=".$order->getShippingAddress()->getLirstName()."&";
		$Reqship_To_FirstName = "ship_To_FirstName=".$order->getShippingAddress()->getFirstName()."&";
		$Reqship_To_Phn_Num = "ship_To_Phn_Num=".$order->getShippingAddress()->getTelephone()."&";
		$Reqship_To_CountryCd = "ship_To_CountryCd=".$country['code']."&"; 
		
		/* Card Holder Details */
		$Reqcard_PostalCd = "card_PostalCd=".$order->getBillingAddress() -> getPostcode()."&";
		$Reqcard_Address = "card_Address=".$baddress."&";
		$Reqcard_Phn_Num = "card_Phn_Num=".$order->getBillingAddress()-> getTelephone()."&";
		$Reqcust_email = "cust_email=".$order->getBillingAddress() -> getEmail()."&";
	
		//$ReqResponseUrl = "&responseURL=".$this->getReturnUrl()."&form_key=".$this->formKey."&";
		//$ReqErrorUrl = "&errorURL=".$this->getReturnUrl()."&form_key=".$this->formKey."&";
		
		$ReqResponseUrl = "&responseURL=".$this->getReturnUrl()."&";
		$ReqErrorUrl = "&errorURL=".$this->getReturnUrl()."&";
	
		$ReqUdf1 = "udf1=Test1&";	// UDF1 values 
		$ReqUdf2 = "udf2="."Test2"."&";	// UDF2 values 
		$ReqUdf3 = "udf3="."Test3"."&";	// UDF3 values 
		$ReqUdf5 = "udf5="."Test5"."&"; // UDF5 values to be set with udf4 values of configuration
		
		$ReqUdf4 = "udf4="."Magento_v.2.4_PHP_7.4&";	// UDF4 is a fixed value for tracking
				
	
		if($this->getConfigData('udf1') !="")
			$ReqUdf1 = "udf1=".$this->getConfigData('udf1')."&";
		if($this->getConfigData('udf2') !="")
			$ReqUdf2 = "udf2=".$this->getConfigData('udf2')."&";
		if($this->getConfigData('udf3') !="")
			$ReqUdf3 = "udf3=".$this->getConfigData('udf3')."&";
		if($this->getConfigData('udf4') !="")
			$ReqUdf5 = "udf5=".$this->getConfigData('udf4')."&";
	
		$TranRequest=$ReqAmount.$ReqAction.$ReqResponseUrl.$ReqErrorUrl.$ReqTrackId.$ReqCurrency.$ReqLangid.$ReqTranportalId.$ReqTranportalPassword.
		$Reqship_To_Postalcd.$Reqship_To_Address.$Reqship_To_LastName.$Reqship_To_FirstName.$Reqship_To_Phn_Num.$Reqship_To_CountryCd.$Reqcard_PostalCd.
		$Reqcard_Address.$Reqcard_Phn_Num.$Reqcust_email.$ReqUdf1.$ReqUdf2.$ReqUdf3.$ReqUdf4.$ReqUdf5;
		
		//echo  $TranRequest ;		 
		$req='';
		if($this->getConfigData('encryption') == 'aesiv')
			$req = "&trandata=".$this->encryptAES($TranRequest,$this->getConfigData('resourcekey'));
		elseif($this->getConfigData('encryption') == 'tdes')
			$req = "&trandata=".$this->encryptTDES($TranRequest,$this->getConfigData('resourcekey'));
		  
		$req = $req.$ReqErrorUrl.$ReqResponseUrl."&tranportalId=".$this->getConfigData('portalid');
		
		
		//$req .= '&form_key='.$this->formKey;
		
		$params["url"] =$this->getConfigData('gatewayurl').$req;
       
        return $params;
    }

    //validate response
    public function validateResponse($returnParams) {
		$validated = "validated";
        
		$ResErrorText=(isset($returnParams['ErrorText'])? $returnParams['ErrorText'] : null); 	  	//Error Text/message
		
		if($ResErrorText!=null)		
			return $ResErrorText;
		
		$ResTranData= (isset($returnParams['trandata'])? $returnParams['trandata'] : null);
		if($this->getConfigData('encryption') == 'tdes')
			$ResTranData= (isset($returnParams['trandatacbc'])? $returnParams['trandatacbc'] : null);
			
		if($ResTranData !=null)
		{
			//Decryption logice starts
			try
			{
				$decryptedData='';
				if($this->getConfigData('encryption')== 'aesiv')
					$decrytedData=$this->decryptAES($ResTranData,$this->getConfigData('resourcekey'));
				elseif($this->getConfigData('encryption') == 'tdes')
					$decrytedData=$this->decryptTDES($ResTranData,$this->getConfigData('resourcekey'));
				$res='';
				parse_str($decrytedData,$res);
				$log['response'] = $decryptedData;
				$this->logger->debug($log);
				//This error handling added for extra precaution
				$ResErrorText=(isset($res['ErrorText'])? $res['ErrorText'] : null); 	  	//Error Text/message
				if($ResErrorText!=null)		
					return $ResErrorText;
				
				$ResTrackID = $res['trackid'];
				$ResAmount = $res['amt'];
				$ResResult = $res['result'];
				
				$flag = $this->verify_payment($ResAmount,$ResTrackID,$this->getConfigData('portalid'),$this->getConfigData('portalpwd'),$this->getConfigData('resourcekey'),$this->getConfigData('s2surl'));
				
				if ($flag && isset($ResResult) && strtoupper($ResResult) == 'CAPTURED') 
					return $validated;
				else
					return 'Error::Payment data validation failed...';
			}
			catch(Exception $ex)
			{
				return $ex->getMessage();
			}
		}        
    }

	public function verify_payment($amt,$transid,$tranportalid,$tranportalpassword,$termresourcekey,$url='')
    {
        if(Empty($url)) return true; //bypass verification
		
		try
		{
			$trackid = md5(rand());
			$tranhashkey = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_@"), 0, 16);
			
			$request = "<request><currency>".$amt."</currency><transid>".$transid."</transid>";
			$request .="<amt>".$amt."</amt><action>8</action><trackid>".$trackid."</trackid>";
			$request .="<udf5>TrackID</udf5><tranhashkey>".$tranhashkey."</tranhashkey>";
			$request .="<id>".$tranportalid."</id><password>".$tranportalpassword."</password></request>";

			$tranhash = base64_encode(hash_hmac('sha256', $request, $tranhashkey));
			
			$trandata = $this->encryptTDES($request,$termresourcekey);
			if($this->getConfigData('encryption')== 'aesiv')
				$trandata = $this->encryptAES($request,$termresourcekey);
				
			$req = "<trandata>".$trandata."</trandata><id>".$tranportalid."</id><password>".$tranportalpassword."</password><tranhash>".$tranhash."</tranhash>";
			//echo $req;
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSLVERSION, 6);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_TIMEOUT, 60);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $req);
			$response = curl_exec($curl);
			$curlerr = curl_error($curl);
			
			if($curlerr !='')
				return false;
			else 
			{
				$simple = "<response>".$response."</response>";
				$p = xml_parser_create();
				xml_parse_into_struct($p, $simple, $vals, $index);
				xml_parser_free($p);				
				$trandata=$vals[1]['value'];
				$tranhash = $vals[2]['value'];
				
				$decData=$this->decryptTDES($trandata,$termresourcekey);
				if((strrpos($decData,'^')+1) == strlen($decData)) //remove caret symbols from end
					$decData = rtrim($decData,'^');
			 					
				$p = xml_parser_create();
				xml_parse_into_struct($p, "<root>".$decData."</root>", $vals, $index);
				xml_parser_free($p);				
				
				$res=$vals[1]['value'];
				$hashkey='';
				foreach($vals as $v) {
					if($v['tag'] == 'TRANHASHKEY') {
						$hashkey = $v['value'];
						break;
					}
				}
				//echo $decData.'<br>';
				//print_r($vals);
				
				$Cmphash = base64_encode(hash_hmac('sha256', $decData, $hashkey));
				
				//echo $hashkey.'<br>'.$Cmphash.'<br>'.$tranhash;
				//exit();
				if($res == 'SUCCESS' && $Cmphash == $tranhash)
				{					
					return true;
				}
				else
				{
					return false;
				}	
			}			
		}
		catch (Exception $e)
		{
			return false;
		}
    }

    public function postProcessing(\Magento\Sales\Model\Order $order,
            \Magento\Framework\DataObject $payment, $response) {
        
		$ResTranData= (isset($response['trandata'])? $response['trandata'] : null);
		if($this->getConfigData('encryption') == 'tdes')
			$ResTranData= (isset($response['trandatacbc'])? $response['trandatacbc'] : null);
			
		if($ResTranData !=null)
		{
			$decryptedData='';
			if($this->getConfigData('encryption')== 'aesiv')
				$decrytedData=$this->decryptAES($ResTranData,$this->getConfigData('resourcekey'));
			elseif($this->getConfigData('encryption') == 'tdes')
				$decrytedData=$this->decryptTDES($ResTranData,$this->getConfigData('resourcekey'));
			$res='';
			parse_str($decrytedData,$res);
			$ResResult = $res['result'];
			
			$payment->setTransactionId($res['tranid'])       
			->setPreparedMessage('SUCCESS')
			->setShouldCloseParentTransaction(true)
			->setIsTransactionClosed(0)
			->setAdditionalInformation('iPay_PaymentID', $res['paymentid'])
			->setAdditionalInformation('iPay_order_status', $ResResult)
			->registerCaptureNotification($res['amt'],true);
			
			//$this->logger->debug($res);			
			
			$order->setTotalPaid($res['amt']); 		
			$order->setState(Order::STATE_PROCESSING)->setStatus(Order::STATE_PROCESSING);
			$order->save();
			//$this->orderSender->send($order);//post ordering function (Optional)
		}
		
        $invoice = $payment->getCreatedInvoice();
        /* Uncomment this code if mail is configured
		if ($invoice && !$order->getEmailSent()) {
            $this->_orderSender->send($order);
            $order->addStatusHistoryComment(
                __('You notified customer about invoice #%1.', $invoice->getIncrementId())
            )->setIsCustomerNotified(
                true
            )->save();
        }*/
    }
	
	/* AES IV 256 Bit  Encryption/Decryption Methods */
	public function encryptAES($str,$key) {		
		$str = $this->pkcs5_pad($str); 
		$encrypted = openssl_encrypt($str, $this->AES_METHOD, $key, OPENSSL_ZERO_PADDING, $this->AES_IV);
		$encrypted = base64_decode($encrypted);
		$encrypted = unpack('C*', ($encrypted));
		$encrypted = $this->byteArray2Hex($encrypted);
		$encrypted = urlencode($encrypted);
		return $encrypted;
	}
	
	public function decryptAES($code,$key) { 		
		$code = $this->hex2ByteArray(trim($code));
		$code= $this->byteArray2String($code);	  
		$code = base64_encode($code);
		$decrypted = openssl_decrypt($code, $this->AES_METHOD, $key, OPENSSL_ZERO_PADDING, $this->AES_IV);
		return $this->pkcs5_unpad($decrypted);
	}
	
	public function pkcs5_pad ($text) {
		$blocksize = openssl_cipher_iv_length($this->AES_METHOD);
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}
	
	public function pkcs5_unpad($text) {
		$pad = ord($text[strlen($text)-1]);
		if ($pad > strlen($text)) {
			return false;	
		}
		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
			return false;
		}
		return substr($text, 0, -1 * $pad);
    }
	
	public function byteArray2Hex($byteArray) {
		$chars = array_map("chr", $byteArray);
		$bin = join($chars);
		return bin2hex($bin);
	}
	
	public function hex2ByteArray($hexString) {
		$string = hex2bin($hexString);
		return unpack('C*', $string);
	}
	
	public function byteArray2String($byteArray) {
		$chars = array_map("chr", $byteArray);
		return join($chars);
	}
	
	// TDES Functions start
	public function encryptTDES($payload, $key) {  
		$chiper = "DES-EDE3-CBC";  //Algorthim used to encrypt
		if((strlen($payload)%8)!=0) {
			//Perform right padding
			$payload = $this->rightPadZeros($payload);
		}
		//$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($chiper));
		$iv="00000000";
		$encrypted = openssl_encrypt($payload, $chiper, $key,OPENSSL_RAW_DATA,$iv);
		
		$encrypted=unpack('C*', ($encrypted));
		$encrypted=$this->byteArray2Hex($encrypted);
		return strtoupper($encrypted);  
	}
	
	public function decryptTDES($data, $key) {
		$chiper = "DES-EDE3-CBC";  //Algorthim used to decrypt
		$data = $this->hex2ByteArray($data);
		$data = $this->byteArray2String($data);
		$data = base64_encode($data);
		//$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($chiper));
		$iv="00000000";
		$decrypted = openssl_decrypt($data, $chiper, $key, OPENSSL_ZERO_PADDING,$iv);
		return $decrypted;
	} 
	
	public function rightPadZeros($Str) {
		if(null == $Str){
			return null;
		}
		$PadStr = $Str;
		
		for ($i = strlen($Str);($i%8)!=0; $i++) {
			$PadStr .= "^";
		}
		return $PadStr;
	}
	// TDES Functions end
	//End of Encryption/Decryption methods
	
}
