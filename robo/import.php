<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

define("GLOBAL_PATH",'/var/www/html/marpa_intranet/');

include_once(GLOBAL_PATH . "cfg/cfg.main.php");
require_once(PGSQLDB_CLASS);

require_once(CLASSE_PATH.'core.class.php');
require_once(CLASSE_PATH.'dbpg.class.php');
require_once(CLASSE_PATH.'sendmail.php');
require_once(GLOBAL_PATH.'smtp/smtp.php');  

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'teraware_db.php';

if(!isset($_SESSION))
    session_start();

$_SESSION['SI_USUCODIGO'] = 1;

class Import extends edz_db{
	public function __construct()
	{
		// $db = new edz_db('pgsql.teraware.info', 'teraware', 'postlocal', 'teraware');
		$db = new edz_db(DB_HOST, DB_USER, DB_PASS, DB_BASE);
		// print_r($db);
		return $db;
	}
	
	
}
