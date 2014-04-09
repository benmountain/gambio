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

   @file       includes/classes/Smarty/plugins/function.facebook_badge.php
   @version    07.04.2014 - 20:34
   ---------------------------------------------------------------------------------------*/

function smarty_function_facebook_badge($params, &$smarty) 
{  
	$facebook_badge = (defined('MODULE_EASYMARKETING_FACEBOOK_LIKE_BADGE_CODE') && MODULE_EASYMARKETING_FACEBOOK_LIKE_BADGE_CODE != '') ? MODULE_EASYMARKETING_FACEBOOK_LIKE_BADGE_CODE : '';
	
  	return $facebook_badge;
}