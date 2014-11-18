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

   @file       system/overloads/EasymarketingCheckoutSuccessExtender/EasymarketingCheckoutSuccessExtender.inc.php
   @version    26.09.2014 - 19:35
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
							
				$this->v_output_buffer['EM_CS_TP'] .= $output;
			}
						
			if(MODULE_EM_ACTIVATE_FACEBOOK_TRACKING == 'True' && MODULE_EM_FACEBOOK_CONVERSION_TRACKING_CODE != '')
			{
				$output = MODULE_EM_FACEBOOK_CONVERSION_TRACKING_CODE;
				
				if($t_amount > 0)
				{
					$output = preg_replace("/\'value\':\'(\d.*\d|\d)\'/", "'value':'".$t_amount."'", $output);
					$output = preg_replace("/cd\[value\]=(\d.*\d|\d)&/", "cd[value]=".$t_amount."&", $output);
				}
				
				$this->v_output_buffer['EM_CS_TP'] .= $output;
			}
				
			if(MODULE_EM_REMARKETING_STATUS == 'True' && MODULE_EM_REMARKETING_CODE != '')
			{
				$ecomm_pagetype = 'purchase';
							
				$t_productIds = array();
				$t_productQtys = array();
							
				foreach($t_order->products as $product)
				{
					$t_productIds[] = (int)$product['id'];
					$t_productQtys[] = $product['qty'];
				}
							
				$ecomm_prodid = implode(',', $t_productIds);
				$ecomm_totalvalue = $t_amount;
							
				$additional_parameters[] = array('ecomm_quantity', implode(',', $t_productQtys));
					
				$remarketing_code = MODULE_EM_REMARKETING_CODE;
				$remarketing_code = str_replace("ecomm_prodid: 'REPLACE_WITH_VALUE'", "ecomm_prodid: '".$ecomm_prodid."'", $remarketing_code);
				$remarketing_code = str_replace("ecomm_pagetype: 'REPLACE_WITH_VALUE'", "ecomm_pagetype: '".$ecomm_pagetype."'", $remarketing_code);
				$remarketing_code = str_replace("ecomm_totalvalue: 'REPLACE_WITH_VALUE'", "ecomm_totalvalue: '".$ecomm_totalvalue."'", $remarketing_code);
				$remarketing_code = str_replace("value=0", "value=".$t_amount, $remarketing_code);
							
				if(count($additional_parameters) > 0)
				{
					$t_additional_tags = array();
								
					foreach($additional_parameters as $key => $entry)
					{
						array_push($t_additional_tags, $entry[0] . ": '" . $entry[1] . "'");
					}
								
					$remarketing_code = str_replace('};', implode(',', $t_additional_tags) . ',};', $remarketing_code);
				}
						
				$this->v_output_buffer['EM_CS_TP'] .= $remarketing_code;
			}
		}
	}
}
