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

   @file       api/easymarketing/includes/auth.php
   @version    07.04.2014 - 20:34
   ---------------------------------------------------------------------------------------*/

@ini_set('display_errors', false);
error_reporting(0);

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
  die('Direct Access to this location is not allowed.');
}

// set request parameters shop_token
$shop_token = (isset($_GET['shop_token']) ? $_GET['shop_token'] : NULL);

if (!isset($shop_token) && $shop_token != MODULE_EASYMARKETING_SHOP_TOKEN or !defined('MODULE_EASYMARKETING_STATUS') or MODULE_EASYMARKETING_STATUS != 'True') 
{
  	// wrong authentification
  	die('Direct Access to this location is not allowed.');
}