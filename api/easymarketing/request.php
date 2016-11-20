<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul

   Copyright (c) 2016 [www.easymarketing.de]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   -----------------------------------------------------------------------------------------
   
   @author		Florian Ressel <florian.ressel@easymarketing.de>

   @file       api/easymarketing/request.php
   @version    v3.0.0
   @updated    20.11.2016 - 19:31
   ---------------------------------------------------------------------------------------*/

chdir('../../');
require_once('includes/application_top.php');

// include easymarketing api header
require_once(DIR_FS_CATALOG.'api/easymarketing/includes/header.php');

// include easymarketing functions
require_once('includes/functions.php');

if(!isset($_GET['action']) or $_GET['action'] == '')
{
	die('Invalid action.');
} else {	
	if(file_exists(DIR_FS_CATALOG.'api/easymarketing/actions/' . $_GET['action'] . '.php'))
	{
		require_once(DIR_FS_CATALOG.'api/easymarketing/actions/' . $_GET['action'] . '.php');
	} else {
		die('Unknown action ' . $_GET['action'] . '.');
	}
}

?>