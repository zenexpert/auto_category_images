<?php
/**
 * @copyright Copyright 2003-2026 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: ZenExpert 2026-04-19 $
 */

require('includes/application_top.php');
require(DIR_WS_CLASSES . 'AutoCategoryImages.php');

$action = (isset($_GET['action']) ? zen_db_prepare_input($_GET['action']) : '');
$messages = [];

if ($action == 'process') {
    $autoImage = new AutoCategoryImages();
    $messages = $autoImage->process();
}
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
    <?php require DIR_WS_INCLUDES . 'admin_html_head.php'; ?>
</head>
<body>
<!-- header //-->
<?php require DIR_WS_INCLUDES . 'header.php'; ?>
<!-- header_eof //-->

    <div class="container-fluid">
        <h1><?php echo HEADING_TITLE; ?></h1>

        <?php if (!empty($messages)) { ?>
            <div class="aci-messages">
                <ul>
                    <?php foreach ($messages as $msg) { ?>
                        <li><?php echo $msg; ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <div class="aci-content">
            <p><?php echo TEXT_INFO_DESCRIPTION; ?></p>
            <br>
            <a href="<?php echo zen_href_link(FILENAME_AUTO_CATEGORY_IMAGES, 'action=process', 'NONSSL'); ?>" class="process-btn">
                <?php echo BUTTON_PROCESS; ?>
            </a>
        </div>
    </div>

    <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
    </body>
    </html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
