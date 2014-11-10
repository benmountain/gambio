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

   @file       api/easymarketing/categories.php
   @version    05.10.2014 - 00:32
   ---------------------------------------------------------------------------------------*/

chdir('../../');
require_once('includes/application_top.php');

// include easymarketing api header
require_once(DIR_FS_CATALOG.'api/easymarketing/includes/header.php');

// include easymarketing functions
require_once('includes/functions.php');

$parent_id = (isset($_GET['parent_id']) ? (int) $_GET['parent_id'] : NULL);

$oLanguage = new language($_GET['lang']);

// process request
if (isset($parent_id)) 
{
	// init array
	$categories_array = array();
	
  	// sql query for categories
  	$categories_query_raw = "SELECT c.categories_id,
                                  cd.categories_name
                             FROM ".TABLE_CATEGORIES." c
                             JOIN ".TABLE_CATEGORIES_DESCRIPTION." cd
                                  ON (c.categories_id = cd.categories_id
                                     AND cd.language_id = '".$oLanguage->language['id']."')
                            WHERE c.categories_status = '1'
                              AND c.categories_id = '".xtc_db_prepare_input($parent_id)."'";
  
  	// make sql query
  	$categories_query_result = xtc_db_query($categories_query_raw);
  
  	// check for result
  	if (xtc_db_num_rows($categories_query_result) > 0) 
	{
    	while ($categories = xtc_db_fetch_array($categories_query_result)) 
		{
      		// build categories array
      		$categories_array = array(
										'id' => $categories['categories_id'],
                                		'name' => $categories['categories_name'],
                                		'url' => xtc_href_link(FILENAME_DEFAULT, xtc_category_link($categories['categories_id'], $categories['categories_name']), 'NONSSL', false),
                                		'children' => mod_get_sub_categories($categories['categories_id'])
                                	);
    	}
  	} elseif ($parent_id == '0') {
    	// build categories array
    	$categories_array = array(
									'id' => $parent_id,
                              		'name' => STORE_NAME,
                              		'url' => xtc_href_link(FILENAME_DEFAULT, '', 'NONSSL', false),
                              		'children' => mod_get_sub_categories($parent_id)
                              	);
  	}
  
    // output categories  
    mod_stream_response($categories_array);  
} else {
	mod_stream_invalid_request();
}
?>