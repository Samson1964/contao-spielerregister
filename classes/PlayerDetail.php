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
class PlayerDetail extends \Module
{

	var $playerid; // Nimmt die ID des abgefragten Spielers auf
	var $playersearch; // Hier wird der Suchbegriff gespeichert
	var $minImportance = 1; // Minimale Relevanz
	
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_spielerregister');

			$objTemplate->wildcard = '### SPIELERREGISTER DETAILS ###';
			$objTemplate->title = $this->name;
			$objTemplate->id = $this->id;

			return $objTemplate->parse();
		}
		else
		{
			// FE-Modus: URL mit allen möglichen Parametern auflösen
			\Input::setGet('player', \Input::get('player')); // Spieler-ID
			$this->playerid = \Input::get('player');
			$this->playersearch = strtolower(\Input::get('psearch'));
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

		// Weiterleitungsseite ermitteln
		$jumpTo = \PageModel::findByPk($this->spielerregister_jumpTo);
		
		$objRegister = $this->Database->prepare('SELECT * FROM tl_spielerregister WHERE active = 1 AND importance >= ? AND id = ?')
									  ->limit(1)
		                              ->execute($this->minImportance, $this->playerid);

		// Template-Objekt anlegen
		$this->Template = new \FrontendTemplate('spielerregister_playerdetail');

		// Link zur aktuellen Seite (inkl. Parameter) für Suchformular zuweisen
		$this->Template->action = ampersand(\Environment::get('indexFreeRequest')); 
		
		// Suche auswerten
		if($this->playersearch)
		{
			$this->Template->sucheaktiv = true; // Eine Suche wurde ausgelöst
			$suchlaenge = strlen($this->playersearch); // Länge des Suchbegriffs

			if($suchlaenge > 1)
			{
				// Sucht nach Suchbegriff über alle 4 Nachnamenfelder
				$objSuche = $this->Database->prepare('SELECT * FROM tl_spielerregister WHERE active = 1 AND importance >= ? AND (surname1 LIKE ? OR surname2 LIKE ? OR surname3 LIKE ? OR surname4 LIKE ?) ORDER BY surname1 ASC')
										->execute($this->minImportance, '%'.$this->playersearch.'%', '%'.$this->playersearch.'%', '%'.$this->playersearch.'%', '%'.$this->playersearch.'%');
				$this->Template->suchtreffer = $objSuche->numRows;
				$this->Template->suchbegriff = $this->playersearch;
				$ergebnisliste = array();
				$zaehler = 0;
				while($objSuche->next())
				{
					$ergebnisliste[$zaehler]['name'] = $objSuche->firstname1 . ' ' . $objSuche->surname1;
					$ergebnisliste[$zaehler]['id'] = $objSuche->id;
					$ergebnisliste[$zaehler]['link'] = $jumpTo->getFrontendUrl('/player/' . $objSuche->id);
					// Lebensdaten für Ausgabe aufbereiten
					$birthday = $this->DatumToString($objSuche->birthday);
					$deathday = $this->DatumToString($objSuche->deathday);
					($birthday) ? $temp = '* ' . $birthday : $temp = '';
					if($objSuche->death) 
					{
						($temp) ? $temp .= '; &dagger;' : $temp = '&dagger;';
						($deathday) ? $temp .= ' ' . $deathday : $temp .= ' unbekannt';
					}
					$ergebnisliste[$zaehler]['lebensdaten'] = $temp;
					$zaehler++;
				}			
				$this->Template->suchergebnis = $ergebnisliste;
				if($objSuche->numRows == 0) $this->Template->suchfehler = 'Keine Treffer für "<i>' . $this->playersearch . '</i>".'; 
			}
			else $this->Template->suchfehler = 'Ihr Suchbegriff "<i>' . $this->playersearch . '</i>" enthält zu wenig Zeichen.';
		}

		if($objRegister->id)
		{
			$this->Template->id = $objRegister->id;
			$this->Template->nachname = $objRegister->surname1;
			$this->Template->vorname = $objRegister->firstname1;
			// Name für Ausgabe aufbereiten
			($objRegister->firstname1) ? $this->Template->name = $objRegister->firstname1 . ' ' . $objRegister->surname1 : $this->Template->name = $objRegister->surname1;
			// Seitentitel modifizieren
			$objPage->pageTitle = $this->Template->name . ' | ' . $objPage->pageTitle;
			$this->Template->geburtstag = $this->DatumToString($objRegister->birthday);
			$this->Template->verstorben = $objRegister->death;
			$this->Template->todestag = $this->DatumToString($objRegister->deathday);
			// Lebensdaten für Ausgabe aufbereiten
			($this->Template->geburtstag) ? $temp = '* ' . $this->Template->geburtstag : $temp = '';
			if($objRegister->death) 
			{
				($temp) ? $temp .= '; &dagger;' : $temp = '&dagger;';
				($this->Template->todestag) ? $temp .= ' ' . $this->Template->todestag : $temp .= ' unbekannt';
			}
			$this->Template->lebensdaten = $temp;
			$this->Template->bedeutung = $objRegister->importance;
			$this->Template->wikipedia = $objRegister->wikipedia;
			$this->Template->kurzinfo = $objRegister->shortinfo;
			$this->Template->langinfo = $objRegister->langinfo;
			$this->Template->chess365_id = $objRegister->chess365_id;
			$this->Template->chess_id = $objRegister->chess_id;
			$this->Template->chessgames_id = $objRegister->chessgames_id;

			// Bilder aus multiSRC holen
			$images = Spielerregister::Bilder($objRegister->multiSRC);
			if($images) 
			{
				$this->Template->slider = true;
				$this->Template->images = $images; 
				$GLOBALS['TL_CSS'][] = 'system/modules/spielerregister/assets/css/js-image-slider.css';
				$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/spielerregister/assets/js/js-image-slider.js';
			}
			else
			{
				$this->Template->slider = false; 
				$images = array();
				
				// Jüngstes Bild laden
				$objImage = $this->Database->prepare('SELECT * FROM tl_spielerregister_images WHERE pid = ? AND active = 1 ORDER BY imagedate DESC')
				                           ->execute($objRegister->id);
				if($objImage->numRows)
				{
					$GLOBALS['TL_CSS'][] = 'system/modules/spielerregister/assets/css/js-image-slider.css';
					$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/spielerregister/assets/js/js-image-slider.js';
					while($objImage->next())
					{
						$objFile = \FilesModel::findByPk($objImage->singleSRC);
						// Bildunterschrift zusammenbauen
						($objImage->title) ? $caption = $objImage->title : $caption = '';
						if($objImage->year)
						{
							($caption) ? $caption .= ' (' . $objImage->year . ')' : $caption = $objImage->year;
						}
						if($objImage->copyright) $caption .= '[' . $objImage->copyright . ']';
						// Nach Copyright per Regex suchen
						$caption = \Samson\Helper::replaceCopyright($caption);
						$images[$objFile->path] = array
						(
							'id'        => $objFile->id,
							'uuid'      => $objFile->uuid,
							'name'      => $objFile->basename,
							'singleSRC' => $objFile->path,
							'alt'       => '',
							'imageUrl'  => '',
							'caption'   => $caption,
							'thumb'     => Image::get($objFile->path, 180, 180, 'center_center')
						);
					}
					$objFile = \FilesModel::findByPk($objImage->singleSRC);
					$this->Template->bildoriginal = $objFile->path; 
					$this->Template->bildvorschau = Image::get($objFile->path, 180, 180, 'center_center'); 
					$temp = getimagesize($this->Template->bildvorschau); 
					$this->Template->bildbreite = $temp[0]; 
					$this->Template->bildjahr = $objImage->year; 
					$this->Template->bildtitel = $objImage->title; 
					$this->Template->bildquelle = $objImage->copyright; 
					// Bildunterschrift zusammenbauen
					($objImage->title) ? $caption = $objImage->title : $caption = '';
					if($objImage->year)
					{
						($caption) ? $caption .= ' (' . $objImage->year . ')' : $caption = $objImage->year;
					}
					if($objImage->copyright) $caption .= '[' . $objImage->copyright . ']';
					// Nach Copyright per Regex suchen
					$found = preg_match("/(\[.+\])/",$caption,$treffer,PREG_OFFSET_CAPTURE);
					if($found)
					{
						// Copyright gefunden, Länge und Position speichern
						$cplen = strlen($treffer[0][0]);
						$cppos = $treffer[0][1];
						// Copyright ersetzen
						$cpstr = str_replace("[","<div class=\"rechte\">",$treffer[0][0]);
						$cpstr = str_replace("]","</div>",$cpstr);
						// Restliche Bildunterschrift extrahieren
						$caption = substr($caption,0,$cppos).substr($caption,$cppos+$cplen);
						// Copyright hinzufügen
						$caption = $cpstr.$caption;
					}
					$this->Template->caption = $caption; 
					$this->Template->slider = true; 
					$this->Template->images = $images; 
				}
				else
				{
					$this->Template->bildoriginal = '';
					$this->Template->bildvorschau = '';
					$this->Template->bildjahr = ''; 
					$this->Template->bildtitel = ''; 
					$this->Template->bildquelle = ''; 
					$this->Template->caption = ''; 
				}
			}

		}

	}

	protected function LadeTag($datum) {
	
		return (0+substr($datum,6,2)).". ".$this->monatsname[0+substr($datum,4,2)];
	
	}
	
	protected function LadeJahr($datum) {
	
		return substr($datum,0,4);
	
	}

	protected function DatumToString($datum) {

		$temp = '';
		$tag = substr($datum,6,2) + 0;
		$monat = substr($datum,4,2) + 0;
		$jahr = substr($datum,0,4) + 0;

		if($tag) $temp .= sprintf('%02d', $tag) . '.';
		if($monat) $temp .= sprintf('%02d', $monat) . '.';
		if($jahr) $temp .= sprintf('%04d', $jahr);

		if(!$temp) $temp = '';
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
