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
class DeathList extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'spielerregister_deathlist';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_spielerregister');

			$objTemplate->wildcard = '### SPIELERREGISTER STERBELISTE ###';
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
		$startdatum = date("Ymd", strtotime("-".$this->spielerregister_deathlist_months." month"));
		$objRegister = \Database::getInstance()->prepare('SELECT * FROM tl_spielerregister WHERE active = ? AND death = ? AND deathday >= ? ORDER BY deathday DESC')
											   ->execute(1, 1, $startdatum);

		// Template-Objekt anlegen
		$this->Template = new \FrontendTemplate($this->strTemplate);

		$output = array();
		$x = 0;
		if($objRegister->numRows)
		{
			// Datensätze anzeigen
			while($objRegister->next()) 
			{
				$output[$x]['id'] = $objRegister->id;
				$output[$x]['vorname'] = $objRegister->firstname1;
				$output[$x]['nachname'] = $objRegister->surname1;
				$output[$x]['sterbetag'] = $this->getDate($objRegister->deathday);
				$output[$x]['kurzinfo'] = $objRegister->shortinfo;
				$alter = $this->Alter($objRegister->birthday, $objRegister->deathday);
				($alter) ? $output[$x]['alter'] = $alter : $output[$x]['alter'] = '?';
				$x++;
			}
		}

		$this->Template->data = $output;
		
	}

	/**
	 * Datumswert aus Datenbank umwandeln
	 * @param mixed
	 * @return mixed
	 */
	public function getDate($varValue)
	{
		$laenge = strlen($varValue);
		$temp = '';
		switch($laenge)
		{
			case 8: // JJJJMMTT
				$temp = substr($varValue,6,2).'.'.substr($varValue,4,2).'.'.substr($varValue,0,4);
				break;
			case 6: // JJJJMM
				$temp = substr($varValue,4,2).'.'.substr($varValue,0,4);
				break;
			case 4: // JJJJ
				$temp = $varValue;
				break;
			default: // anderer Wert
				$temp = '';
		}

		return $temp;
	}

	/**
	 * Alter aus zwei Datumsangaben ermitteln
	 * @param start = Startdatum als JJJJMMTT oder TT.MM.JJJJ
	 * @param ende = Endedatum als JJJJMMTT oder TT.MM.JJJJ
	 * @return int
	 */
	protected function Alter($start, $ende) 
	{
		// Startdatum umwandeln
		$laenge = strlen($start);
		switch($laenge)
		{
			case 8: // JJJJMMTT
				$day = substr($start,6,2);
				$month = substr($start,4,2);
				$year = substr($start,0,4);
				break;
			case 10: // TT.MM.JJJJ
				$day = substr($start,0,2);
				$month = substr($start,3,2);
				$year = substr($start,5,4);
				break;
			default: // anderer Wert
		}

		// Startdatum prüfen
		if(!checkdate($month, $day, $year)) return false;

		// Endedatum umwandeln
		$laenge = strlen($ende);
		switch($laenge)
		{
			case 8: // JJJJMMTT
				$cur_day = substr($ende,6,2);
				$cur_month = substr($ende,4,2);
				$cur_year = substr($ende,0,4);
				break;
			case 10: // TT.MM.JJJJ
				$cur_day = substr($ende,0,2);
				$cur_month = substr($ende,3,2);
				$cur_year = substr($ende,5,4);
				break;
			default: // anderer Wert
		}

		// Endedatum prüfen
		if(!checkdate($cur_month, $cur_day, $cur_year)) return false;

		// Alter berechnen
		$calc_year = $cur_year - $year;
		if($month > $cur_month) return $calc_year - 1;
		elseif($month == $cur_month && $day > $cur_day) return $calc_year - 1;
		else return $calc_year;
	
	}

}