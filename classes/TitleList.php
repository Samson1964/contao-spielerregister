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
 * Benutzerdefinierten Namespace festlegen, damit die Klasse ersetzt werden kann
 */
namespace Samson\Playerbase;

/**
 * Class CounterRegister
 *
 * @copyright  Frank Hoppe 2014
 * @author     Frank Hoppe
 *
 * Basisklasse vom FH-Counter
 * Erledigt die Zählung der jeweiligen Contenttypen und schreibt die Zählerwerte in $GLOBALS
 */
class TitleList extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'spielerregister_titlelist';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_spielerregister');

			$objTemplate->wildcard = '### SPIELERREGISTER TITELLISTE ###';
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
		$listenart = array
		(
			'gm' => 'gm_',
			'im' => 'im_',
			'wg' => 'wgm_',
			'wi' => 'wim_'
		);
		$objRegister = \Database::getInstance()->prepare('SELECT * FROM tl_spielerregister WHERE active = ? AND '.$listenart[$this->spielerregister_titlelist].'title = 1')
											   ->execute(1);

		// Template-Objekt anlegen
		$this->Template = new \FrontendTemplate($this->strTemplate);

		$output = array();
		if($objRegister->numRows)
		{
			// Datensätze anzeigen
			while($objRegister->next()) 
			{
				switch($this->spielerregister_titlelist)
				{
					case 'gm': $titeldatum = $objRegister->gm_date; break;
					case 'im': $titeldatum = $objRegister->im_date; break;
					case 'wg': $titeldatum = $objRegister->wgm_date; break;
					case 'wi': $titeldatum = $objRegister->wim_date; break;
				}
				$objDatum = $this->Alter($objRegister->birthday, $titeldatum, true);
				$output[] = array
				(
					'id'                => $objRegister->id,
					'vorname'           => $objRegister->firstname1,
					'nachname'          => $objRegister->surname1,
					'geburtstag'        => $this->getDate($objRegister->birthday),
					'sterbetag'         => $this->getDate($objRegister->deathday),
					'titeldatum'        => $this->getDate($titeldatum),
					'verleihungsalter'  => $objDatum->y . " Jahr(e), " . $objDatum->m . " Monat(e), " . $objDatum->d . " Tag(e)",
					'anzahlTage'        => $objDatum->days
				);
			}
		}

		$output = $this->sortArrayByFields(
			$output,
			array(
				'anzahlTage' => array(SORT_ASC, SORT_NUMERIC)
			)
		);

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
	protected function Alter($start, $ende, $genau = false)
	{
		//echo "Start: $start | Ende: $ende<br>";
		// Startdatum umwandeln
		$laenge = strlen($start);
		switch($laenge)
		{
			case 4: // JJJJ - dann Jahresmitte festlegen
				$day = 30;
				$month = 1;
				$year = $start;
				break;
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
		if($month == 0) $month = '06';
		if($day == 0) $day = '01';
		$startdatum = $day.'.'.$month.'.'.$year;
		$startdatum = $year.'-'.$month.'-'.$day;

		//echo "Start: $start | $day / $month / $year<br>";
		// Startdatum prüfen
		//if(!checkdate($month, $day, $year)) return false;
		//echo "Start: $start | $day / $month / $year<br>";

		// Endedatum umwandeln
		$laenge = strlen($ende);
		switch($laenge)
		{
			case 4: // JJJJ - dann Jahresmitte festlegen
				$cur_day = 30;
				$cur_month = 6;
				$cur_year = $ende;
				break;
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
		if($cur_month == 0) $cur_month = '06';
		if($cur_day == 0) $cur_day = '01';
		$endedatum = $cur_day.'.'.$cur_month.'.'.$cur_year;
		$endedatum = $cur_year.'-'.$cur_month.'-'.$cur_day;

		//echo "Ende: $ende | $cur_day / $cur_month / $cur_year<br>";
		// Endedatum prüfen
		//if(!checkdate($cur_month, $cur_day, $cur_year)) return false;
		//echo "Ende: $ende | $cur_day / $cur_month / $cur_year<br>";

		//echo "Startdatum: $startdatum | Endedatum: $endedatum<br>";
		$date1 = new \DateTime($startdatum);
		$date2 = new \DateTime($endedatum);
		
		//print_r($interval);
		$interval = $date1->diff($date2);
		//print_r($interval);
		return $interval;
		
		// Differenz in Jahren, Tagen und Monaten
		echo "Differenz: " . $interval->y . " Jahr(e), " . $interval->m . " Monat(e), " . $interval->d . " Tag(e)<br>";
		// Differenz in Tagen
		echo "Differenz: " . $interval->days . " Tag(e)<br>";

		// Alter berechnen
		$calc_year = $cur_year - $year;
		if($month > $cur_month) return $calc_year - 1;
		elseif($month == $cur_month && $day > $cur_day) return $calc_year - 1;
		else return $calc_year;
	
	}

	function sortArrayByFields($arr, $fields)
	{
		$sortFields = array();
		$args       = array();
		
		foreach ($arr as $key => $row) {
			foreach ($fields as $field => $order) {
				$sortFields[$field][$key] = $row[$field];
			}
		}
		
		foreach ($fields as $field => $order) {
			$args[] = $sortFields[$field];
			
			if (is_array($order)) {
				foreach ($order as $pt) {
					$args[$pt];
				}
			} else {
				$args[] = $order;
			}
		}
		
		$args[] = &$arr;
		
		call_user_func_array('array_multisort', $args);
		
		return $arr;
	}

}