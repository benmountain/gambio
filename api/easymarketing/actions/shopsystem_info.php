<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul

   Copyright (c) 2016 [www.easymarketing.de]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   -----------------------------------------------------------------------------------------
   
   @author		Florian Ressel <florian.ressel@easymarketing.de>

   @file       api/easymarketing/actions/shopsystem_info.php
   @version    v3.0.0
   @updated    20.11.2016 - 19:31
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