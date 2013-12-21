<?php
/* -----------------------------------------------------------------------------------------
   $Id:$

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

class easymarketing {
  var $code, 
      $title, 
      $description, 
      $shoptoken,
      $accesstoken,
      $condition,
      $availibility1,
      $availibility2,
      $enabled;


  function easymarketing() {
    $this->code = 'easymarketing';
    $this->title = MODULE_EASYMARKETING_TEXT_TITLE;
    $this->description = MODULE_EASYMARKETING_TEXT_DESCRIPTION;
    $this->shoptoken = MODULE_EASYMARKETING_SHOP_TOKEN;
    $this->accesstoken = MODULE_EASYMARKETING_ACCESS_TOKEN;
    $this->condition = MODULE_EASYMARKETING_CONDITION_DEFAULT;
    $this->availibility1 = MODULE_EASYMARKETING_AVAILIBILLITY_STOCK_1;
    $this->availibility2 = MODULE_EASYMARKETING_AVAILIBILLITY_STOCK_0;
    $this->enabled = ((MODULE_EASYMARKETING_STATUS == 'True') ? true : false);
  }


  function process() {
    while (list($key, $value) = each($_POST['configuration'])) {
      xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '" . $value . "' WHERE configuration_key = '" . $key . "'");
    }
    if ($_POST['configuration']['MODULE_EASYMARKETING_SHOP_TOKEN'] != MODULE_EASYMARKETING_SHOP_TOKEN 
        || strlen(MODULE_EASYMARKETING_SHOP_TOKEN) != '32') 
    {
      xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".md5(xtc_rand(0, 9999999999))."' WHERE configuration_key = 'MODULE_EASYMARKETING_SHOP_TOKEN'");
    }
  }
  
  function display() {
    $contents = xtc_draw_form('modules', 'easymarketing.php', 'content=save','post');
    
    $module_keys = $this->keys();
    $keys_extra = array();
    for ($j = 0, $k = sizeof($module_keys); $j < $k; $j++) {
      $key_value_query = xtc_db_query("SELECT configuration_key,
                                              configuration_value,
                                              use_function,
                                              set_function
                                         FROM " . TABLE_CONFIGURATION . "
                                        WHERE configuration_key = '" . $module_keys[$j] . "'");
      $key_value = xtc_db_fetch_array($key_value_query);
      if ($key_value['configuration_key'] !='') {
        $keys_extra[$module_keys[$j]]['title'] = constant(strtoupper($key_value['configuration_key'] .'_TITLE'));
      }
      $keys_extra[$module_keys[$j]]['value'] = $key_value['configuration_value'];
      if ($key_value['configuration_key'] !='') {
        $keys_extra[$module_keys[$j]]['description'] = constant(strtoupper($key_value['configuration_key'] .'_DESC'));
      }
      $keys_extra[$module_keys[$j]]['use_function'] = $key_value['use_function'];
      $keys_extra[$module_keys[$j]]['set_function'] = $key_value['set_function'];
    }
    $module_info['keys'] = $keys_extra;
      
    while (list($key, $value) = each($module_info['keys'])) {
      $contents .= '<b>' . $value['title'] . '</b><br />' .  $value['description'].'<br />';
      if ($value['set_function']) {
        eval('$contents .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
      } else {
        $contents .= xtc_draw_input_field('configuration[' . $key . ']', $value['value']);
      }
      $contents .= '<br/><br/>';
    }
    
    $contents .= '<br/>' . xtc_button(BUTTON_SAVE) . xtc_button_link(BUTTON_BACK, xtc_href_link('easymarketing.php', xtc_get_all_get_params(array('content'))));
    
    return $contents;
  }


  function check() {
    if (!isset($this->_check)) {
      $check_query = xtc_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_EASYMARKETING_STATUS'");
      $this->_check = xtc_db_num_rows($check_query);
    }
    return $this->_check;
  }


  function install() {
    xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_STATUS', 'True',  '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
    xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_CONDITION_DEFAULT', 'new',  '6', '1', 'xtc_cfg_select_condition(', now())");
    xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_SHOP_TOKEN', '',  '6', '1', '', now())");
    xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_ACCESS_TOKEN', '',  '6', '1', '', now())");
    xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_AVAILIBILLITY_STOCK_0', 'available for order',  '6', '1', 'xtc_cfg_select_availibility(', now())");
    xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_AVAILIBILLITY_STOCK_1', 'in stock',  '6', '1', 'xtc_cfg_select_availibility(', now())");
  }


  function remove() {
    xtc_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }


  function keys() {
    return array('MODULE_EASYMARKETING_STATUS',
                 'MODULE_EASYMARKETING_SHOP_TOKEN',
                 'MODULE_EASYMARKETING_ACCESS_TOKEN',
                 'MODULE_EASYMARKETING_CONDITION_DEFAULT',
                 'MODULE_EASYMARKETING_AVAILIBILLITY_STOCK_1',
                 'MODULE_EASYMARKETING_AVAILIBILLITY_STOCK_0',
                 );
  }
}


//additional functions
function xtc_cfg_select_condition($configuration, $key) {
  $condition_dropdown = array(
                          array('id' => 'new', 'text' => 'Neu'),
                          array('id' => 'refurbished', 'text' => 'Erneuert'),
                          array('id' => 'used', 'text' => 'Gebraucht'),
                        );
  return xtc_draw_pull_down_menu('configuration['.$key.']', $condition_dropdown, $configuration);
}

function xtc_cfg_select_availibility($configuration, $key) {
  $availibility_dropdown = array(
                             array('id' => 'in stock', 'text' => 'Auf Lager'),
                             array('id' => 'available for order', 'text' => 'Bestellbar'),
                             array('id' => 'out of stock', 'text' => 'Nicht auf Lager'),
                             array('id' => 'preorder', 'text' => 'Vorbestellen'),
                           );
  return xtc_draw_pull_down_menu('configuration['.$key.']', $availibility_dropdown, $configuration);
}

?>