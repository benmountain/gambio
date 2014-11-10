<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2014 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
  
   @copyright  Copyright (c) 2014, Easymarketing AG (http://www.easymarketing.de)
   @author     Florian Ressel <florian.ressel@easymarketing.de>
   
   @file       includes/external/easymarketing/classes/EasymarketingHelper.class.php
   @version    07.04.2014 - 20:34
   ---------------------------------------------------------------------------------------*/

class EasymarketingHelper
{
	
	/*
	 * get the website url
	 *
	 * @params $shop_url (string)
	 * @return string
	 */
	public function getWebsiteURL($protocol = false)
	{
       	$website_url = (!$protocol) ? str_replace(array('http://', 'https://'), '', HTTP_SERVER) : HTTP_SERVER;
  		$website_url .= DIR_WS_CATALOG;
		
		return $website_url;
	}
	
	/*
	 * generate secure shop token
	 *
	 * @return string
	 */ 
	public function generateShopToken()
	{
		return sha1(mt_rand(10,1000) . time());
	}
	
}

?>