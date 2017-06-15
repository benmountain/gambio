<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul

   Copyright (c) 2016-2017 [www.easymarketing.de]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   -----------------------------------------------------------------------------------------
   
   @author		Florian Ressel <florian.ressel@easymarketing.de>

   @file       GXUserComponents/overloads/HeaderExtenderComponent/EasymarketingHeaderExtender.inc.php
   @version    v3.1.2
   @updated    15.06.2017 - 22:50
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
