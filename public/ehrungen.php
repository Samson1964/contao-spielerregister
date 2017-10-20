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
class Ehrungsliste 
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
		
		// Personen laden, die geehrt wurden und leben
		$objPlayers = \Database::getInstance()
			->prepare("SELECT * FROM tl_spielerregister WHERE active = 1 
						AND death != '1'
						AND	(honorpresident != ''
						OR honormember != ''
						OR honorgoldpin != ''
						OR honorsilverpin != ''
						OR honorgoldbadge != ''
						OR honorsilverbadge != ''
						OR honorletter != ''
						OR honorplate != '')
					")
			->execute();
		if($objPlayers->numRows) 
		{
			while($objPlayers->next())
			{
				$debugausgabe .= "<br>" . $objPlayers->surname1;
				$geburtstag = substr($objPlayers->birthday,4,4);
				$debugausgabe .= "<br>* " . $geburtstag;
				for($x=0;$x<count($zielzeit);$x++)
				{
					$debugausgabe .= "<br>- " . $zielzeit[$x];
					// Bei Treffer, Spieler in Ausgabe schreiben
					if($geburtstag == $zielzeit[$x])
					{
						// Wievielter Geburtstag?
						$alter = $zieljahr[$x] - substr($objPlayers->birthday,0,4);
						$ausgabe[$x] .= '<b>' . $alter . '. Geburtstag von ' . $objPlayers->firstname1 . ' ' . $objPlayers->surname1 . '</b><br>';
						// Ehrungen ausgeben
						if($objPlayers->honorpresident) $ausgabe[$x] .= '<i>- Ehrenpräsident ' . $objPlayers->honorpresident . '</i><br>';
						if($objPlayers->honormember) $ausgabe[$x] .= '<i>- Ehrenmitglied ' . $objPlayers->honormember . '</i><br>';
						if($objPlayers->honorgoldpin) $ausgabe[$x] .= '<i>- Goldene Ehrennadel ' . $objPlayers->honorgoldpin . '</i><br>';
						if($objPlayers->honorsilverpin) $ausgabe[$x] .= '<i>- Silberne Ehrennadel ' . $objPlayers->honorsilverpin . '</i><br>';
						if($objPlayers->honorgoldbadge) $ausgabe[$x] .= '<i>- Goldene Ehrenplakette ' . $objPlayers->honorgoldbadge . '</i><br>';
						if($objPlayers->honorsilverbadge) $ausgabe[$x] .= '<i>- Silberne Ehrenplakette ' . $objPlayers->honorsilverbadge . '</i><br>';
						if($objPlayers->honorletter) $ausgabe[$x] .= '<i>- Ehrenbrief ' . $objPlayers->honorletter . '</i><br>';
						if($objPlayers->honorplate) $ausgabe[$x] .= '<i>- Ehrenteller ' . $objPlayers->honorplate . '</i><br>';
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
			$content = '<p>Folgende Geburtstage von geehrten Personen stehen an:</p>' . $content;
			$content .= '<p><i>DIESE E-MAIL WURDE AUTOMATISCH GENERIERT!</i></p>';
			// Email versenden, wenn Ehrungen anstehen
			$objEmail = new \Email();
			$objEmail->from = 'webmaster@schachbund.de';
			$objEmail->fromName = 'DSB-Geburtstage';
			$objEmail->subject = '[DSB-Webinfo] Geburtstage aus Ehrungslisten';
			$objEmail->html = $content;
			$objEmail->sendCc(array
			(
				'Ullrich Krause <praesident@schachbund.de>',
				'Uwe Bönsch <sportdirektor@schachbund.de>',
				'DSB-Presse <presse@schachbund.de>'
			)); 
			$objEmail->sendTo(array('Frank Hoppe <webmaster@schachbund.de>')); 
		}
	}
}

/**
 * Instantiate controller
 */
$objEhrungsliste = new Ehrungsliste();
$objEhrungsliste->run();

