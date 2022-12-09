<?php
/**
 * @author Hugues
 * @since 2.22.12.05
 * @version 2.22.12.05
 */
define('V2_APERD_SITE_URL', 'https://v2.aperd.jhugues.fr/');
define('PLUGINS_MYCOMMON', V2_APERD_SITE_URL.'wp-content/plugins/mycommon');
define('PLUGINS_APERD', V2_APERD_SITE_URL.'wp-content/plugins/hj-v2-aperd/');
if (!defined('ABSPATH')) {
    die('Forbidden');
}
?>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css" integrity="sha384-QYIZto+st3yW+o8+5OHfT6S482Zsvz2WfOzpFSXMF9zqeLcFV0/wlZpMtyFcZALm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/darkly/bootstrap.min.css" integrity="sha384-nNK9n28pDUDDgIiIqZ/MiyO3F4/9vsMtReZK39klb/MtkZI3/LtjSjlmyVPS3KdN" crossorigin="anonymous">
<link rel="stylesheet" href="<?php echo PLUGINS_APERD; ?>web/rsc/admin_aperd.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo PLUGINS_APERD; ?>web/rsc/open-iconic-bootstrap.css" type="text/css" media="all" />
<?php
global $objAperd;
if (empty($objAperd)) {
    $objAperd = new AperdV2();
}
$objAdminPageBean = new AdminPageBean();
echo $objAdminPageBean->getContentPage();
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script type='text/javascript' src='<?php echo PLUGINS_APERD; ?>web/rsc/admin_aperd.js'></script>
