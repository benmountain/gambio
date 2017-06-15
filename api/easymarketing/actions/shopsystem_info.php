<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul
   
   modified eCommerce Shopsoftware
   http://www.modified-shop.org
   Copyright (c) 2009 - 2014 [www.modified-shop.org]
   
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   -----------------------------------------------------------------------------------------
   
   @modified_by Easymarketing AG, Florian Ressel <florian.ressel@easymarketing.de>

   @file       api/easymarketing/actions/shopsystem_info.php
   @version    v3.0.2
   @updated    15.06.2017 - 22:45
   ---------------------------------------------------------------------------------------*/

if(file_exists(DIR_FS_CATALOG.'release_info.php'))
{
	include(DIR_FS_CATALOG.'release_info.php');
}

if(isset($gx_version))
{
	$_gx_version = explode(' ', $gx_version);
	$gx_version = $_gx_version[0];
} else {
	$gx_version = 'unknown';
}

$easymarketingText = MainFactory::create('LanguageTextManager', 'easymarketing');

$shopsystem_info_array = array(
            					'shopsystem' => 'Gambio',
            					'shopsystem_human' => 'Gambio ' . $gx_version,
            					'shopsystem_version' => $gx_version,
            					'api_version' => $easymarketingText->get_text('modul_version')          
        					);
  
mod_stream_response($shopsystem_info_array);
?>