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
 * Class Aenderungen
 *
 * Verschickt Änderungen und Neueinträge an alle Newsletter-Empfänger
 * @copyright  Frank Hoppe 2017..2017
 * @author     Frank Hoppe
 * @package    Spielerregister
 */
class Aenderungen
{
	public function run()
	{
		// Zeit festlegen, wo ein Datensatz als neu gilt
		$neuzeit = time() - (24 * 3600);
		// Ausgabe initialisieren
		$ausgabe = '';
		
		// Datensätze laden
		$objPlayers = \Database::getInstance()->prepare("SELECT * FROM tl_spielerregister WHERE active = 1 AND createtime >= ?")
		                                      ->execute($neuzeit);
		if($objPlayers->numRows)
		{
			$ausgabe = '<ul>';
			while($objPlayers->next())
			{
				$ausgabe .= '<li>';
				$ausgabe .= '<b>'.$objPlayers->firstname1.' '.$objPlayers->surname1.'</b>';
				$ausgabe .= '</li>';
			}
			$ausgabe .= '</ul>';
		}
		
		if($ausgabe)
		{
			echo $ausgabe;
			// Newsletter-Empfänger laden
			$bcc = array();
			$objNewsletter = \Database::getInstance()->prepare("SELECT email FROM tl_newsletter_recipients WHERE active = 1 AND pid = 8")
			                                         ->execute();
			if($objNewsletter->numRows) 
			{
				while($objNewsletter->next())
				{
					$bcc[] = $objNewsletter->email;
				}
			}
			$content = '<h1>Persönlichkeiten-Newsletter des Deutschen Schachbundes</h1>';
			$content .= '<p>Folgende Personen wurden <b>neu</b> in unsere Datenbank aufgenommen:</p>' . $ausgabe;
			$content .= '<p><i>DIESE E-MAIL WURDE AUTOMATISCH GENERIERT!</i></p><p>Wenn Sie Korrekturen oder Ergänzungen für uns haben, dann antworten Sie einfach auf diese E-Mail!</p>';
			$content .= '<p><a href="http://www.schachbund.de/persoenlichkeiten.html">Nächste Jahrestage</a> (komplett mit Fotos) | <a href="http://www.schachbund.de/gedenktafel.html">Unsere Gedenktafel</a> (Sterbefälle letzte 12 Monate)</p>';
			$content .= '<p><a href="http://www.schachbund.de/persoenlichkeiten-newsletter.html">Newsletter kündigen</a></p>';
			// Email versenden, wenn Jahrestage anstehen
			$objEmail = new \Email();
			$objEmail->from = 'webmaster@schachbund.de';
			$objEmail->fromName = 'DSB-Spielerregister';
			$objEmail->subject = '[DSB-Webinfo] Spielerregister - Neue Einträge';
			$objEmail->html = $content;
			//if($bcc) $objEmail->sendBcc($bcc);
			$objEmail->sendTo(array('Frank Hoppe <webmaster@schachbund.de>')); 
		}
	}

}

/**
 * Instantiate controller
 */
$objAenderungen = new Aenderungen();
$objAenderungen->run();
