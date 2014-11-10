<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2014 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   
   @modified_by Easymarketing AG, Florian Ressel <florian.ressel@easymarketing.de>

   @file       api/easymarketing/shopsystem_info.php
   @version    30.10.2014 - 15:21
   ---------------------------------------------------------------------------------------*/

chdir('../../');
require_once('includes/application_top.php');

// include easymarketing api header
require_once(DIR_FS_CATALOG.'api/easymarketing/includes/header.php');

// include easymarketing functions
require_once('includes/functions.php');

if(file_exists(DIR_FS_DOCUMENT_ROOT.'release_info.php'))
{
	require_once(DIR_FS_DOCUMENT_ROOT.'release_info.php');
}

if(isset($gx_version))
{
	$_gx_version = explode(' ', $gx_version);
	$gx_version = $_gx_version[0];
} else {
	$gx_version = 'unknown';
}

$shopsystem_info_array = array(
            					'shopsystem' => 'Gambio',
            					'shopsystem_human' => 'Gambio ' . $gx_version,
            					'shopsystem_version' => $gx_version,
            					'api_version' => '1.9.0'          
        					);
  
mod_stream_response($shopsystem_info_array);
?>