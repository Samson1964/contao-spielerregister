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
	'spielerregister_playerdetail' => 'Samson\Playerbase\PlayerDetail',
	'spielerregister_yeardaylist'  => 'Samson\Playerbase\YeardayList',
	'spielerregister_yearday'      => 'Samson\Playerbase\Yearday',
	'spielerregister_honorlist'    => 'Samson\Playerbase\HonorList',
	'spielerregister_deathlist'    => 'Samson\Playerbase\DeathList',
	'spielerregister_titlelist'    => 'Samson\Playerbase\TitleList',
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

/**
 * Inserttag für Registerersetzung in den Hooks anmelden
 */

$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('Samson\Playerbase\Spielerregister','ReplacePlayer');


$GLOBALS['BE_MOD']['dsb']['spielerregister'] = array
(
	'tables'         => array('tl_spielerregister', 'tl_spielerregister_images'),
	'icon'           => 'system/modules/spielerregister/assets/images/icon.png',
	'export'         => array('ExportSpielerregister', 'exportSpieler')
);

// Konfiguration für ProSearch
$GLOBALS['PS_SEARCHABLE_MODULES']['spielerregister'] = array(
	'icon'           => 'system/modules/spielerregister/assets/images/icon.png',
	'title'          => array('surname1','firstname1', 'birthplace', 'deathplace', 'shortinfo'),
	'searchIn'       => array('surname1','firstname1', 'birthplace', 'deathplace', 'shortinfo'),
	'tables'         => array('tl_spielerregister'),
	'shortcut'       => 'sr'
);
