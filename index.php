<?php

	// echo "<center>Tunggu beberapa detik... anda harus di logout dari aplikasi</center>";

	//  echo "<meta http-equiv='refresh' content='5;URL=http://simbada.sumutprov.go.id/login/logout'>";

	// echo "<meta http-equiv='refresh' content='5;URL=http://180.250.40.227/'>";
	// echo "<center>Tunggu beberapa detik... anda akan dialihkan ke halaman utama aplikasi</center>";
	// exit();

	// echo "<center>Sistem sedang dalam perbaikan, mohon maaf atas ketidaknyamananya<br>
	// Silahkan kembali beberapa saat lagi. Terima kasih<br><br>

	// Jika ada pertanyaan silahkan sampaikan melalui group facebook Team Aset Pemprovsu<br>
	// <a href='https://www.facebook.com/groups/asetpemprovsu/'' target='_blank'>Klik Disini </a>

	// </center>"; exit();

	define('ENVIRONMENT', 'production');

	if (defined('ENVIRONMENT'))
	{
		switch (ENVIRONMENT)
		{
			case 'development':
				error_reporting(E_ALL);
			break;

			case 'maintenance':
				require_once 'application/views/underconstruction.php'; ## call view
				exit();
			break;
		
			case 'testing':
			case 'production':
				error_reporting(0);
			break;

			default:
				exit('The application environment is not set correctly.');
		}
	}

	$system_path = 'system';

	$application_folder = 'application';

		if (defined('STDIN'))
	{
		chdir(dirname(__FILE__));
	}

	if (realpath($system_path) !== FALSE)
	{
		$system_path = realpath($system_path).'/';
	}

	$system_path = rtrim($system_path, '/').'/';

	if ( ! is_dir($system_path))
	{
		exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
	}

	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	define('EXT', '.php');

	define('BASEPATH', str_replace("\\", "/", $system_path));

	define('FCPATH', str_replace(SELF, '', __FILE__));

	define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

	if (is_dir($application_folder))
	{
		define('APPPATH', $application_folder.'/');
	}
	else
	{
		if ( ! is_dir(BASEPATH.$application_folder.'/'))
		{
			exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
		}

		define('APPPATH', BASEPATH.$application_folder.'/');
	}

require_once BASEPATH.'core/CodeIgniter.php';