<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul
   
   modified eCommerce Shopsoftware
   http://www.modified-shop.org
   Copyright (c) 2009 - 2014 [www.modified-shop.org]
   
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   -----------------------------------------------------------------------------------------
   
   @modified_by Easymarketing AG, Florian Ressel <florian.ressel@easymarketing.de>

   @file       api/easymarketing/actions/bestseller.php
   @version    v3.1.2
   @updated    15.06.2017 - 22:50
   ---------------------------------------------------------------------------------------*/

// set request parameters bestseller
$most_sold_since = (isset($_GET['most_sold_since']) ? (int) $_GET['most_sold_since'] : NULL);
$limit = (isset($_GET['limit']) ? (int) $_GET['limit'] : NULL);

// process request
if (isset($most_sold_since) && isset($limit)) 
{
	if(MODULE_EM_ROOT_CATEGORY > 0)
	{
		$sql_join_ptc = "JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." ptc ON ptc.products_id = op.products_id AND ptc.categories_id = '".(int)MODULE_EM_ROOT_CATEGORY."'";
	}
	
  	// sql query for best sellers
  	$products_query_raw = 	"SELECT op.products_id,
                                SUM(op.products_quantity) AS quantity
                           	FROM ".TABLE_ORDERS." o
                           	JOIN ".TABLE_ORDERS_PRODUCTS." op
                                ON o.orders_id = op.orders_id
						   	JOIN ".TABLE_PRODUCTS." p ON p.products_id = op.products_id
						   	".$sql_join_ptc."
                          	WHERE o.date_purchased >= '".xtc_db_prepare_input(date("Y-m-d H:i:s", $most_sold_since))."' AND p.products_status = 1
                       		GROUP BY op.products_id
                       		ORDER BY quantity DESC
                          	LIMIT ".$limit;

  	// make sql query
  	$products_query_result = xtc_db_query($products_query_raw);
	
	// init products array
	$products_array = array();
  
  	// check for result
  	if (xtc_db_num_rows($products_query_result) > 0) 
	{
    	while ($products = xtc_db_fetch_array($products_query_result)) 
		{
      		// build products array
      		$products_array[] = array(
										'id' => $products['products_id'],
                                		'sales' => intval($products['quantity'])
                                	);
    	}
  	}
	
	// build response array
	$response = array(
						'limit' => $limit,
                      	'most_sold_since' => $most_sold_since,
                      	'products' => $products_array
                      );
					  
	// output products
    mod_stream_response($response);
} else {
	mod_stream_invalid_request();
}
?>