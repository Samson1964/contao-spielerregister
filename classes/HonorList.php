<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package   fh-counter
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2014
 */

/**
 * Class CounterRegister
 *
 * @copyright  Frank Hoppe 2014
 * @author     Frank Hoppe
 *
 * Basisklasse vom FH-Counter
 * Erledigt die Zählung der jeweiligen Contenttypen und schreibt die Zählerwerte in $GLOBALS
 */
class HonorList extends \Module
{

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_spielerregister');

			$objTemplate->wildcard = '### SPIELERREGISTER EHRUNGSLISTE ###';
			$objTemplate->title = $this->name;
			$objTemplate->id = $this->id;

			return $objTemplate->parse();
		}
		
		return parent::generate(); // Weitermachen mit dem Modul
	}

	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->import('Database');

		// Art der Liste für Abfrage feststellen
		switch($this->spielerregister_honorListtype)
		{
			case 'pr': // Liste der Ehrenpräsidenten
				$sql = 'honorpresident != \'\' ORDER BY honorpresident DESC';
				break;
			case 'me': // Liste der Ehrenmitglieder
				$sql = 'honormember != \'\' ORDER BY honormember DESC';
				break;
			case 'go': // Liste der Träger der Goldenen Ehrennadel
				$sql = 'honorgoldpin != \'\' ORDER BY honorgoldpin DESC';
				break;
			case 'si': // Liste der Träger der Silbernen Ehrennadel
				$sql = 'honorsilverpin != \'\' ORDER BY honorsilverpin DESC';
				break;
			case 'gb': // Liste der Träger der Goldenen Ehrenplakette
				$sql = 'honorgoldbadge != \'\' ORDER BY honorgoldbadge DESC';
				break;
			case 'sb': // Liste der Träger der Silbernen Ehrenplakette
				$sql = 'honorsilverbadge != \'\' ORDER BY honorsilverbadge DESC';
				break;
			case 'le': // Liste der Empfänger des Ehrenbriefes
				$sql = 'honorletter != \'\' ORDER BY honorletter DESC';
				break;
			case 'pl': // Liste der Empfänger des Ehrentellers
				$sql = 'honorplate != \'\' ORDER BY honorplate DESC';
				break;
			case 'bm': // Liste der Empfänger der Bundesmedaille
				$sql = 'honormedal != \'\' ORDER BY honormedal DESC';
				break;
		}
		
		$objRegister = $this->Database->prepare('SELECT * FROM tl_spielerregister WHERE active = 1 AND ' . $sql)
									  ->execute();

		// Template-Objekt anlegen
		$this->Template = new \FrontendTemplate('spielerregister_honorlist');

		$output = array();
		$x = 0;
		if($objRegister->numRows)
		{
			// Datensätze anzeigen
			while($objRegister->next()) 
			{
				// Jüngstes Bild laden
				$objImage = $this->Database->prepare('SELECT * FROM tl_spielerregister_images WHERE pid = ? ORDER BY imagedate DESC')
				                           ->limit(1)
				                           ->execute($objRegister->id);
				if($objImage->numRows)
				{
					$objFile = \FilesModel::findByPk($objImage->singleSRC);
					$thumbnail = Image::get($objFile->path, 16, 16, 'proportional'); 
					$output[$x]['bild'] = ''; //$thumbnail;
				}
				else $output[$x]['bild'] = '';
				$output[$x]['id'] = $objRegister->id;
				$output[$x]['vorname'] = $objRegister->firstname1;
				$output[$x]['nachname'] = $objRegister->surname1;
				$output[$x]['tot'] = $objRegister->death;
				// Art der Liste für Abfrage feststellen
				switch($this->spielerregister_honorListtype)
				{
					case 'pr': // Liste der Ehrenpräsidenten
						$output[$x]['jahr'] = $objRegister->honorpresident;
						break;
					case 'me': // Liste der Ehrenmitglieder
						$output[$x]['jahr'] = $objRegister->honormember;
						break;
					case 'go': // Liste der Träger der Goldenen Ehrennadel
						$output[$x]['jahr'] = $objRegister->honorgoldpin;
						break;
					case 'si': // Liste der Träger der Silbernen Ehrennadel
						$output[$x]['jahr'] = $objRegister->honorsilverpin;
						break;
					case 'gb': // Liste der Träger der Goldenen Ehrenplakette
						$output[$x]['jahr'] = $objRegister->honorgoldbadge;
						break;
					case 'sb': // Liste der Träger der Silbernen Ehrenplakette
						$output[$x]['jahr'] = $objRegister->honorsilverbadge;
						break;
					case 'le': // Liste der Empfänger des Ehrenbriefes
						$output[$x]['jahr'] = $objRegister->honorletter;
						break;
					case 'pl': // Liste der Empfänger des Ehrentellers
						$output[$x]['jahr'] = $objRegister->honorplate;
						break;
					case 'bm': // Liste der Empfänger der Bundesmaedaille
						$output[$x]['jahr'] = $objRegister->honormedal;
						break;
				}
				$x++;
			}
		}

		$this->Template->data = $output;
		
	}

}
