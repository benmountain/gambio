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

   @file       system/overloads/HeaderExtenderComponent/EasymarketingHeaderExtender.inc.php
   @version    07.04.2014 - 20:34
   ---------------------------------------------------------------------------------------*/


class EasymarketingHeaderExtender extends EasymarketingHeaderExtender_parent
{
	function proceed() 
	{
		parent::proceed();
		
		if (defined('MODULE_EASYMARKETING_STATUS') && MODULE_EASYMARKETING_STATUS == 'True') 
		{
			if(defined('MODULE_EASYMARKETING_GOOGLE_SITE_VERIFICATION_META_TAG') && MODULE_EASYMARKETING_GOOGLE_SITE_VERIFICATION_META_TAG != '')
			{
				echo MODULE_EASYMARKETING_GOOGLE_SITE_VERIFICATION_META_TAG;
			}
		}
	}
}