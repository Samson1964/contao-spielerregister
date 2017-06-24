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
 * Table tl_spielerregister_images
 */
$GLOBALS['TL_DCA']['tl_spielerregister_images'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_spielerregister',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index',
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('imagedate DESC'),
			'headerFields'            => array('surname1', 'firstname1'),
			'panelLayout'             => 'filter;sort,search,limit',
			'child_record_callback'   => array('tl_spielerregister_images', 'listImages'),  
		),
		'global_operations' => array
		(
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
				'label'               => &$GLOBALS['TL_LANG']['tl_spielerregister_images']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_spielerregister_images']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_spielerregister_images']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_spielerregister_images']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_spielerregister_images']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_spielerregister_images']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{image_legend},singleSRC,imagedate;{info_legend},title,year,copyright;{active_legend},active'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_spielerregister.alias',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager')
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister_images']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('mandatory'=>true, 'filesOnly'=>true, 'fieldType'=>'radio', 'tl_class'=>'w50'),
			'sql'                     => "binary(16) NULL"
		), 
		'imagedate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister_images']['imagedate'],
			'exclude'                 => true,
			'sorting'                 => true,
			'flag'                    => 12,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'rgxp'=>'digit', 'tl_class'=>'w50', 'maxlength'=>8),
			'sql'                     => "varchar(8) NOT NULL default ''"
		), 
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister_images']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'long clr'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		), 
		'year' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister_images']['year'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>16, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		), 
		'copyright' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister_images']['copyright'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		), 
		'active' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_spielerregister_images']['active'],
			'default'                 => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50','isBoolean' => true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
	)
);


/**
 * Class tl_spielerregister_images
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 */
class tl_spielerregister_images extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	public function listImages($arrRow)
	{
		// Bild extrahieren
		$objFile = \FilesModel::findByPk($arrRow['singleSRC']);
		$thumbnail = Image::get($objFile->path, 70, 70, 'crop');

		$temp = '<div class="tl_content_left" style="min-width:300px">';
		$temp .= '<img src="' . $thumbnail . '" width="70" height="70" style="float:left; margin-right:5px" />';
		$temp .= 'Datei: <b>' . $objFile->path . '</b><br>';
		if($arrRow['year']) $temp .= 'Datum: <b>' . $arrRow['year'] . '</b><br>';
		if($arrRow['copyright']) $temp .= 'Rechte: <b>' . $arrRow['copyright'] . '</b><br>';
		if($arrRow['title']) $temp .= 'Titel: <b>' . $arrRow['title'] . '</b><br>';
		return $temp.'</div>';
	}
  
}
