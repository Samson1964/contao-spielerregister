<?php
/**
 * Avatar for Contao Open Source CMS
 *
 * Copyright (C) 2013 Kirsten Roschanski
 * Copyright (C) 2013 Tristan Lins <http://bit3.de>
 *
 * @package    Avatar
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Add palette to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['spielerregister_yeardaylist'] = '{title_legend},name,type;{options_legend},spielerregister_jumpTo,spielerregister_level,spielerregister_image,spielerregister_lastday;{expert_legend:hide},cssID,align,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['spielerregister_yearday'] = '{title_legend},name,type;{options_legend},spielerregister_jumpTo,spielerregister_level,spielerregister_image;{expert_legend:hide},cssID,align,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['spielerregister_playerdetail'] = '{title_legend},name,type;{options_legend},spielerregister_jumpTo;{expert_legend:hide},cssID,align,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['spielerregister_honorlist'] = '{title_legend},name,headline,type;{options_legend},spielerregister_honorListtype;{expert_legend:hide},cssID,align,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['spielerregister_deathlist'] = '{title_legend},name,headline,type;{options_legend},spielerregister_deathlist_months;{expert_legend:hide},cssID,align,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['spielerregister_titlelist'] = '{title_legend},name,headline,type;{options_legend},spielerregister_titlelist;{expert_legend:hide},cssID,align,space';

$GLOBALS['TL_DCA']['tl_module']['fields']['spielerregister_level'] = array
(
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['spielerregister_level'],
	'exclude'          => true,
	'default'          => 5,
	'inputType'        => 'select',
	'options'          => array(10, 9, 8, 7, 6, 5, 4, 3, 2, 1),
	'reference'        => &$GLOBALS['TL_LANG']['tl_spielerregister'],
	'eval'             => array('tl_class'=>'w50'),
	'sql'              => "char(2) NOT NULL default '5'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['spielerregister_image'] = array
(
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['spielerregister_image'],
	'exclude'          => true,
	'default'          => 6,
	'inputType'        => 'select',
	'options'          => array(10, 9, 8, 7, 6, 5, 4, 3, 2, 1),
	'reference'        => &$GLOBALS['TL_LANG']['tl_spielerregister'],
	'eval'             => array('tl_class'=>'w50 clr'),
	'sql'              => "char(2) NOT NULL default '6'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['spielerregister_lastday'] = array
(
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['spielerregister_lastday'],
	'default'          => 30,
	'exclude'          => true,
	'inputType'        => 'text',
	'eval'             => array('tl_class'=>'w50', 'rgxp'=>'digit', 'maxlength'=>3),
	'sql'              => "varchar(3) NOT NULL default '30'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['spielerregister_jumpTo'] = array
(
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['spielerregister_jumpTo'],
	'exclude'          => true,
	'inputType'        => 'pageTree',
	'foreignKey'       => 'tl_page.title',
	'eval'             => array('mandatory'=>true, 'fieldType'=>'radio', 'tl_class'=>'long'),
	'sql'              => "int(10) unsigned NOT NULL default '0'",
	'relation'         => array('type'=>'belongsTo', 'load'=>'lazy')
); 
		
$GLOBALS['TL_DCA']['tl_module']['fields']['spielerregister_honorListtype'] = array
(
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['spielerregister_honorListtype'],
	'exclude'          => true,
	'default'          => 'pr',
	'inputType'        => 'select',
	'options'          => array
	(
		'pr'           => 'Liste der Ehrenpräsidenten',
		'me'           => 'Liste der Ehrenmitglieder',
		'go'           => 'Liste der Träger der Goldenen Ehrennadel',
		'si'           => 'Liste der Träger der Silbernen Ehrennadel',
		'gb'           => 'Liste der Träger der Goldenen Ehrenplakette',
		'sb'           => 'Liste der Träger der Silbernen Ehrenplakette',
		'le'           => 'Liste der Empfänger des Ehrenbriefes',
		'pl'           => 'Liste der Empfänger des Ehrentellers',
		'bm'           => 'Liste der Empfänger der Bundesmedaille'
	),
	'eval'             => array('tl_class'=>'w50'),
	'sql'              => "char(2) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['spielerregister_deathlist_months'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['spielerregister_deathlist_months'],
	'exclude'                 => true,
	'search'                  => true,
	'sorting'                 => true,
	'flag'                    => 11,
	'inputType'               => 'text',
	'eval'                    => array
	(
		'maxlength'           => 2,
		'tl_class'            => 'w50',
		'rgxp'                => 'alnum'
	),
	'sql'                     => "int(2) unsigned NOT NULL default '12'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['spielerregister_titlelist'] = array
(
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['spielerregister_titlelist'],
	'exclude'          => true,
	'default'          => 'gm',
	'inputType'        => 'select',
	'options'          => array
	(
		'gm'           => 'Liste der Großmeister',
		'im'           => 'Liste der Internationalen Meister',
		'wg'           => 'Liste der Großmeisterinnen',
		'wi'           => 'Liste der Internationalen Meisterinnen'
	),
	'eval'             => array('tl_class'=>'w50'),
	'sql'              => "char(2) NOT NULL default ''"
);

