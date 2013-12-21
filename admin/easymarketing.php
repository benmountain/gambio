<?php
/* -----------------------------------------------------------------------------------------
   $Id: easymarketing.php 6027 2013-11-07 11:48:21Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

require('includes/application_top.php');

$coo_account_manager = MainFactory::create_object('Easymarketing');

if (isset($_GET['content']) && $_GET['content'] != '') {
  switch ($_GET['content']) {
    case 'install':
      if ($coo_account_manager->check() == false) {
        $coo_account_manager->install();
      }
      xtc_redirect(xtc_href_link('easymarketing.php', 'content=settings'));
      break;
    case 'uninstall':
      if ($coo_account_manager->check() == true) {
        $coo_account_manager->remove();
      }
      xtc_redirect(xtc_href_link('easymarketing.php'));
      break;
    case 'save':
      if (is_array($_POST) && count($_POST) > 0) {
        $coo_account_manager->process();
      }
      xtc_redirect(xtc_href_link('easymarketing.php', 'content=settings'));
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
          <td class="dataTableHeadingContentText" style="width:1%; padding-right:20px; white-space: nowrap"><a href="easymarketing.php?content=settings"><?php echo EASYMARKETING_SETTINGS_HEADING; ?></a></td>
         </tr>
        </table>
        
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
              <?php 
              // include needed function
              require_once (DIR_FS_INC.'get_external_content.inc.php');
              $iframe = get_external_content('http://api.easymarketing.de/analysis?width=1050&height=1700&partner_id=modified&website_url='.(HTTP_SERVER.DIR_WS_CATALOG), 3, false);
              echo $iframe; 
              ?>
            </td>
          </tr>
        </table>
        <?php
        } else {
          echo '<div style="border:1px solid #ccc; padding:15px;">';
          if ($coo_account_manager->check() == false) {
            echo EASYMARKETING_INSTALL;
            echo xtc_button_link(BUTTON_INSTALL, xtc_href_link('easymarketing.php', xtc_get_all_get_params(array('content')) . 'content=install'));
          } else {
            echo $coo_account_manager->display();
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