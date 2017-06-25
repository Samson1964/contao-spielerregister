<?php

/**
 * Contao Open Source CMS, Copyright (C) 2005-2013 Leo Feyer
 *
 *
 * Supported GET parameters:
 * - bid:   Banner ID
 *
 * Usage example:
 * <a href="system/modules/banner/public/conban_clicks.php?bid=7">
 *
 * @copyright	Glen Langer 2007..2013 <http://www.contao.glen-langer.de>
 * @author      Glen Langer (BugBuster)
 * @package     Banner
 * @license     LGPL
 * @filesource
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
use Contao\Controller;

/**
 * Initialize the system
 */
define('TL_MODE', 'FE');
// ER2 / ER3 (dev over symlink)
if(file_exists('../../../initialize.php')) require('../../../initialize.php');
else require('../../../../../system/initialize.php');


/**
 * Class BannerClicks
 *
 * Banner ReDirect class
 * @copyright  Glen Langer 2007..2013
 * @author     Glen Langer
 * @package    Banner
 */
class Jahrestage
{
	public function run()
	{
		// Zeitlichen Rahmen festlegen
		$zieltext[0] = 'Heute';
		$zieltext[1] = 'Morgen';
		$zieltext[2] = 'In 1 Woche';
		$zieltext[3] = 'In 2 Wochen';
		$zielzeit[0] = date("md"); // Tag heute
		$zielzeit[1] = date("md",strtotime("+1 day")); // Tag morgen
		$zielzeit[2] = date("md",strtotime("+1 week")); // Tag in 1 Woche
		$zielzeit[3] = date("md",strtotime("+2 week")); // Tag in 2 Wochen
		$zieljahr[0] = date("Y"); // Jahr heute
		$zieljahr[1] = date("Y",strtotime("+1 day")); // Jahr morgen
		$zieljahr[2] = date("Y",strtotime("+1 week")); // Jahr in 1 Woche
		$zieljahr[3] = date("Y",strtotime("+2 week")); // Jahr in 2 Wochen
		// Ausgabearray initialisieren
		$ausgabe = array('', '', '', '');
		$debugausgabe = '';
		
		// Personen laden, bei denen demnächst Jahrestage anstehen
		$objPlayers = \Database::getInstance()
			->prepare("SELECT * FROM tl_spielerregister WHERE active = 1")
			->execute();
		if($objPlayers->numRows) 
		{
			while($objPlayers->next())
			{
				$debugausgabe .= "<br>" . $objPlayers->surname1;
				$geburtstag = substr($objPlayers->birthday,4,4);
				($objPlayers->death) ? $todestag = substr($objPlayers->deathday,4,4) : $todestag = false;
				$debugausgabe .= "<br>* " . $geburtstag;
				for($x=0;$x<count($zielzeit);$x++)
				{
					$todtext = '';
					$debugausgabe .= "<br>- " . $zielzeit[$x];
					// Bei Treffer, Spieler in Ausgabe schreiben
					if($geburtstag == $zielzeit[$x])
					{
						// Wievielter Geburtstag?
						$alter = $zieljahr[$x] - substr($objPlayers->birthday,0,4);
						$tagestext = 'Geburtstag';
					}
					if($todestag == $zielzeit[$x])
					{
						// Wievielter Todestag?
						$alter = $zieljahr[$x] - substr($objPlayers->deathday,0,4);
						$tagestext = 'Todestag';
					}
					if($geburtstag == $zielzeit[$x] || $todestag == $zielzeit[$x])
					{
						// Alter zum Zeitpunkt des Todes berechnen
						if($objPlayers->birthday && $objPlayers->deathday)
						{
							$todesalter = $this->Alter($objPlayers->birthday, $objPlayers->deathday);
							($todesalter) ? $todtext = ' (&dagger; '.$todesalter.')' : $todtext = '';
							if(!$todtext && $objPlayers->death) $todtext = ' (&dagger; ?)';
						}
						// Spielername ausgeben
						$ausgabe[$x] .= '<p><b>' . $alter . '. ' . $tagestext . ' von <a href="http://www.schachbund.de/person/player/'.$objPlayers->id.'.html">' . $objPlayers->firstname1 . ' ' . $objPlayers->surname1 . '</a>'.$todtext.'</b></p>';
						$ausgabe[$x] .= '<div style="padding-left:10px;">';
						// Kurzinfo ausgeben
						if($objPlayers->shortinfo) $ausgabe[$x] .= $objPlayers->shortinfo;
						// Ehrungen ausgeben
						if($objPlayers->honorpresident) $ausgabe[$x] .= '<i>- Ehrenpräsident des DSB ' . $objPlayers->honorpresident . '</i><br>';
						if($objPlayers->honormember) $ausgabe[$x] .= '<i>- Ehrenmitglied des DSB ' . $objPlayers->honormember . '</i><br>';
						if($objPlayers->honorgoldpin) $ausgabe[$x] .= '<i>- Goldene Ehrennadel des DSB ' . $objPlayers->honorgoldpin . '</i><br>';
						if($objPlayers->honorsilverpin) $ausgabe[$x] .= '<i>- Silberne Ehrennadel des DSB ' . $objPlayers->honorsilverpin . '</i><br>';
						if($objPlayers->honorgoldbadge) $ausgabe[$x] .= '<i>- Goldene Ehrenplakette des DSB ' . $objPlayers->honorgoldbadge . '</i><br>';
						if($objPlayers->honorsilverbadge) $ausgabe[$x] .= '<i>- Silberne Ehrenplakette des DSB ' . $objPlayers->honorsilverbadge . '</i><br>';
						if($objPlayers->honorletter) $ausgabe[$x] .= '<i>- Ehrenbrief des DSB ' . $objPlayers->honorletter . '</i><br>';
						if($objPlayers->honorplate) $ausgabe[$x] .= '<i>- Ehrenteller des DSB ' . $objPlayers->honorplate . '</i><br>';
						// Absatz abschließen
						$ausgabe[$x] .= '</div>';
					}
				}
			}
		}
		echo $debugausgabe;
		$content = '';
		for($x=0;$x<count($ausgabe);$x++)
		{
			if($ausgabe[$x])
			{
				$tag = substr($zielzeit[$x],2,2) . '.' . substr($zielzeit[$x],0,2) . '.' . $zieljahr[$x];
				$ausgabe[$x] = '<h2>' . $zieltext[$x] . ' (' . $tag .')</h2><p>' . $ausgabe[$x] . '</p>';
				echo $ausgabe[$x];
				$content .= $ausgabe[$x];
			}
		}
		
		if($content)
		{
			// Newsletter-Empfänger laden
			$bcc = array();
			$objNewsletter = \Database::getInstance()
				->prepare("SELECT email FROM tl_newsletter_recipients WHERE active = 1 AND pid = 8")
				->execute();
			if($objNewsletter->numRows) 
			{
				while($objNewsletter->next())
				{
					$bcc[] = $objNewsletter->email;
				}
			}
			$content = '<h1>Jahrestagsnewsletter des Deutschen Schachbundes</h1><p>Folgende Jahrestage stehen an:</p>' . $content;
			$content .= '<p><i>DIESE E-MAIL WURDE AUTOMATISCH GENERIERT!</i></p><p>Wenn Sie Korrekturen oder Ergänzungen für uns haben, dann antworten Sie einfach auf diese E-Mail!</p>';
			$content .= '<p><a href="http://www.schachbund.de/persoenlichkeiten.html">Nächste Jahrestage</a> (komplett mit Fotos) | <a href="http://www.schachbund.de/gedenktafel.html">Unsere Gedenktafel</a> (Sterbefälle letzte 12 Monate)</p>';
			$content .= '<p><a href="http://www.schachbund.de/persoenlichkeiten-newsletter.html">Newsletter kündigen</a></p>';
			// Email versenden, wenn Jahrestage anstehen
			$objEmail = new \Email();
			$objEmail->from = 'webmaster@schachbund.de';
			$objEmail->fromName = 'DSB-Jahrestage';
			$objEmail->subject = '[DSB-Webinfo] Jahrestage Spielerregister';
			$objEmail->html = $content;
			if($bcc) $objEmail->sendBcc($bcc);
			$objEmail->sendTo(array('Frank Hoppe <webmaster@schachbund.de>')); 
		}
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

/**
 * Instantiate controller
 */
$objEhrungsliste = new Jahrestage();
$objEhrungsliste->run();

