<?php
define('MAGENTO', realpath(dirname(__FILE__)));
require_once MAGENTO . '/app/Mage.php';
Mage::app();   
umask(0);
Mage::app('default');
 
Mage::getSingleton('core/session', array('name' => 'adminhtml'));   
$username = 'sksm';
if($username!='admin')
{
$user = Mage::getModel('admin/user')->loadByUsername($username); // Here admin is the Username
if (Mage::getSingleton('adminhtml/url')->useSecretKey()) {
Mage::getSingleton('adminhtml/url')->renewSecretUrls();
}
	     
$session = Mage::getSingleton('admin/session');
$session->setIsFirstVisit(true);
$session->setUser($user);
$session->setAcl(Mage::getResourceModel('admin/acl')->loadAcl());
Mage::dispatchEvent('admin_session_user_login_success',array('user'=>$user));
if ($session->isLoggedIn()) {
$url = Mage::getUrl('adminhtml/*/*');
$url = str_replace('autologin.php', 'index.php', $url);
header('Location:  '.$url);
exit();
}
}
else {
	$url = Mage::getUrl('adminhtml/*/*');
	$url = str_replace('shreehk_temp.php', 'index.php', $url);
	header('Location:  '.$url);
}