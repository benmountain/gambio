<?php
/* -----------------------------------------------------------------------------------------
   $Id:$

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

if (basename($PHP_SELF) == FILENAME_CHECKOUT_SUCCESS && defined('MODULE_EASYMARKETING_STATUS') && MODULE_EASYMARKETING_STATUS == 'True') 
{
    // include easymarketing configuration
    require_once(DIR_FS_CATALOG.'api/easymarketing/includes/config.php');

  $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, 'https://'.EASYMARKETING_API_URL.'/conversion_tracker?website_url='.str_replace(array('http://', 'https://'), '', HTTP_SERVER).'&access_token='.MODULE_EASYMARKETING_ACCESS_TOKEN);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
  
    $response = curl_exec($ch);

    curl_close($ch);

  $conversion_tracker_data = json_decode($response, true);

    // print out conversion tracker code
    if (is_array($conversion_tracker_data)) 
    {
      echo $conversion_tracker_data['code'];
      echo $conversion_tracker_data['img'];
    }
}

