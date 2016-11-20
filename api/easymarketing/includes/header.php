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

   @file       api/easymarketing/includes/header.php
   @version    v3.0.0
   @updated    20.11.2016 - 19:31
   ---------------------------------------------------------------------------------------*/

@ini_set('display_errors', false);
error_reporting(0);

// set request parameters shop_token
$shop_token = (isset($_GET['shop_token']) ? $_GET['shop_token'] : NULL);
$debug = (isset($_GET['debug']) && $_GET['debug'] == 'true' ? true : false);
$lang = (isset($_GET['lang']) ? $_GET['lang'] : DEFAULT_LANGUAGE);

if (!isset($shop_token) or $shop_token != MODULE_EM_SHOP_TOKEN or !defined('MODULE_EM_STATUS') or MODULE_EM_STATUS != 'True') 
{
	// wrong authentification
  	die('Invalid access to this ressource.');
}

require_once(DIR_WS_CLASSES.'language.php');
$oLanguage = new language($lang);

define('MODULE_EM_DEBUG', $debug);