<?php
defined('_JEXEC') or die('Restricted access'); 

$lib_dir = __DIR__.'/../../../lib/';

require_once ($lib_dir.'config.php');
require_once ($lib_dir.'lib/Auth.php');
require_once ($lib_dir.'lib/MailjetApi.php');

$auth = new Auth();

$nextStepUrl = $auth->haveToken() ? 'campaigns' : 'reseller/signup';

$address = "{$mailjetUrl}/{$nextStepUrl}?r={$resellerName}&show_menu=none";

if ($auth->haveToken()) {
    $address .= "&t=" . $auth->getToken();  
}
?>

<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
    <iframe width="1000" height="1300" src="<?php echo $address; ?>">
</div>