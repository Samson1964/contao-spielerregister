<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2018 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Samson',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Samson\Playerbase\DeathList'       => 'system/modules/spielerregister/classes/DeathList.php',
	'Samson\Playerbase\Export'          => 'system/modules/spielerregister/classes/Export.php',
	'Samson\Playerbase\HonorList'       => 'system/modules/spielerregister/classes/HonorList.php',
	'Samson\Playerbase\PlayerDetail'    => 'system/modules/spielerregister/classes/PlayerDetail.php',
	'Samson\Playerbase\TitleList'       => 'system/modules/spielerregister/classes/TitleList.php',
	'Samson\Playerbase\Yearday'         => 'system/modules/spielerregister/classes/Yearday.php',
	'Samson\Playerbase\YeardayList'     => 'system/modules/spielerregister/classes/YeardayList.php',

	// Include
	'Samson\Playerbase\Spielerregister' => 'system/modules/spielerregister/include/Spielerregister.php',
	'Samson\Playerbase\Helper'          => 'system/modules/spielerregister/include/Helper.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'be_spielerregister'           => 'system/modules/spielerregister/templates',
	'spielerregister_deathlist'    => 'system/modules/spielerregister/templates',
	'spielerregister_honorlist'    => 'system/modules/spielerregister/templates',
	'spielerregister_infobox'      => 'system/modules/spielerregister/templates',
	'spielerregister_playerdetail' => 'system/modules/spielerregister/templates',
	'spielerregister_suche'        => 'system/modules/spielerregister/templates',
	'spielerregister_titlelist'    => 'system/modules/spielerregister/templates',
	'spielerregister_yearday'      => 'system/modules/spielerregister/templates',
	'spielerregister_yeardays'     => 'system/modules/spielerregister/templates',
));
