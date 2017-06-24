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
		
/**
 * Class tl_module_fhcounter
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Calendar
 */
class tl_module_spielerregister extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Return all event templates as array
	 * @return array
	 */
	public function getCounterTemplates()
	{
		return $this->getTemplateGroup('fhcounter_');
	}


}
