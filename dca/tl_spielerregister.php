<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package News
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Table tl_spielerregister
 */
$GLOBALS['TL_DCA']['tl_spielerregister'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_spielerregister_images'),
		'switchToEdit'                => true, 
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_spielerregister', 'applyAdvancedFilter'),
		), 
		'onsubmit_callback' => array
		(
			array('tl_spielerregister', 'generateAlias')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id'                 => 'primary',
				'alias'              => 'index',
				'birthday'           => 'index',
				'deathday'           => 'index',
				'honorpresident'     => 'index',
				'honormember'        => 'index',
				'honorgoldpin'       => 'index',
				'honorsilverpin'     => 'index',
				'honorgoldbadge'     => 'index',
				'honorsilverbadge'   => 'index',
				'honormedal'         => 'index',
				'honorletter'        => 'index',
				'honorplate'         => 'index',
				'honormedal'         => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('alias'),
			'flag'                    => 1,
			'panelLayout'             => 'myfilter;filter,sort;search,limit',
			'panel_callback'          => array('myfilter' => array('tl_spielerregister', 'generateAdvancedFilter')),  
		),
		'label' => array
		(
			'fields'                  => array('surname1', 'firstname1'),
			'format'                  => '%s, %s',
			'label_callback'          => array('tl_spielerregister', 'listPersons')
		),
		'global_operations' => array
		(
			'export' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_spielerregister']['export'],
				'href'                => 'key=export',
				'icon'                => 'system/modules/spielerregister/assets/images/image.png',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_spielerregister']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
			),
			'editImage' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_spielerregister']['editImage'],
				'href'                => 'table=tl_spielerregister_images',
				'icon'                => 'system/modules/spielerregister/assets/images/image.png'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_spielerregister']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
				//'button_callback'     => array('tl_spielerregister', 'copyArchive')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_spielerregister']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
				//'button_callback'     => array('tl_spielerregister', 'deleteArchive')
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_spielerregister']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_spielerregister', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_spielerregister']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('death'),
		'default'                     => 'infobox;{namen_legend},surname1,firstname1,title,alias;{namen2_legend:hide},surname2,firstname2,surname3,firstname3,surname4,firstname4;{live_legend},birthday,birthplace,birthday_alt,death;{photos_legend:hide},multiSRC;{info_legend:hide},shortinfo,longinfo;{link_legend:hide},wikipedia,fide_id,dewis_id,chessgames_id,chess365_id,chess_id,homepage;{star_legend},importance;{dsb_legend},honorpresident,honormember,honorgoldpin,honorsilverpin,honorgoldbadge,honorsilverbadge,honorletter,honorplate,honormedal;{images_legend:hide};{intern_legend:hide},intern;{active_legend},nohighlighting,active'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'death'                       => 'deathday,deathplace,deathday_alt'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'createtime' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'infobox' => array
		(
			'exclude'              => true,
			'input_field_callback' => array('tl_spielerregister', 'getInfobox')
		), 
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['title'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>16, 'tl_class'=>'w50'),
			'sql'                     => "varchar(16) NOT NULL default ''"
		),
		'surname1' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['surname1'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>32, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'surname2' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['surname2'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>32, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'surname3' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['surname2'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>32, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'surname4' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['surname2'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>32, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'firstname1' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['firstname1'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>32, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'firstname2' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['firstname2'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>32, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'firstname3' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['firstname2'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>32, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'firstname4' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['firstname2'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>32, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alias', 'unique'=>false, 'maxlength'=>128, 'tl_class'=>'w50 clr'),
			'sql'                     => "varbinary(128) NOT NULL default ''"
		), 
		'birthday' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['birthday'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 11,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'maxlength'           => 10,
				'tl_class'            => 'w50',
				'rgxp'                => 'alnum'
			),
			'load_callback'           => array
			(
				array('\Samson\Helper', 'getDate')
			),
			'save_callback' => array
			(
				array('\Samson\Helper', 'putDate')
			),
			'sql'                     => "int(8) unsigned NOT NULL default '0'"
		),
		'birthday_alt' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['birthday_alt'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'maxlength'           => 10,
				'tl_class'            => 'w50',
				'rgxp'                => 'alnum'
			),
			'load_callback'           => array
			(
				array('\Samson\Helper', 'getDate')
			),
			'save_callback' => array
			(
				array('\Samson\Helper', 'putDate')
			),
			'sql'                     => "int(8) unsigned NOT NULL default '0'"
		),
		'birthplace' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['birthplace'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'deathday' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['deathday'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 11,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'maxlength'           => 10,
				'tl_class'            => 'w50',
				'rgxp'                => 'alnum'
			),
			'load_callback'           => array
			(
				array('\Samson\Helper', 'getDate')
			),
			'save_callback' => array
			(
				array('\Samson\Helper', 'putDate')
			),
			'sql'                     => "int(8) unsigned NOT NULL default '0'"
		),
		'deathday_alt' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['deathday_alt'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'maxlength'           => 10,
				'tl_class'            => 'w50',
				'rgxp'                => 'alnum'
			),
			'load_callback'           => array
			(
				array('\Samson\Helper', 'getDate')
			),
			'save_callback' => array
			(
				array('\Samson\Helper', 'putDate')
			),
			'sql'                     => "int(8) unsigned NOT NULL default '0'"
		),
		'deathplace' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['deathplace'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'death' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['death'],
			'inputType'               => 'checkbox',
			'filter'                  => true,
			'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'clr'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'multiSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['multiSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array
			(
				'multiple'            => true, 
				'fieldType'           => 'checkbox', 
				'files'               => true, 
				'isGallery'           => true,
				'extensions'          => Config::get('validImageTypes'),
				'mandatory'           => false
			),
			'sql'                     => "blob NULL"
		), 
		'shortinfo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['shortinfo'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE'),
			'explanation'             => 'insertTags', 
			'sql'                     => "mediumtext NULL"
		),
		'longinfo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['longinfo'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE'),
			'explanation'             => 'insertTags', 
			'sql'                     => "text NULL"
		),
		'wikipedia' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['wikipedia'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		), 
		'fide_id' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['fide_id'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>10, 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'dewis_id' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['dewis_id'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>10, 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'chessgames_id' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['chessgames_id'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		), 
		'chess365_id' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['365chess_id'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		), 
		'chess_id' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['chess_id'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		), 
		'homepage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['homepage'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'long clr'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		), 
		'importance' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['importance'],
			'exclude'                 => true,
			'default'                 => 5,
			'search'                  => true,
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 12,
			'inputType'               => 'select',
			'options'                 => array(10, 9, 8, 7, 6, 5, 4, 3, 2, 1),
			'reference'               => &$GLOBALS['TL_LANG']['tl_spielerregister'],
			'sql'                     => "char(2) NOT NULL default '5'"
		), 
		// Ehrenpräsident im Jahr xxxx
		'honorpresident' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['honorpresident'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>4, 'tl_class'=>'w50', 'rgxp' => 'digit'),
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		// Ehrenmitglied im Jahr xxxx
		'honormember' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['honormember'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>4, 'tl_class'=>'w50', 'rgxp' => 'digit'),
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		// Goldene Ehrennadel im Jahr xxxx
		'honorgoldpin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['honorgoldpin'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>4, 'tl_class'=>'w50', 'rgxp' => 'digit'),
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		// Silberne Ehrennadel im Jahr xxxx
		'honorsilverpin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['honorsilverpin'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>4, 'tl_class'=>'w50', 'rgxp' => 'digit'),
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		// Goldene Ehrenplakette im Jahr xxxx
		'honorgoldbadge' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['honorgoldbadge'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>4, 'tl_class'=>'w50', 'rgxp' => 'digit'),
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		// Silberne Ehrenplakette im Jahr xxxx
		'honorsilverbadge' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['honorsilverbadge'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>4, 'tl_class'=>'w50', 'rgxp' => 'digit'),
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		// Ehrenbrief im Jahr xxxx
		'honorletter' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['honorletter'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>4, 'tl_class'=>'w50', 'rgxp' => 'digit'),
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		// Ehrenteller im Jahr xxxx
		'honorplate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['honorplate'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>4, 'tl_class'=>'w50', 'rgxp' => 'digit'),
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		// Bundesmedaille im Jahr xxxx
		'honormedal' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['honormedal'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>4, 'tl_class'=>'w50', 'rgxp' => 'digit'),
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		'intern' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['intern'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE'),
			'sql'                     => "text NULL"
		), 
		'nohighlighting' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['nohighlighting'],
			'inputType'               => 'checkbox',
			'filter'                  => true,
			'eval'                    => array('tl_class' => 'w50','isBoolean' => true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'active' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister']['active'],
			'inputType'               => 'checkbox',
			'default'                 => 1,
			'filter'                  => true,
			'eval'                    => array('tl_class' => 'w50','isBoolean' => true),
			'sql'                     => "char(1) NOT NULL default '1'"
		),
	)
);


/**
 * Class tl_spielerregister
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 */
class tl_spielerregister extends Backend
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
	 * Datensätze auflisten
	 * @param array
	 * @return string
	 */
	public function listPersons($arrRow)
	{ 
		// Name und Vorname ausgeben
		$temp = $arrRow['surname1'];
		if($arrRow['firstname1']) $temp .= ', ' . $arrRow['firstname1'];
		// Lebensdaten ausgeben
		$birthday = \Samson\Helper::getDate($arrRow['birthday']);
		$deathday = \Samson\Helper::getDate($arrRow['deathday']);
		$death = $arrRow['death'];
		// Summe bilden für weitere Operationen
		$summe = 0;
		if($birthday) $summe += 1;
		if($deathday) $summe += 2;
		if($death) $summe += 4;
		// Summe abfragen und Text bauen
		switch($summe)
		{
			case 1: $temp .= ' (* ' . $birthday . ')'; break;
			case 2: 
			case 6: $temp .= ' (* ?, &dagger; ' . $deathday . ')'; break;
			case 3: 
			case 7: $temp .= ' (* '.$birthday.', &dagger; '.$deathday . ')'; break;
			case 4: $temp .= ' (* ?, &dagger; ?)'; break;
			case 5: $temp .= ' (* '.$birthday.', &dagger; ?)'; break;
			default:
		}
		return $temp;
	}


	/**
	 * Generiert automatisch ein Alias aus allen Vornamen und allen Nachnamen
	 * @param mixed
	 * @param \DataContainer
	 * @return string
	 * @throws \Exception
	 */
	public function generateAlias(DataContainer $dc)
	{
		$temp = $dc->activeRecord->surname1;
		if($dc->activeRecord->surname2) $temp .= '-'.$dc->activeRecord->surname2;
		if($dc->activeRecord->surname3) $temp .= '-'.$dc->activeRecord->surname3;
		if($dc->activeRecord->surname4) $temp .= '-'.$dc->activeRecord->surname4;
		if($dc->activeRecord->firstname1) $temp .= '-'.$dc->activeRecord->firstname1;
		if($dc->activeRecord->firstname2) $temp .= '-'.$dc->activeRecord->firstname2;
		if($dc->activeRecord->firstname3) $temp .= '-'.$dc->activeRecord->firstname3;
		if($dc->activeRecord->firstname4) $temp .= '-'.$dc->activeRecord->firstname4;

		$temp = StringUtil::generateAlias($temp);
		\Database::getInstance()->prepare("UPDATE tl_spielerregister SET alias=? WHERE id=?")
		                        ->execute($temp, $dc->id);
	}


	public function getInfobox(DataContainer $dc)
	{
		// Suchmaschinenlinks generieren
		$googlelink = 'https://www.google.de/search?q=%22' . $dc->activeRecord->firstname1 . '+' . $dc->activeRecord->surname1 . '%22+schach';
		$chess365link = 'http://www.365chess.com/search_result.php?wlname=' . $dc->activeRecord->surname1 . '&amp;wname=' . $dc->activeRecord->firstname1 . '&amp;nocolor=on&amp;sply=1&amp;submit_search=1';
		$chessgameslink = 'http://www.chessgames.com/perl/ezsearch.pl?search=' . $dc->activeRecord->surname1;
		$chesslink = 'https://www.chess.com/games/search?sort=6&amp;p1=' . $dc->activeRecord->surname1 . '+' . $dc->activeRecord->firstname1;

		// Popuplink generieren
		$popuplink = 'contao/main.php?do=spielerregister&amp;table=tl_spielerregister_images&amp;id=' . $dc->activeRecord->id . '&amp;popup=1&amp;rt=' . REQUEST_TOKEN . '" title="' . sprintf(specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $dc->activeRecord->id) . '" style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':840,\'title\':\'' . specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_content']['editalias'][1], $dc->activeRecord->id))) . '\',\'url\':this.href});return false';

		// Vorhandene Bilder anzeigen
		$bilder = '';
		$objImages = $this->Database->prepare('SELECT * FROM tl_spielerregister_images WHERE pid = ?')
		                           ->execute($dc->activeRecord->id);
		if($objImages->numRows)
		{
			while($objImages->next()) 
			{
				$objFile = \FilesModel::findByPk($objImages->singleSRC);
				$thumbnail = Image::get($objFile->path, 20, 20, 'crop'); 
				$bilder .= '<img src="' . $thumbnail . '" /> ';
			}
		}

		// Fotolink generieren
		if($bilder)
			$fotolink = 'Fotos bearbeiten: <a href="' . $popuplink . '">'.$bilder.'</a>';
		else
			$fotolink = '<a href="' . $popuplink . '">Fotos bearbeiten</a>';
		
		$string = '
<div class="long">
  <h3><label>Infobox</label></h3>
  <p>Spieler suchen in: <a href="' . $googlelink . '" target="_blank">Google</a> |
  <a href="' . $chess365link . '" target="_blank">365chess.com</a> |
  <a href="' . $chessgameslink . '" target="_blank">chessgames.com</a> |
  <a href="' . $chesslink . '" target="_blank">chess.com</a></p>
  <p>'.$fotolink.'</p>
</div>'; 
		
		return $string;
	}

/*
https://community.contao.org/de/showthread.php?48275-DCA-Filter-erstellen-von-Child-Table
*/
	
    public function generateAdvancedFilter(DataContainer $dc)
    {
    
        if (\Input::get('id') > 0) {
            return '';
        }

        $session = \Session::getInstance()->getData();

        // Filters
        $arrFilters = array
        (
            'spr_filter'   => array
            (
                'name'    => 'spr_filter',
                'label'   => $GLOBALS['TL_LANG']['tl_spielerregister']['filter_extended'],
                'options' => array
				(
					'1' => $GLOBALS['TL_LANG']['tl_spielerregister']['filter_over100'], 
					'2' => $GLOBALS['TL_LANG']['tl_spielerregister']['filter_birthdayfail'],
					'3' => $GLOBALS['TL_LANG']['tl_spielerregister']['filter_deathdayfail'],
					'4' => $GLOBALS['TL_LANG']['tl_spielerregister']['filter_birthdayerror'],
					'5' => $GLOBALS['TL_LANG']['tl_spielerregister']['filter_deathdayerror'],
					'8' => $GLOBALS['TL_LANG']['tl_spielerregister']['filter_birthplacefail'],
					'9' => $GLOBALS['TL_LANG']['tl_spielerregister']['filter_deathplacefail'],
					'6' => $GLOBALS['TL_LANG']['tl_spielerregister']['filter_shortinfo'],
					'7' => $GLOBALS['TL_LANG']['tl_spielerregister']['filter_firstnamefail'],
				)
            ),
        );

        $strBuffer = '
<div class="tl_filter spr_filter tl_subpanel">
<strong>' . $GLOBALS['TL_LANG']['tl_spielerregister']['filter'] . ':</strong> ' . "\n";

        // Generate filters
        foreach ($arrFilters as $arrFilter) {
            $strOptions = '
  <option value="' . $arrFilter['name'] . '">' . $arrFilter['label'] . '</option>
  <option value="' . $arrFilter['name'] . '">---</option>' . "\n";

            // Generate options
            foreach ($arrFilter['options'] as $k => $v) {
                $strOptions .= '  <option value="' . $k . '"' . (($session['filter']['tl_registerFilter'][$arrFilter['name']] === (string) $k) ? ' selected' : '') . '>' . $v . '</option>' . "\n";
            }

            $strBuffer .= '<select name="' . $arrFilter['name'] . '" id="' . $arrFilter['name'] . '" class="tl_select' . (isset($session['filter']['tl_registerFilter'][$arrFilter['name']]) ? ' active' : '') . '">
' . $strOptions . '
</select>' . "\n";
        }

        return $strBuffer . '</div>'; 

    }  
	
    public function applyAdvancedFilter()
    {
    
        $session = $this->Session->getData();

        // Store filter values in the session
        foreach ($_POST as $k => $v) {
            if (substr($k, 0, 4) != 'spr_') {
                continue;
            }

            // Reset the filter
            if ($k == \Input::post($k)) {
                unset($session['filter']['tl_registerFilter'][$k]);
            } // Apply the filter
            else {
                $session['filter']['tl_registerFilter'][$k] = \Input::post($k);
            }
        }

        $this->Session->setData($session);

        if (\Input::get('id') > 0 || !isset($session['filter']['tl_registerFilter'])) {
            return;
        }

        $arrPlayers = null;


        switch ($session['filter']['tl_registerFilter']['spr_filter']) {
            case '1': // Älter als 100 Jahre, nicht verstorben
                $hundertjahre = date("Ymd") - 1000000; // Aktuelles Datum minus 100 Jahre
				$verstorben = false; // Nicht verstorben
				$objPlayers = \Database::getInstance()->prepare("SELECT id FROM tl_spielerregister WHERE birthday <= ? AND birthday != 0 AND death = ?")
													  ->execute($hundertjahre, $verstorben);
				$arrPlayers = is_array($arrPlayers) ? array_intersect($arrPlayers, $objPlayers->fetchEach('id')) : $objPlayers->fetchEach('id');
				break;
            case '2': // Geburtsdatum fehlt
				$objPlayers = \Database::getInstance()->prepare("SELECT id FROM tl_spielerregister WHERE birthday = 0")
													  ->execute();
				$arrPlayers = is_array($arrPlayers) ? array_intersect($arrPlayers, $objPlayers->fetchEach('id')) : $objPlayers->fetchEach('id');
                break;
            case '3': // Sterbedatum fehlt
				$objPlayers = \Database::getInstance()->prepare("SELECT id FROM tl_spielerregister WHERE deathday = 0 AND death = '1'")
													  ->execute();
				$arrPlayers = is_array($arrPlayers) ? array_intersect($arrPlayers, $objPlayers->fetchEach('id')) : $objPlayers->fetchEach('id');
                break;
            case '4': // Geburtsdatum unvollständig
				$objPlayers = \Database::getInstance()->prepare("SELECT id FROM tl_spielerregister WHERE birthday % 100 = 0 AND birthday != 0")
													  ->execute();
				$arrPlayers = is_array($arrPlayers) ? array_intersect($arrPlayers, $objPlayers->fetchEach('id')) : $objPlayers->fetchEach('id');
                break;
            case '5': // Sterbedatum unvollständig
				$objPlayers = \Database::getInstance()->prepare("SELECT id FROM tl_spielerregister WHERE deathday % 100 = 0 AND deathday != 0 AND death = '1'")
													  ->execute();
				$arrPlayers = is_array($arrPlayers) ? array_intersect($arrPlayers, $objPlayers->fetchEach('id')) : $objPlayers->fetchEach('id');
                break;
            case '6': // Kurzinfo fehlt
				$objPlayers = \Database::getInstance()->prepare("SELECT id FROM tl_spielerregister WHERE shortinfo = ''")
													  ->execute();
				$arrPlayers = is_array($arrPlayers) ? array_intersect($arrPlayers, $objPlayers->fetchEach('id')) : $objPlayers->fetchEach('id');
                break;
            case '7': // Vorname fehlt
				$objPlayers = \Database::getInstance()->prepare("SELECT id FROM tl_spielerregister WHERE firstname1 = ''")
													  ->execute();
				$arrPlayers = is_array($arrPlayers) ? array_intersect($arrPlayers, $objPlayers->fetchEach('id')) : $objPlayers->fetchEach('id');
                break;
            case '8': // Geburtsort fehlt
				$objPlayers = \Database::getInstance()->prepare("SELECT id FROM tl_spielerregister WHERE birthplace = ''")
													  ->execute();
				$arrPlayers = is_array($arrPlayers) ? array_intersect($arrPlayers, $objPlayers->fetchEach('id')) : $objPlayers->fetchEach('id');
                break;
            case '9': // Sterbeort fehlt
				$objPlayers = \Database::getInstance()->prepare("SELECT id FROM tl_spielerregister WHERE deathplace = '' AND death = '1'")
													  ->execute();
				$arrPlayers = is_array($arrPlayers) ? array_intersect($arrPlayers, $objPlayers->fetchEach('id')) : $objPlayers->fetchEach('id');
                break;

            default:
        }

        if (is_array($arrPlayers) && empty($arrPlayers)) {
            $arrPlayers = array(0);
        }

        $GLOBALS['TL_DCA']['tl_spielerregister']['list']['sorting']['root'] = $arrPlayers; 

	}  
	
	/**
	 * Ändert das Aussehen des Toggle-Buttons.
	 * @param $row
	 * @param $href
	 * @param $label
	 * @param $title
	 * @param $icon
	 * @param $attributes
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		$this->import('BackendUser', 'User');
		
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 0));
			$this->redirect($this->getReferer());
		}
		
		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_spielerregister::active', 'alexf'))
		{
			return '';
		}
		
		$href .= '&amp;id='.$this->Input->get('id').'&amp;tid='.$row['id'].'&amp;state='.$row[''];
		
		if (!$row['active'])
		{
			$icon = 'invisible.gif';
		}
		
		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}

	/**
	 * Toggle the visibility of an element
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnPublished)
	{
		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_spielerregister::active', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide record ID "'.$intId.'"', 'tl_spielerregister toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
		
		$this->createInitialVersion('tl_spielerregister', $intId);
		
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_spielerregister']['fields']['active']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_spielerregister']['fields']['active']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnPublished = $this->$callback[0]->$callback[1]($blnPublished, $this);
			}
		}
		
		// Update the database
		$this->Database->prepare("UPDATE tl_spielerregister SET tstamp=". time() .", active='" . ($blnPublished ? '' : '1') . "' WHERE id=?")
		               ->execute($intId);
		$this->createNewVersion('tl_spielerregister', $intId);
	}
}
