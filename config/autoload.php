<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Fh-counter
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Spielerregister'       => 'system/modules/spielerregister/include/Spielerregister.php',
	'PlayerDetail'          => 'system/modules/spielerregister/classes/PlayerDetail.php',
	'YeardayList'           => 'system/modules/spielerregister/classes/YeardayList.php',
	'Yearday'               => 'system/modules/spielerregister/classes/Yearday.php',
	'ExportSpielerregister' => 'system/modules/spielerregister/classes/Export.php',
	'HonorList'             => 'system/modules/spielerregister/classes/HonorList.php',
	'DeathList'             => 'system/modules/spielerregister/classes/DeathList.php'
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'be_spielerregister'            => 'system/modules/spielerregister/templates',
	'spielerregister_yeardays'      => 'system/modules/spielerregister/templates',
	'spielerregister_yearday'       => 'system/modules/spielerregister/templates',
	'spielerregister_playerdetail'  => 'system/modules/spielerregister/templates',
	'spielerregister_suche'         => 'system/modules/spielerregister/templates',
	'spielerregister_honorlist'     => 'system/modules/spielerregister/templates',
	'spielerregister_deathlist'     => 'system/modules/spielerregister/templates',
	'spielerregister_infobox'       => 'system/modules/spielerregister/templates'
));
