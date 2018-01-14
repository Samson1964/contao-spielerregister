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
class ExportSpielerregister extends \Backend
{

	/**
	 * Return a form to choose a CSV file and import it
	 * @param object
	 * @return string
	 */

	public function exportSpieler(DataContainer $dc)
	{
		if ($this->Input->get('key') != 'export')
		{
			return '';
		}
		
		// get records
		$arrExport = array();
		$objRow = $this->Database->prepare("SELECT surname1,firstname1,birthday,deathday,importance FROM tl_spielerregister")
								 ->execute($dc->id);

		while ($objRow->next())
		{
			$arrExport[] = $objRow->row();			
		}

		// start output
                $csv_delimiter = ';';
                                
		$exportFile =  'Spielerregister-Export_' . date("Ymd-Hi");
		
		header('Content-Type: application/csv');
		header('Content-Transfer-Encoding: binary');
		header('Content-Disposition: attachment; filename="' . $exportFile .'.csv"');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Expires: 0');

		$output = '';
		$output .= '"Name"'.$csv_delimiter.'"Vorname"'.$csv_delimiter.'"Geburtsdatum"'.$csv_delimiter
                        .'"Sterbedatum"'.$csv_delimiter.'"Wertung"'. "\n" ;

		foreach ($arrExport as $export) 
		{
			$output .= '"' . join('"'.$csv_delimiter.'"', $export).'"' . "\n";
		}

		echo $output;
		// Popuplink generieren
		$golink = 'contao/main.php?do=spielerregister&amp;rt=' . REQUEST_TOKEN; 
		header('Location:' . $golink);
	}
	
}
