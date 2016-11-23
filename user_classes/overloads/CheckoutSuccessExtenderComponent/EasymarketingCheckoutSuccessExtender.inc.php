<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul

   Copyright (c) 2016 [www.easymarketing.de]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   -----------------------------------------------------------------------------------------
   
   @author		Florian Ressel <florian.ressel@easymarketing.de>

   @file       user_classes/overloads/EasymarketingCheckoutSuccessExtender/EasymarketingCheckoutSuccessExtender.inc.php
   @version    v3.0.1
   @updated    23.11.2016 - 13:37
   ---------------------------------------------------------------------------------------*/

class EasymarketingCheckoutSuccessExtender extends EasymarketingCheckoutSuccessExtender_parent
{
	function proceed() 
	{	
		parent::proceed();
		
		if (defined('MODULE_EM_STATUS') && MODULE_EM_STATUS == 'True') 
		{	
			$t_order = new order($this->v_data_array['orders_id']);
			
			if(floatval($t_order->info['pp_total']) > 0)
			{
				$t_amount = number_format(floatval($t_order->info['pp_total']), 2, '.', '');
			} else {
				$t_amount = 0.00;
			}

			if(MODULE_EM_ACTIVATE_GOOGLE_TRACKING == 'True' && MODULE_EM_GOOGLE_CONVERSION_TRACKING_CODE != '')
			{	
				$output = MODULE_EM_GOOGLE_CONVERSION_TRACKING_CODE;
		
				if($t_amount > 0)
				{
					$output = preg_replace('/google_conversion_value*\s=*\s(\d.*\d|\d)/', 'google_conversion_value = ' . $t_amount, $output);
					$output = preg_replace('/value=(\d.*\d|\d)&/', 'value='.$t_amount.'&', $output);
				}
							
				$this->html_output_array[] = $output;
			}
						
			if(MODULE_EM_ACTIVATE_FACEBOOK_TRACKING == 'True' && MODULE_EM_FACEBOOK_CONVERSION_TRACKING_CODE != '')
			{
				$output = MODULE_EM_FACEBOOK_CONVERSION_TRACKING_CODE;
				
				if($t_amount > 0)
				{
					$output = preg_replace("/\'value\':\'(\d.*\d|\d)\'/", "'value':'".$t_amount."'", $output);
					$output = preg_replace("/cd\[value\]=(\d.*\d|\d)&/", "cd[value]=".$t_amount."&", $output);
				}
				
				$this->html_output_array[] = $output;
			}
				
			if(MODULE_EM_ACTIVATE_REMARKETING == 'True' && MODULE_EM_REMARKETING_CODE != '')
			{
				$ecomm_pagetype = 'purchase';
				$ecomm_prodids = array();
				$ecomm_quantity = array();
				
				$t_additional_parameters = array();							
				$t_productIds = array();
				$t_productQtys = array();
							
				foreach($t_order->products as $product)
				{
					$ecomm_prodids[] = (int)$product['id'];
					$ecomm_quantity[] = $product['qty'];
				}
							
				$ecomm_prodid = implode(',', $t_productIds);
				$ecomm_totalvalue = $t_amount;
							
				$t_additional_parameters[] = "ecomm_quantity: [".implode(',', $ecomm_quantity)."]";
					
				$remarketing_code = MODULE_EM_REMARKETING_CODE;
				$remarketing_code = str_replace("ecomm_prodid: 'REPLACE_WITH_VALUE'", "ecomm_prodid: [".implode(',', $ecomm_prodids)."]", $remarketing_code);
				$remarketing_code = str_replace("ecomm_pagetype: 'REPLACE_WITH_VALUE'", "ecomm_pagetype: '".$ecomm_pagetype."'", $remarketing_code);
				$remarketing_code = str_replace("ecomm_totalvalue: 'REPLACE_WITH_VALUE'", "ecomm_totalvalue: ".$ecomm_totalvalue, $remarketing_code);
				$remarketing_code = str_replace("value=0", "value=".$t_amount, $remarketing_code);	
				$remarketing_code = str_replace('};', implode(',', $t_additional_parameters) . ',};', $remarketing_code);
						
				$this->html_output_array[] = $remarketing_code;
			}
		}
	}
}
