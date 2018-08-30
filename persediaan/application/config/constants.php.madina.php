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


define('NM_PEMDA', "PEMERINTAH KABUPATEN MANDAILING NATAL");

define('STATUS_KEPALA_DAERAH', "BUPATI MANDAILING NATAL");
define('JABATAN_KEPALA_DAERAH', "BUPATI MANDAILING NATAL");
define('NAMA_KEPALA_DAERAH', "Drs.H.DAHLAN HASAN NASUTION");

define('STATUS_SEKDA', "Plt.SEKRETARIS DAERAH KABUPATEN");
define('NAMA_SEKDA', "Drs. MHD. SYAFEI LUBIS, M.Si");
define('JABATAN_SEKDA', "PEMBINA UTAMA MUDA");
define('NIP_SEKDA', 'NIP . 19591109 198602 1 002');

define('STATUS_KEPALA_DINAS', "Plt.KEPALA DINAS PENGELOLAAN KEUANGAN DAN ASET DAERAH");
define('NAMA_KEPALA_DINAS', "KAMAL RANGKUTI, S.Sos, MM");
define('JABATAN_KEPALA_DINAS', "PEMBINA");
define('NIP_KEPALA_DINAS', 'NIP . 19701228 199402 1 001');

define('NERACA_AWAL', 2008);


/* End of file constants.php */
/* Location: ./application/config/constants.php */