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
class Yearday extends \Module
{

	var $monatsname = array("","Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember");

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_spielerregister');

			$objTemplate->wildcard = '### SPIELERREGISTER ###';
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
		global $objPage;

		// Zeitlichen Rahmen festlegen
		$ZEITSTEMPEL["von"] = time();
		$ZEITSTEMPEL["bis"] = strtotime("+60 day");
		$von = date("Ymd",$ZEITSTEMPEL["von"]);
		$vonjahr = substr($von,0,4);
		$bis = date("Ymd",$ZEITSTEMPEL["bis"]);
		$bisjahr = substr($bis,0,4);
		
		$objRegister = $this->Database->prepare('SELECT * FROM tl_spielerregister WHERE active = 1 AND nohighlighting = 0 AND (importance = 10 OR importance >= ?)')
		                              ->execute($this->spielerregister_level);

		// Template-Objekt anlegen
		$this->Template = new \FrontendTemplate('spielerregister_yearday');

		$daten = array();
		$zaehler = 0;
		if($objRegister->numRows > 1)
		{
			// Datensätze anzeigen
			while($objRegister->next()) {
				
				$vondatum[0] = substr_replace($objRegister->birthday,$vonjahr,0,4);
				$vondatum[1] = substr_replace($objRegister->birthday,$bisjahr,0,4);
				$bisdatum[0] = substr_replace($objRegister->deathday,$vonjahr,0,4);
				$bisdatum[1] = substr_replace($objRegister->deathday,$bisjahr,0,4);
				
				$tag = 0+substr($objRegister->birthday,6,2);
				if(($vondatum[0] >= $von && $vondatum[0] <= $bis && $tag) || ($vondatum[1] >= $von && $vondatum[1] <= $bis && $tag)) 
				{
					// Spieler merken
					$zaehler++;
					$daten['typ'][$zaehler] = 'birthday';
					$daten['id'][$zaehler] = $objRegister->id;
					$daten['nachname'][$zaehler] = $objRegister->surname1;
					$daten['vorname'][$zaehler] = $objRegister->firstname1;
					$daten['geburtstag'][$zaehler] = $this->DatumToString($objRegister->birthday);
					$daten['verstorben'][$zaehler] = $objRegister->death;
					$daten['todestag'][$zaehler] = $this->DatumToString($objRegister->deathday);
					$daten['alter'][$zaehler] = $this->Alter($objRegister->birthday, $von, $bis);
					$daten['bedeutung'][$zaehler] = $objRegister->importance;
					$daten['wikipedia'][$zaehler] = $objRegister->wikipedia;
					$daten['kurzinfo'][$zaehler] = $objRegister->shortinfo;
			 		if($vondatum[0] >= $von && $vondatum[0] <= $bis) $daten['sortierung'][$zaehler] = $vondatum[0];
					else $daten['sortierung'][$zaehler] = $vondatum[1];
					$daten['monatstag'][$zaehler] = $this->LadeTag($daten['sortierung'][$zaehler]);
					// Jüngstes Bild laden
					$objImage = $this->Database->prepare('SELECT * FROM tl_spielerregister_images WHERE pid = ? ORDER BY imagedate DESC LIMIT 1')
					                           ->execute($objRegister->id);
					if($objImage->numRows)
					{
						$objFile = \FilesModel::findByPk($objImage->singleSRC);
						$thumbnail = Image::get($objFile->path, 120, 120, 'proportional'); 
						$daten['bild'][$zaehler] = $thumbnail;
					}
					else $daten['bild'][$zaehler] = '';
				}
				$tag = 0+substr($objRegister->deathday,6,2);
				if(($bisdatum[0] >= $von && $bisdatum[0] <= $bis && $tag) || ($bisdatum[1] >= $von && $bisdatum[1] <= $bis && $tag)) 
				{
					// Spieler merken
					$zaehler++;
					$daten['typ'][$zaehler] = 'deathday';
					$daten['id'][$zaehler] = $objRegister->id;
					$daten['nachname'][$zaehler] = $objRegister->surname1;
					$daten['vorname'][$zaehler] = $objRegister->firstname1;
					$daten['geburtstag'][$zaehler] = $this->DatumToString($objRegister->birthday);
					$daten['verstorben'][$zaehler] = $objRegister->death;
					$daten['todestag'][$zaehler] = $this->DatumToString($objRegister->deathday);
					$daten['alter'][$zaehler] = $this->Alter($objRegister->deathday, $von, $bis);
					$daten['bedeutung'][$zaehler] = $objRegister->importance;
					$daten['wikipedia'][$zaehler] = $objRegister->wikipedia;
					$daten['kurzinfo'][$zaehler] = $objRegister->shortinfo;
			 		if($bisdatum[0] >= $von && $bisdatum[0] <= $bis) $daten['sortierung'][$zaehler] = $bisdatum[0];
					else $daten['sortierung'][$zaehler] = $bisdatum[1];
					$daten['monatstag'][$zaehler] = $this->LadeTag($daten['sortierung'][$zaehler]);
					// Jüngstes Bild laden
					$objImage = $this->Database->prepare('SELECT * FROM tl_spielerregister_images WHERE pid = ? ORDER BY imagedate DESC LIMIT 1')
					                           ->execute($objRegister->id);
					if($objImage->numRows)
					{
						$objFile = \FilesModel::findByPk($objImage->singleSRC);
						$thumbnail = Image::get($objFile->path, 120, 120, 'proportional'); 
						$daten['bild'][$zaehler] = $thumbnail;
					}
					else $daten['bild'][$zaehler] = '';
				}
			}
		}
		
		// Daten sortieren
		if($daten['sortierung']) array_multisort($daten['sortierung'],$daten['monatstag'],$daten['id'],$daten['typ'],$daten['nachname'],$daten['vorname'],$daten['geburtstag'],$daten['verstorben'],$daten['todestag'],$daten['alter'],$daten['bedeutung'],$daten['wikipedia'],$daten['kurzinfo'],$daten['bild']);
		
		$this->Template->sortierung = $daten['sortierung'][0];
		$this->Template->monatstag = $daten['monatstag'][0];
		$this->Template->id = $daten['id'][0];
		$this->Template->typ = $daten['typ'][0];
		$this->Template->nachname = $daten['nachname'][0];
		$this->Template->vorname = $daten['vorname'][0];
		$this->Template->geburtstag = $daten['geburtstag'][0];
		$this->Template->verstorben = $daten['verstorben'][0];
		$this->Template->todestag = $daten['todestag'][0];
		$this->Template->alter = $daten['alter'][0];
		$this->Template->bedeutung = $daten['bedeutung'][0];
		$this->Template->wikipedia = $daten['wikipedia'][0];
		$this->Template->kurzinfo = $daten['kurzinfo'][0];
		$this->Template->bild = $daten['bild'][0];
		$this->Template->Anzahl = $zaehler;

	}

	protected function LadeTag($datum) {
	
		return (0+substr($datum,6,2)).". ".$this->monatsname[0+substr($datum,4,2)];
	
	}
	
	protected function LadeJahr($datum) {
	
		return substr($datum,0,4);
	
	}

	protected function DatumToString($datum) {

		$temp = '';
		$tag = (int)substr($datum,6,2) + 0;
		$monat = (int)substr($datum,4,2) + 0;
		$jahr = (int)substr($datum,0,4) + 0;

		if($tag) $temp .= sprintf('%02d', $tag) . '.';
		if($monat) $temp .= sprintf('%02d', $monat) . '.';
		if($jahr) $temp .= sprintf('%04d', $jahr);

		if(!$temp) $temp = 'unbekannt';
		return $temp;

	}

	protected function Alter($datum, $von, $bis) 
	{
	
		$jahr = substr($datum,0,4);
		$vonjahr = substr($von,0,4);
		$bisjahr = substr($bis,0,4);
		
		$monat = substr($datum,4);
		$vonmonat = substr($von,4);
		$bismonat = substr($bis,4);
		
		if($vonjahr == $bisjahr) {
			// Gleiches Jahr, Berechnung ganz einfach
			return $vonjahr - $jahr;
		}
		elseif($monat < $vonmonat && $monat <= $bismonat) {
			// Monat zu klein, muß also Folgejahr sein
			return $bisjahr - $jahr;
		}
		elseif($monat > $bismonat && $monat >= $vonmonat) {
			// Monat zu groß, muß also letztes Jahr sein
			return $vonjahr - $jahr;
		}
		else return false;
	
	}

}
