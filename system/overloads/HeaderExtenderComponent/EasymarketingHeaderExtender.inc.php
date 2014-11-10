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
   @version    26.09.2014 - 19:34
   ---------------------------------------------------------------------------------------*/


class EasymarketingHeaderExtender extends EasymarketingHeaderExtender_parent
{
	function proceed() 
	{		
		if (defined('MODULE_EM_STATUS') && MODULE_EM_STATUS == 'True') 
		{
			if(defined('MODULE_EM_GOOGLE_SITE_VERIFICATION_META_TAG') && MODULE_EM_GOOGLE_SITE_VERIFICATION_META_TAG != '')
			{
				$this->v_output_buffer['EasymarketingHeaderExtender'] = MODULE_EM_GOOGLE_SITE_VERIFICATION_META_TAG;
			}
		}
		
		parent::proceed();
	}
}