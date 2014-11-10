<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2014 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   
   @modified_byMODULE_EM AG, Florian Ressel <florian.ressel@easymarketing.de>

   @file       system/overloads/HeaderExtenderComponent/EasymarketingHeaderExtender.inc.php
   @version    07.04.2014 - 20:34
   ---------------------------------------------------------------------------------------*/

class EasymarketingHeaderExtender extends EasymarketingHeaderExtender_parent
{
	function proceed() 
	{
		parent::proceed();
		
		if (defined('MODULE_EM_STATUS') && MODULE_EM_STATUS == 'True') 
		{
			if(defined('MODULE_EM_GOOGLE_SITE_VERIFICATION_META_TAG') && MODULE_EM_GOOGLE_SITE_VERIFICATION_META_TAG != '')
			{
				echo MODULE_EM_GOOGLE_SITE_VERIFICATION_META_TAG;
			}
		}
	}
}