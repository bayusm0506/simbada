<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


define('ADD', '[add]');
define('EDIT', 'edit');
define('REMOVE', 'remove');
define('VIEW', '[view]');
define('CETAK', 'cetak');


define('NM_PEMDA', "PEMERINTAH PROVINSI SUMATERA UTARA");
define('LOKASI', "MEDAN");
define('KODE_LOKASI', "11.02.0");


define('STATUS_KEPALA_DAERAH', "Plt. GUBERNUR SUMATERA UTARA");
define('JABATAN_KEPALA_DAERAH', "Plt. GUBERNUR SUMATERA UTARA");
define('NAMA_KEPALA_DAERAH', "Ir. H. TENGKU ERRY NURADi, M.Si");

define('STATUS_SEKDA', "SEKRETARIS DAERAH PROVINSI");
define('NAMA_SEKDA', "H. HASBAN RITONGA, SH");
define('JABATAN_SEKDA', "PEMBINA UTAMA");
define('NIP_SEKDA', 'NIP . 19570617 197701 1001');

define('STATUS_KEPALA_DINAS', "KEPALA BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH PROVSU");
define('NAMA_KEPALA_DINAS', "H. AGUS TRIPRIYONO, SE, M.Si, Ak, CA");
define('JABATAN_KEPALA_DINAS', "PEMBINA TK. 1");
define('NIP_KEPALA_DINAS', 'NIP . 19650301 199303 1 006');

define('NERACA_AWAL', 2006);


/* End of file constants.php */
/* Location: ./application/config/constants.php */