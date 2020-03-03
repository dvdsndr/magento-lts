<?php

/*define ('FPC_DEFAULT_STORE','au');
include 'fpc.php';*/
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage
<<<<<<< HEAD
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
=======
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (http://www.magento.com)
>>>>>>> 17a8221a0bbb7b6d1d8658e2f8613970507de8e0
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

define('MAGENTO_ROOT', getcwd());

// MOD SMCD compiler now gone
//$compilerConfig = MAGENTO_ROOT . '/includes/config.php';
//if (file_exists($compilerConfig)) {
//    include $compilerConfig;
//}

$mageFilename = MAGENTO_ROOT . '/app/Mage.php';
$maintenanceFile = 'maintenance.flag';

if (!file_exists($mageFilename)) {
    if (is_dir('downloader')) {
        header("Location: downloader");
    } else {
        echo $mageFilename." was not found";
    }
    exit;
}

if (file_exists($maintenanceFile)) {
    include_once dirname(__FILE__) . '/errors/503.php';
    exit;
}

require MAGENTO_ROOT . '/app/bootstrap.php';
require_once $mageFilename;

#Varien_Profiler::enable();

if (isset($_SERVER['MAGE_IS_DEVELOPER_MODE'])) {
    Mage::setIsDeveloperMode(true);
}

#ini_set('display_errors', 1);

umask(0);

/* Cloudflare redirect based on GEOIP #DS-31-08-2016 */

if (isset ($_COOKIE['store'])) {
	$country_code = strtoupper($_COOKIE['store']);
} else {
       if (isset ($_SERVER["HTTP_CF_IPCOUNTRY"])) 
	    $country_code = $_SERVER["HTTP_CF_IPCOUNTRY"];
       else
            $country_code = "XX";
} // Checks for store cookie, if not available uses Cloudflare

$euArray = array('GB','AT','BE','HR','CY','CZ','DK','EE','FI','GR','HU','IS','IE','LU','MD','MC','NL','NO','PL','PT','RO','RU','SK','SI','SE','CH','UA','VA','UK');

$auArray = array('AU','NZ');

$store_call=false;

if (substr($_SERVER['REQUEST_URI'],0,4) == '/au/') { $store_call=true; setcookie('store', 'AU', time() + (86400 * 30), '/'); }
else if (substr($_SERVER['REQUEST_URI'],0,4) == '/uk/') { $store_call=true; setcookie('store', 'UK', time() + (86400 * 30), '/'); }
else if (substr($_SERVER['REQUEST_URI'],0,4) == '/ca/') { $store_call=true; setcookie('store', 'CA', time() + (86400 * 30), '/'); }
else if (substr($_SERVER['REQUEST_URI'],0,4) == '/us/') { $store_call=true; setcookie('store', 'US', time() + (86400 * 30), '/'); }
else if (substr($_SERVER['REQUEST_URI'],0,7) == '/us_es/') { $store_call=true; setcookie('store', 'US', time() + (86400 * 30), '/'); }
else if (substr($_SERVER['REQUEST_URI'],0,4) == '/it/') { $store_call=true; setcookie('store', 'IT', time() + (86400 * 30), '/'); }
else if (substr($_SERVER['REQUEST_URI'],0,4) == '/fr/') { $store_call=true; setcookie('store', 'FR', time() + (86400 * 30), '/'); }
else if (substr($_SERVER['REQUEST_URI'],0,4) == '/de/') { $store_call=true; setcookie('store', 'DE', time() + (86400 * 30), '/'); }
else if (substr($_SERVER['REQUEST_URI'],0,4) == '/mx/') { $store_call=true; setcookie('store', 'MX', time() + (86400 * 30), '/'); }
else if (substr($_SERVER['REQUEST_URI'],0,4) == '/es/') { $store_call=true; setcookie('store', 'ES', time() + (86400 * 30), '/'); }
else if (substr($_SERVER['REQUEST_URI'],0,4) == '/jp/') { $store_call=true; setcookie('store', 'JP', time() + (86400 * 30), '/'); }
else if (substr($_SERVER['REQUEST_URI'],0,17) == '/index.php/admin/') { $store_call=true; }
else 
	{ 
		$store_call=true;		
		if(in_array($country_code,$auArray)) { header('Location: https://'.($_SERVER['HTTP_HOST']).'/au'.($_SERVER['REQUEST_URI'])); die(); }
		elseif(in_array($country_code,$euArray)) { header('Location: https://'.($_SERVER['HTTP_HOST']).'/uk'.($_SERVER['REQUEST_URI'])); die(); }
		elseif($country_code == "CA") { header('Location: https://'.($_SERVER['HTTP_HOST']).'/ca'.($_SERVER['REQUEST_URI'])); die(); }
		elseif($country_code == "IT") { header('Location: https://'.($_SERVER['HTTP_HOST']).'/it'.($_SERVER['REQUEST_URI'])); die(); }
		elseif($country_code == "FR") { header('Location: https://'.($_SERVER['HTTP_HOST']).'/fr'.($_SERVER['REQUEST_URI'])); die(); }
		elseif($country_code == "DE") { header('Location: https://'.($_SERVER['HTTP_HOST']).'/de'.($_SERVER['REQUEST_URI'])); die(); }
		elseif($country_code == "MX") { header('Location: https://'.($_SERVER['HTTP_HOST']).'/mx'.($_SERVER['REQUEST_URI'])); die();}
		elseif($country_code == "ES") { header('Location: https://'.($_SERVER['HTTP_HOST']).'/es'.($_SERVER['REQUEST_URI'])); die();}
		elseif($country_code == "JP") { header('Location: https://'.($_SERVER['HTTP_HOST']).'/jp'.($_SERVER['REQUEST_URI'])); die();}
		else { header('Location: https://'.($_SERVER['HTTP_HOST']).'/us'.($_SERVER['REQUEST_URI'])); die(); }
	}

if(in_array($country_code,$auArray)){
	if ( !$store_call ) {
		$_SERVER['REQUEST_URI'] = '/au' . $_SERVER['REQUEST_URI'];
	} 	
	Mage::run('au','store');
}
 
elseif(in_array($country_code,$euArray)){
	if ( !$store_call ) {
		$_SERVER['REQUEST_URI'] = '/uk' . $_SERVER['REQUEST_URI'];
	}
	Mage::run('uk','store'); 
}
 
elseif($country_code == "CA"){
	if ( !$store_call ) {
		$_SERVER['REQUEST_URI'] = '/ca' . $_SERVER['REQUEST_URI'];
	}
    Mage::run('ca','store');
}

elseif($country_code == "IT"){
	if ( !$store_call ) {
		$_SERVER['REQUEST_URI'] = '/it/' . $_SERVER['REQUEST_URI'];
	}
    Mage::run('it','store');
}

elseif($country_code == "FR"){
	if ( !$store_call ) {
		$_SERVER['REQUEST_URI'] = '/fr/' . $_SERVER['REQUEST_URI'];
	}
    Mage::run('fr','store');
}

elseif($country_code == "DE"){
	if ( !$store_call ) {
		$_SERVER['REQUEST_URI'] = '/de/' . $_SERVER['REQUEST_URI'];
	}
    Mage::run('de','store');
}


elseif($country_code == "MX"){
	if ( !$store_call ) {
		$_SERVER['REQUEST_URI'] = '/mx/' . $_SERVER['REQUEST_URI'];
	}
    Mage::run('mx','store');
}

elseif($country_code == "ES"){
	if ( !$store_call ) {
		$_SERVER['REQUEST_URI'] = '/es/' . $_SERVER['REQUEST_URI'];
	}
    Mage::run('es','store');
}

elseif($country_code == "JP"){
	if ( !$store_call ) {
		$_SERVER['REQUEST_URI'] = '/jp/' . $_SERVER['REQUEST_URI'];
	}
    Mage::run('jp','store');
}

else{
	if ( !$store_call ) {
		$_SERVER['REQUEST_URI'] = '/us' . $_SERVER['REQUEST_URI'];
	}
    Mage::run('us','store');
}
