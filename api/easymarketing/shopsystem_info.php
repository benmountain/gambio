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
   @version    07.04.2014 - 20:34
   ---------------------------------------------------------------------------------------*/

chdir('../../');
require_once('includes/application_top.php');

// include easymarketing configuration
require_once(DIR_FS_CATALOG.'api/easymarketing/includes/config.php');

// include easymarketing authentification
require_once('includes/auth.php');

// include easymarketing functions
require_once('includes/functions.php');

if(file_exists(DIR_FS_DOCUMENT_ROOT.'release_info.php'))
{
	require_once(DIR_FS_DOCUMENT_ROOT.'release_info.php');
}

if(isset($gx_version))
{
	$gx_version_data = explode(' ', $gx_version);
	$gx_version = $gx_version_data[0];
} else {
	$gx_version = 'unknown';
}

$shopsystem_info_array = array(
            					'shopsystem' => 'Gambio',
            					'shopsystem_human' => 'Gambio ' . $gx_version,
            					'shopsystem_version' => $gx_version,
            					'api_version' => '1.2'          
        					);
  
mod_stream_response($shopsystem_info_array);