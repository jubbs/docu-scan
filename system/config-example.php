<?php

Config::set('DOCU_SERVER_AUTH', 'user:pass');
Config::set('DOCU_SERVER_URL', 'http://url/code/workspace/docu-server/');
Config::set('DOCU_SERVER_AUTH_TOKEN', 'token');

Config::set('COMPUTER_ID', 0);

Config::set('MAIN_DATABASE', array(
    'host' => 'host.com',
    'username' => 'ssl_connection',
    'password' => 'pass',
    'name' => 'dbname'
));

Config::set('RECEIPT_TYPES', array(
    -1 => 'Purchase Receipt'
));

Config::set('DOC_TYPES', array(
    0 => 'Machinery List',
    2 => 'Signed Reg Forms',
    3 => 'Proof of GST',
    6 => 'Deposit Slip',
    16 => 'Miscellaneous',
    17 => 'Statement of Use'
));

Config::set('ALPHABET', array(
	0 => 'A',
	1 => 'B',
	2 => 'C',
	3 => 'D',
	4 => 'E',
	5 => 'F',
	6 => 'G',
	7 => 'H',
	8 => 'I',
	9 => 'J',
	10 => 'K',
	11 => 'L',
	12 => 'M',
	13 => 'N',
	14 => 'O',
	15 => 'P'
));
