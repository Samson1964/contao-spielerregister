<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   bdf
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2014
 */

$GLOBALS['FE_MOD']['spielerregister'] = array
(
	'spielerregister_playerdetail' => 'PlayerDetail',
	'spielerregister_yeardaylist'  => 'YeardayList',
	'spielerregister_yearday'      => 'Yearday',
);  

/**
 * Backend-Bereich DSB anlegen, wenn noch nicht vorhanden
 */
if(!$GLOBALS['BE_MOD']['dsb']) 
{
	$dsb = array(
		'dsb' => array()
	);
	array_insert($GLOBALS['BE_MOD'], 0, $dsb);
}

$GLOBALS['BE_MOD']['dsb']['spielerregister'] = array
(
	'tables'         => array('tl_spielerregister', 'tl_spielerregister_images'),
	'icon'           => 'system/modules/spielerregister/assets/images/icon.png',
	'export'         => array('ExportSpielerregister', 'exportSpieler')
);

?>