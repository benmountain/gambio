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

   @file       admin/easymarketing.php
   @version    01.10.2014 - 15:34
   ---------------------------------------------------------------------------------------*/

require('includes/application_top.php');

$coo_easymarketing_manager = MainFactory::create_object('Easymarketing');

if (isset($_GET['content']) && $_GET['content'] != '') {
  switch ($_GET['content']) {
    case 'install':
      	if ($coo_easymarketing_manager->check() == false)
	  	{
       		$coo_easymarketing_manager->install();
      	}
      	xtc_redirect(xtc_href_link('easymarketing.php', 'content=settings'));
      	break;
    case 'uninstall':
      	if ($coo_easymarketing_manager->check() == true) 
	  	{
        	$coo_easymarketing_manager->uninstall();
      	}
      	xtc_redirect(xtc_href_link('easymarketing.php'));
      	break;
	case 'stop_uninstall':
	   	xtc_redirect(xtc_href_link('easymarketing.php', 'content=settings'));
	  	break;
    case 'save':
      	if (is_array($_POST) && count($_POST) > 0) 
		{
        	if(!$coo_easymarketing_manager->process())
			{
				$error = true;
			} else {
				xtc_redirect(xtc_href_link('easymarketing.php', 'content=set_easymarketing_data'));
			}
      	}
      	xtc_redirect(xtc_href_link('easymarketing.php', 'content=settings&error=' . $error));
      	break;
	case 'save_mappings':
		if ($coo_easymarketing_manager->check() == true) 
	  	{
			$coo_easymarketing_manager->saveMappings();
			xtc_redirect(xtc_href_link('easymarketing.php', 'content=mappings'));
		}
		break;
	case 'set_easymarketing_data':
		if($coo_easymarketing_manager->checkAPIToken(MODULE_EM_API_TOKEN))
		{
			$coo_easymarketing_manager->updateEasymarketingData();	
			xtc_redirect(xtc_href_link('easymarketing.php', 'content=settings&success=true'));
		} else {
			xtc_redirect(xtc_href_link('easymarketing.php', 'content=settings&error=true'));
		}
		break;
	case 'update_overview':
		$coo_easymarketing_manager->updateOverview();
		xtc_redirect(xtc_href_link('easymarketing.php', 'content=overview'));
		break;
	case 'update_easymarketing_data':
		$coo_easymarketing_manager->updateEasymarketingData();
		xtc_redirect(xtc_href_link('easymarketing.php', 'content=update_overview'));
		break;
	case 'perform_google_site_verification':
		$coo_easymarketing_manager->performGoogleSiteVerification();
		xtc_redirect(xtc_href_link('easymarketing.php', 'content=update_overview'));
		break;
	case 'destroy_google_site_verification':
		$coo_easymarketing_manager->destroyGoogleSiteVerification();
		xtc_redirect(xtc_href_link('easymarketing.php', 'content=update_overview'));
		break;
  }
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr>
    	<td class="columnLeft2" width="<?php echo BOX_WIDTH; ?>" valign="top">
			<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
			<!-- left_navigation //-->
			<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
			<!-- left_navigation_eof //-->
    		</table>
		</td>
		<!-- body_text //-->
    	<td class="boxCenter" width="100%" valign="top">
		
      		<div class="pageHeading" style="background-image:url(images/gm_icons/gambio.png)"><?php echo MODULE_EM_HEADING_TITLE; ?></div><br />
      
      		<span class="main">
        		<table style="margin-bottom:5px" border="0" cellpadding="0" cellspacing="0" width="100%">
         			<tr class="dataTableHeadingRow">
          				<td class="dataTableHeadingContentText" style="width:1%; padding-right:20px; white-space: nowrap"><a href="easymarketing.php"><?php echo MODULE_EM_INFO_HEADING; ?></a></td>
          				<?php if ($coo_easymarketing_manager->check()) { echo '<td class="dataTableHeadingContentText" style="width:1%; padding-right:20px; white-space: nowrap"><a href="easymarketing.php?content=update_overview">'. MODULE_EM_OVERVIEW_HEADING.'</a></td>'; } ?>
          				<td class="dataTableHeadingContentText" style="width:1%; padding-right:20px; white-space: nowrap"><a href="easymarketing.php?content=settings"><?php echo MODULE_EM_SETTINGS_HEADING; ?></a></td>
           				<?php if ($coo_easymarketing_manager->check()) { ?><td class="dataTableHeadingContentText" style="width:1%; padding-right:20px; white-space: nowrap"><a href="easymarketing.php?content=mappings"><?php echo MODULE_EM_MAPPINGS_HEADING; ?></a></td><?php } ?>
         			</tr>
        		</table>
        
				<?php
                if(!empty($_GET['success']))
               	{
               		echo '<div style="margin:4px;"><h4>' . MODULE_EM_SUCCESS_HEADING . '</h4>' . MODULE_EM_SUCCESS_TEXT . '</div>';
               	}
                
               	if(!empty($_GET['error']))
               	{
                	echo '<div style="color:#C00; margin:4px;"><h4>' . MODULE_EM_ERROR_HEADING . '</h4>' . MODULE_EM_ERROR_TEXT . '</div>';
               	}
                ?>
        
        		<?php
        		if (!isset($_GET['content'])) {
        		?>
        		<table class="tableCenter">
          			<tr style="background-color: #FFFFFF;">
            			<td style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; padding: 0px 10px 11px 10px; text-align: justify">
                          <br />
                          <font color="#FF7A00"><strong>Vollautomatisiert und optimiert werben auf Google uvm.</strong></font>
              			  <a href="http://easymarketing.de/?partner=modified" target="_blank"><img src="images/easymarketing/logo-easymarketing.jpg" align="right" /></a>
                          <br />
                          <br />
                          <font color="#FF7A00"><strong>In drei Schritten durchstarten:</strong></font>
                          <ul>
                            <li style="list-style-type: circle !important;">Auf easymarketing.de die Shop-URL eingeben</li>
                            <li style="list-style-type: circle !important;">Automatische und dauerhafte Optimierung</li>
                            <li style="list-style-type: circle !important;">Registrieren</li>
                          </ul>
                          EM crawlt Ihren Shop, erkennt dabei alle besonders performanten Keywords, erstellt automatisch aus mehreren 100 verschiedenen Keywords &uuml;ber
                          1.000 verschiedene AdWordsAnzeigen, ver&ouml;ffentlicht diese bei Google &amp; optimiert die Ergebnisse permanent. Dank des intelligenten Algorithmus ist
                          EM vielfach effizienter, als wenn der Online-H&auml;ndler die Anzeigenverwaltung manuell vornehmen w&uuml;rde, es wird sehr viel mehr Traffic und
                          somit Umsatz generiert. Sie sparen somit Zeit und auch Geld, weil EM f&uuml;r Sie vollautomatisch arbeitetet und Ihr Budget mit Ber&uuml;cksichtigung
                          Ihrer Konkurrenz auf Google optimal g&uuml;nstig aussteuert. Die CPC-Gebote werden also automatisch berechnet, so dass Sie als Werbetreibender nicht zu
                          viel zahlen.
                          <ul>
                            <li style="list-style-type: circle !important;">Maximale Effizienz &uuml;ber die Werbeaktivit&auml;ten</li>
                            <li style="list-style-type: circle !important;">Automatische und dauerhafte Optimierung</li>
                            <li style="list-style-type: circle !important;">Hohe Zeitersparnis, da Kampagnen automatisch erstellt und gepflegt werden</li>
                          </ul>
                          <br />
                          <a href="http://easymarketing.de/?partner=modified" target="_blank"><span style="font-size:12px; color:#FF7A00;"><u><strong>Weitere Infos zu Easymarketing finden Sie unter www.easymarketing.de</strong></u></span></a>
            			</td>
          			</tr>
          			<tr style="background-color: #FFFFFF;">
            			<td>
              				<iframe style="background-color: transparent; border: 0px none transparent;padding: 0px; overflow: hidden;" seamless scrolling="no" frameborder="0" allowtransparency="true" width="300px" height="250px" src="http://api.easymarketing.de/demo_chart?website_url=<?php echo urlencode(HTTP_SERVER.DIR_WS_CATALOG); ?>&partner_id=modified&version=large"></iframe>
            			</td>
          			</tr>
        		</table>
			<?php
            } elseif($_GET['content'] == 'overview') {
            ?>
			<table width="600" border="0" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; padding: 0px 10px 11px 10px; text-align: justify">
              <tr class="dataTableHeadingRow">
                <td colspan="2"><h4><?php echo MODULE_EM_OVERVIEW_SETUP_TITLE; ?></h4></td>
              </tr>
              <tr>
                <td width="305"><?php echo MODULE_EM_OVERVIEW_API_STATUS_TITLE; ?></td>
                <td width="285"><?php echo (MODULE_EM_API_STATUS == 1) ? '<span style="color:#3C6">&#10003;</span>' : '<span style="color:#C00">&#10006;</span>'; ?></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EM_OVERVIEW_CONFIGURE_ENDPOINTS_STATUS_TITLE; ?></td>
                <td><?php echo(MODULE_EM_CONFIGURE_ENDPOINTS_STATUS == 1) ? '<span style="color:#3C6">&#10003;</span>' : '<span style="color:#C00">&#10006;</span>'; ?></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EM_OVERVIEW_GOOGLE_TRACKING_STATUS_TITLE; ?></td>
                <td><?php echo (MODULE_EM_ACTIVATE_GOOGLE_TRACKING == 'True') ? '<span style="color:#3C6">&#10003;</span>' : '<span style="color:#C00">&#10006;</span>'; ?></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EM_OVERVIEW_FACEBOOK_TRACKING_STATUS_TITLE; ?></td>
                <td><?php echo (MODULE_EM_ACTIVATE_FACEBOOK_TRACKING == 'True') ? '<span style="color:#3C6">&#10003;</span>' : '<span style="color:#C00">&#10006;</span>'; ?></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EM_OVERVIEW_REMARKETING_STATUS_TITLE; ?></td>
                <td><?php echo (MODULE_EM_REMARKETING_STATUS == 'True') ? '<span style="color:#3C6">&#10003;</span>' : '<span style="color:#C00">&#10006;</span>'; ?></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EM_OVERVIEW_GOOGLE_SITE_VERIFICATION_STATUS_TITLE; ?></td>
                <td><?php echo (MODULE_EM_GOOGLE_SITE_VERIFICATION_STATUS == 1) ? '<span style="color:#3C6">&#10003;</span> <a href="easymarketing.php?content=destroy_google_site_verification">'.MODULE_EM_DESTROY_GOOGLE_SITE_VERIFICATION_BUTTON.'</a>' : '<span style="color:#C00">&#10006;</span> <a href="easymarketing.php?content=check_google_site_verification">'.MODULE_EM_PEFORM_GOOGLE_SITE_VERIFICATION_BUTTON.'</a>'; ?>
                </td>
              </tr>
              <tr class="dataTableHeadingRow">
                <td colspan="2"><h4><?php echo MODULE_EM_OVERVIEW_LAST_CRAWL_TITLE; ?></h4></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EM_OVERVIEW_LAST_CRAWL_DATE_TITLE; ?></td>
                <td><?php echo (MODULE_EM_LAST_CRAWL_DATE > 0) ? date('d.m.Y - H:i', MODULE_EM_LAST_CRAWL_DATE) : '--.--.-- - --:--'; ?></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EM_OVERVIEW_LAST_CRAWL_CATEGORIES_COUNT_TITLE; ?></td>
                <td><?php echo (MODULE_EM_LAST_CRAWL_CATEGORIES_COUNT > 0) ? MODULE_EM_LAST_CRAWL_CATEGORIES_COUNT : '0'; ?></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EM_OVERVIEW_LAST_CRAWL_PRODUCTS_COUNT_TITLE; ?></td>
                <td><?php echo (MODULE_EM_LAST_CRAWL_PRODUCTS_COUNT > 0) ? MODULE_EM_LAST_CRAWL_PRODUCTS_COUNT : '0'; ?></td>
              </tr>
           </table>
           
           <hr />
           <?php echo xtc_button_link(MODULE_EM_UPDATE_BUTTON, xtc_href_link('easymarketing.php', xtc_get_all_get_params(array('content')) . 'content=update_overview')); ?>
           <?php echo xtc_button_link(MODULE_EM_UPDATE_DATA_BUTTON, xtc_href_link('easymarketing.php', xtc_get_all_get_params(array('content')) . 'content=update_easymarketing_data')); ?>
           
		<?php
		} elseif($_GET['content'] == 'mappings') {
		?>   
			<style>
            .sortableBox {
            border: 1px solid #626262;
            width: 390px;
            min-height: 60px;
            list-style-type: none;
            margin: 0;
            padding: 5px 0 0 0;
            float: left;
            margin-right: 10px;
            }
            .sortableBox li {
            margin: 0 5px 5px 5px;
			border: 1px solid #7F7C7C;
            padding: 5px;
            width: 370px;
            cursor:move;
            }
            </style>
            <script>
			<?php 
			foreach($coo_easymarketing_manager->getMappingFields() as $mappingField)
			{
			?>
            $(function() {
            $( "#sortable_<?php echo $mappingField['mapping_field']; ?>, #sortable_active_<?php echo $mappingField['mapping_field']; ?>" ).sortable({
            connectWith: ".connectedSortable_<?php echo $mappingField['mapping_field']; ?>",
            cursor: 'move',
            update: function(event, ui) {
                  var FieldValues = $("#sortable_active_<?php echo $mappingField['mapping_field']; ?>").sortable('toArray').toString();
                  $('#<?php echo $mappingField['mapping_field']; ?>').val(FieldValues);
                }
            }).disableSelection();
            });
			<?php } ?>
            </script>
            
           <?php echo xtc_draw_form('modules', 'easymarketing.php', 'content=save_mappings','post'); ?>
           
           <p><?php echo MODULE_EM_MAPPINGS_INFO_TEXT; ?></p>
           
           <?php echo xtc_button(MODULE_EM_MAPPINGS_SAVE_BUTTON); ?><hr /><br />

            <table width="800" border="0" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; padding: 0px 10px 11px 10px; text-align: justify">
              <tbody>
              
              <?php
			  foreach($coo_easymarketing_manager->getMappingFields() as $mappingField)
			  {
				  $mappingFieldValues = $coo_easymarketing_manager->getMappingFieldValuesByFieldName($mappingField['mapping_field']);
			  ?>
                <tr>
                  <td width="100" valign="top"><?php echo $mappingField['mapping_field']; ?></td>
                  <td width="400" valign="top">
                  
                  <input type="hidden" name="mappingFields[<?php echo $mappingField['mapping_field']; ?>]" id="<?php echo $mappingField['mapping_field']; ?>" value="<?php echo implode(',', $mappingFieldValues); ?>" />
                  
                  <ul id="sortable_<?php echo $mappingField['mapping_field']; ?>" class="connectedSortable_<?php echo $mappingField['mapping_field']; ?> sortableBox">
                  
                  	<?php 
				  	foreach($coo_easymarketing_manager->getMappingEntries($mappingField['mapping_field']) as $mappingEntryKey => $mappingEntryValue)
			  		{
						if(!in_array($mappingEntryKey, $mappingFieldValues))
						{
							echo '<li id="'.$mappingEntryKey.'">'.$mappingEntryValue.'</li>';
						}
					}
					?>
              
                  </ul>
                     
                  </td>
                  <td width="400" valign="top">
                  
                  <ul id="sortable_active_<?php echo $mappingField['mapping_field']; ?>" class="connectedSortable_<?php echo $mappingField['mapping_field']; ?> sortableBox">
                  
                  	<?php 
					$mappingEntries = $coo_easymarketing_manager->getMappingEntries($mappingField['mapping_field']);
					
				  	foreach($mappingFieldValues as $mappingFieldValue)
			  		{	
						if(isset($mappingEntries[$mappingFieldValue]))
						{
							echo '<li id="'.$mappingFieldValue.'">'.$mappingEntries[$mappingFieldValue].'</li>';
						}
					}
				  	?>

                  </ul>
                  
                  </td>
                </tr>
                
                <?php
				if(!$mappingField['disable_default_value']) {
				?>
                <tr>
                  <td width="100" valign="top">&nbsp;</td>
                  <td colspan="2"><?php echo MODULE_EM_MAPPINGS_FIELD_DEFAULT_VALUE_TEXT; ?>: <input type="text" name="mappingDefaultFields[<?php echo $mappingField['mapping_field']; ?>]" value="<?php echo $mappingField['mapping_field_default_value']; ?>" size="100" maxlength="255" /> </td>
                </tr>
                <?php } ?>
                
                <?php 
				if(defined('MODULE_EM_MAPPINGS_FIELD_' . strtoupper($mappingField['mapping_field']) . '_DESC')) {
				?>
                <tr>
                  <td width="100" valign="top">&nbsp;</td>
                  <td colspan="2"><?php echo  constant('MODULE_EM_MAPPINGS_FIELD_' . strtoupper($mappingField['mapping_field']) . '_DESC'); ?>
               	</td>
                <?php } ?>
                  
                <tr>
                	<td colspan="3"><hr /></td>
              	</tr>
                  
               <?php } ?>
              </tbody>
            </table>
            
            <?php echo xtc_button(MODULE_EM_MAPPINGS_SAVE_BUTTON); ?>
            
            </form>
 
        <?php	
		} elseif($_GET['content'] == 'check_uninstall') {
			echo MODULE_EM_UNINSTALL_TEXT . '<br /><br />';
			echo xtc_button_link(MODULE_EM_YES_BUTTON, xtc_href_link('easymarketing.php', xtc_get_all_get_params(array('content')) . 'content=uninstall')) . ' ' . xtc_button_link(MODULE_EM_NO_BUTTON, xtc_href_link('easymarketing.php', xtc_get_all_get_params(array('content')) . 'content=stop_uninstall'));
		} elseif($_GET['content'] == 'check_google_site_verification') {
			echo MODULE_EM_PEFORM_GOOGLE_SITE_VERIFICATION_TEXT . '<br /><br />';
			echo xtc_button_link(MODULE_EM_YES_BUTTON, xtc_href_link('easymarketing.php', xtc_get_all_get_params(array('content')) . 'content=perform_google_site_verification')) . ' ' . xtc_button_link(MODULE_EM_NO_BUTTON, xtc_href_link('easymarketing.php', xtc_get_all_get_params(array('content')) . 'content=overview'));
        } else {
          	echo '<div style="border:1px solid #ccc; padding:15px;">';
          
		  	if ($coo_easymarketing_manager->check() == false) 
			{
            	echo MODULE_EM_INSTALL_TEXT;
            	echo xtc_button_link(MODULE_EM_INSTALL_BUTTON, xtc_href_link('easymarketing.php', xtc_get_all_get_params(array('content')) . 'content=install'));
          	} else {
            	echo $coo_easymarketing_manager->displaySettings();
          	}
          
		  	echo '</div>';
        }
        ?>     
      </span>
      
      <br />
      
      <small><i>Modul Version v2.0.1</i></small>
    </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>