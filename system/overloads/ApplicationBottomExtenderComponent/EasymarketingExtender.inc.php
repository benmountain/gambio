<?php
/* --------------------------------------------------------------
   FrameRemover.php 2012-11 gambio
   Gambio GmbH
   http://www.gambio.de
   Copyright (c) 2012 Gambio GmbH
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   --------------------------------------------------------------


   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(ot_cod_fee.php,v 1.02 2003/02/24); www.oscommerce.com
   (C) 2001 - 2003 TheMedia, Dipl.-Ing Thomas Plänkers ; http://www.themedia.at & http://www.oscommerce.at
   (c) 2003 XT-Commerce - community made shopping http://www.xt-commerce.com ($Id: ot_cod_fee.php 1003 2005-07-10 18:58:52Z mz $)

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/


class EasymarketingExtender extends EasymarketingExtender_parent
{
	function proceed() 
	{
		global $PHP_SELF, $last_order;
		
		parent::proceed();
	  
	  	$t_order = new order($last_order);
	  	$t_amount = round($t_order->info['pp_total'], 2);
		
		if (defined('MODULE_EASYMARKETING_STATUS') && MODULE_EASYMARKETING_STATUS == 'True') 
		{
		  	include(DIR_FS_CATALOG.'api/easymarketing/conversion_tracker.php');
		}
	}
}