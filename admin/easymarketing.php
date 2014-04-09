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
   @version    07.04.2014 - 20:34
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
				xtc_redirect(xtc_href_link('easymarketing.php', 'content=execute_setup'));
			}
      	}
      	xtc_redirect(xtc_href_link('easymarketing.php', 'content=settings&error=' . $error));
      	break;
	case 'execute_setup':
		if($coo_easymarketing_manager->checkAPIToken(MODULE_EASYMARKETING_API_TOKEN))
		{
			$coo_easymarketing_manager->executeSetup();
			xtc_redirect(xtc_href_link('easymarketing.php', 'content=settings&success=true'));
		} else {
			xtc_redirect(xtc_href_link('easymarketing.php', 'content=settings&error=true'));
		}
		break;
	case 'update_overview':
		$coo_easymarketing_manager->updateOverview();
		xtc_redirect(xtc_href_link('easymarketing.php', 'content=overview'));
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
		
      <div class="pageHeading" style="background-image:url(images/gm_icons/gambio.png)"><?php echo HEADING_TITLE; ?></div>
      <br />
      
      <span class="main">
        <table style="margin-bottom:5px" border="0" cellpadding="0" cellspacing="0" width="100%">
         <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContentText" style="width:1%; padding-right:20px; white-space: nowrap"><a href="easymarketing.php"><?php echo EASYMARKETING_INFO_HEADING; ?></a></td>
          <?php if ($coo_easymarketing_manager->check()) { echo '<td class="dataTableHeadingContentText" style="width:1%; padding-right:20px; white-space: nowrap"><a href="easymarketing.php?content=update_overview">'. EASYMARKETING_OVERVIEW_HEADING.'</a></td>'; } ?>
          <td class="dataTableHeadingContentText" style="width:1%; padding-right:20px; white-space: nowrap"><a href="easymarketing.php?content=settings"><?php echo EASYMARKETING_SETTINGS_HEADING; ?></a></td>
         </tr>
        </table>
        
        <?php
			if(!empty($_GET['success']))
			{
				echo '<div style="margin:4px;"><h4>' . EASYMARKETING_SUCCESS_HEADING . '</h4>' . EASYMARKETING_SUCCESS_TEXT . '</div>';
			}
		
			if(!empty($_GET['error']))
			{
				echo '<div style="color:#C00; margin:4px;"><h4>' . EASYMARKETING_ERROR_HEADING . '</h4>' . EASYMARKETING_ERROR_TEXT . '</div>';
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
              <br />
              <br />
              <font color="#FF7A00"><strong>In drei Schritten durchstarten:</strong></font>
              <ul>
                <li style="list-style-type: circle !important;">Auf easymarketing.de die Shop-URL eingeben</li>
                <li style="list-style-type: circle !important;">Automatische und dauerhafte Optimierung</li>
                <li style="list-style-type: circle !important;">Registrieren</li>
              </ul>
              EASYMARKETING crawlt Ihren Shop, erkennt dabei alle besonders performanten Keywords, erstellt automatisch aus mehreren 100 verschiedenen Keywords &uuml;ber
              1.000 verschiedene AdWordsAnzeigen, ver&ouml;ffentlicht diese bei Google &amp; optimiert die Ergebnisse permanent. Dank des intelligenten Algorithmus ist
              EASYMARKETING vielfach effizienter, als wenn der Online-H&auml;ndler die Anzeigenverwaltung manuell vornehmen w&uuml;rde, es wird sehr viel mehr Traffic und
              somit Umsatz generiert. Sie sparen somit Zeit und auch Geld, weil EASYMARKETING f&uuml;r Sie vollautomatisch arbeitetet und Ihr Budget mit Ber&uuml;cksichtigung
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
                <td colspan="2" class="dataTableHeadingContentText"><?php echo MODULE_EASYMARKETING_OVERVIEW_SETUP_TITLE; ?></td>
              </tr>
              <tr>
                <td width="305"><?php echo MODULE_EASYMARKETING_OVERVIEW_API_STATUS_TITLE; ?></td>
                <td width="285"><?php if(MODULE_EASYMARKETING_API_STATUS == 1) { echo '<span style="color:#3C6">&#10003;</span>'; } else { echo '<span style="color:#C00">&#10006;</span>'; } ?></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EASYMARKETING_OVERVIEW_CONFIGURE_ENDPOINTS_STATUS_TITLE; ?></td>
                <td><?php if(MODULE_EASYMARKETING_CONFIGURE_ENDPOINTS_STATUS == 1) { echo '<span style="color:#3C6">&#10003;</span>'; } else { echo '<span style="color:#C00">&#10006;</span>'; } ?></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EASYMARKETING_OVERVIEW_GOOGLE_CONVERSION_TRACKER_STATUS_TITLE; ?></td>
                <td><?php if(MODULE_EASYMARKETING_GOOGLE_CONVERSION_TRACKER_STATUS == 1) { echo '<span style="color:#3C6">&#10003;</span>'; } else { echo '<span style="color:#C00">&#10006;</span>'; } ?></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EASYMARKETING_OVERVIEW_LEAD_TRACKER_STATUS_TITLE; ?></td>
                <td><?php if(MODULE_EASYMARKETING_LEAD_TRACKER_STATUS == 1) { echo '<span style="color:#3C6">&#10003;</span>'; } else { echo '<span style="color:#C00">&#10006;</span>'; } ?></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EASYMARKETING_OVERVIEW_GOOGLE_SITE_VERIFICATION_STATUS_TITLE; ?></td>
                <td><?php if(MODULE_EASYMARKETING_GOOGLE_SITE_VERIFICATION_STATUS == 1) { echo '<span style="color:#3C6">&#10003;</span>'; } else { echo '<span style="color:#C00">&#10006;</span>'; } ?></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EASYMARKETING_OVERVIEW_RETARGETING_ADSCALE_STATUS_TITLE; ?></td>
                <td><?php 
							if(MODULE_EASYMARKETING_RETARGETING_ADSCALE_STATUS == 'True') 
							{ 
								if(MODULE_EASYMARKETING_RETARGETING_ADSCALE_ID > 0) 
								{
									echo '<span style="color:#3C6">&#10003;</span>'; 
								} else {
									echo MODULE_EASYMARKETING_RETARGETING_ADSCALE_NO_ID_TITLE;
								}		
							} else { 
								echo '<span style="color:#C00">&#10006;</span>'; 
							}
					 ?>
             	</td>
              </tr>
              <tr class="dataTableHeadingRow">
                <td colspan="2" class="dataTableHeadingContentText"><?php echo MODULE_EASYMARKETING_OVERVIEW_LAST_CRAWL_TITLE; ?></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EASYMARKETING_OVERVIEW_LAST_CRAWL_DATE_TITLE; ?></td>
                <td><?php if(MODULE_EASYMARKETING_LAST_CRAWL_DATE > 0) { echo date('d.m.Y - H:i', MODULE_EASYMARKETING_LAST_CRAWL_DATE); } else { echo MODULE_EASYMARKETING_OVERVIEW_LAST_CRAWL_NO_DATE_TITLE; } ?></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EASYMARKETING_OVERVIEW_LAST_CRAWL_CATEGORIES_COUNT_TITLE; ?></td>
                <td><?php if(MODULE_EASYMARKETING_LAST_CRAWL_CATEGORIES_COUNT > 0) { echo MODULE_EASYMARKETING_LAST_CRAWL_CATEGORIES_COUNT; } else { echo '0'; } ?></td>
              </tr>
              <tr>
                <td><?php echo MODULE_EASYMARKETING_OVERVIEW_LAST_CRAWL_PRODUCTS_COUNT_TITLE; ?></td>
                <td><?php if(MODULE_EASYMARKETING_LAST_CRAWL_PRODUCTS_COUNT > 0) { echo MODULE_EASYMARKETING_LAST_CRAWL_PRODUCTS_COUNT; } else { echo '0'; } ?></td>
              </tr>
           </table>
           
           <?php echo xtc_button_link(BUTTON_UPDATE, xtc_href_link('easymarketing.php', xtc_get_all_get_params(array('content')) . 'content=update_overview')); ?>
           
		<?php	
		} elseif($_GET['content'] == 'check_uninstall') {
			echo EASYMARKETING_UNINSTALL_TEXT . '<br /><br />';
			echo xtc_button_link(EASYMARKETING_UNINSTALL_YES_BUTTON, xtc_href_link('easymarketing.php', xtc_get_all_get_params(array('content')) . 'content=uninstall')) . ' ' . xtc_button_link(EASYMARKETING_UNINSTALL_NO_BUTTON, xtc_href_link('easymarketing.php', xtc_get_all_get_params(array('content')) . 'content=stop_uninstall'));
        } else {
          echo '<div style="border:1px solid #ccc; padding:15px;">';
          if ($coo_easymarketing_manager->check() == false) {
            echo EASYMARKETING_INSTALL_TEXT;
            echo xtc_button_link(EASYMARKETING_INSTALL_BUTTON, xtc_href_link('easymarketing.php', xtc_get_all_get_params(array('content')) . 'content=install'));
          } else {
            echo $coo_easymarketing_manager->displaySettings();
          }
          echo '</div>';
        }
        ?>     
      </span>
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