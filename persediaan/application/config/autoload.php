<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$autoload['packages'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in the system/libraries folder
| or in your application/libraries folder.
|
| Prototype:
|
|	$autoload['libraries'] = array('database', 'session', 'xmlrpc');
*/
$autoload['libraries'] = array('database', 'form_validation', 'table', 'pagination', 'session', 'auth', 'general', 'template','fpdf','cart');

$autoload['helper'] = array('url', 'form','file','html','sanjaya_helper','mediatama_helper','rupiah_helper','modul_helper');

$autoload['config'] = array();

$autoload['language'] = array();

$autoload['model'] = array();