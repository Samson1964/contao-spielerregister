<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

class Spielerregister 
{

	/**
	 * Gibt ein Array mit den Spielerbildern zurück
	 * @param string	Serialisiertes Array mit den Daten
	 * @return array
	 */
	public static function Bilder($string) 
	{
		global $objPage;

		$multiSRC = deserialize($string);
		if(!is_array($multiSRC) || empty($multiSRC))
		{
			return '';
		}
		else
		{
			// Get the file entries from the database
			$objFiles = \FilesModel::findMultipleByUuids($multiSRC);
		}

		$images = array();
		$auxDate = array();

		// Get all images
		while ($objFiles->next())
		{
			// Continue if the files has been processed or does not exist
			if (isset($images[$objFiles->path]) || !file_exists(TL_ROOT . '/' . $objFiles->path))
			{
				continue;
			}

			// Single files
			if ($objFiles->type == 'file')
			{
				$objFile = new \File($objFiles->path, true);

				if (!$objFile->isImage)
				{
					continue;
				}

				$arrMeta = \Frontend::getMetaData($objFiles->meta, $objPage->language);

				// Use the file name as title if none is given
				if ($arrMeta['title'] == '')
				{
					$arrMeta['title'] = specialchars($objFile->basename);
				}
				// Bildunterschrift modifizieren
				if($arrMeta['caption'] == '')
				{
					$arrMeta['caption'] = self::ExtractCaption(specialchars($objFile->filename));
				}

				// Add the image
				$images[$objFiles->path] = array
				(
					'id'        => $objFiles->id,
					'uuid'      => $objFiles->uuid,
					'name'      => $objFile->basename,
					'singleSRC' => $objFiles->path,
					'alt'       => $arrMeta['title'],
					'imageUrl'  => $arrMeta['link'],
					'caption'   => \Samson\Helper::replaceCopyright($arrMeta['caption']),
					'thumb'     => Image::get($objFiles->path, 180, 180, 'center_center')
				);

				$auxDate[] = $objFile->mtime;
			}

			// Folders
			else
			{
				$objSubfiles = \FilesModel::findByPid($objFiles->uuid);

				if ($objSubfiles === null)
				{
					continue;
				}

				while ($objSubfiles->next())
				{
					// Skip subfolders
					if ($objSubfiles->type == 'folder')
					{
						continue;
					}

					$objFile = new \File($objSubfiles->path, true);

					if (!$objFile->isImage)
					{
						continue;
					}

					$arrMeta = \Frontend::getMetaData($objSubfiles->meta, $objPage->language);

					// Use the file name as title if none is given
					if ($arrMeta['title'] == '')
					{
						$arrMeta['title'] = specialchars($objFile->basename);
					}
					// Bildunterschrift modifizieren
					if($arrMeta['caption'] == '')
					{
						$arrMeta['caption'] = self::ExtractCaption(specialchars($objFile->filename));
					}

					// Add the image
					$images[$objSubfiles->path] = array
					(
						'id'        => $objSubfiles->id,
						'uuid'      => $objSubfiles->uuid,
						'name'      => $objFile->basename,
						'singleSRC' => $objSubfiles->path,
						'alt'       => $arrMeta['title'],
						'imageUrl'  => $arrMeta['link'],
						'caption'   => \Samson\Helper::replaceCopyright($arrMeta['caption']),
						'thumb'     => Image::get($objSubfiles->path, 180, 180, 'center_center')
					);

					$auxDate[] = $objFile->mtime;
				}
			}
		}

		return $images;
	
	}

	public static function ExtractCaption($string) 
	{
		$parts = explode('_', $string); // Dateiname trennen

		foreach($parts as $part)
		{
			if(is_numeric($part)) return $part;
		}
		
		return '';
	
	}

	public function ReplacePlayer($strTag)
	{

		$arrSplit = explode('::', $strTag);

		if($arrSplit[0] == 'register' || $arrSplit[0] == 'cache_register')
		{
			// Inhalt des Inserttags trennen
			$data = explode('|', $arrSplit[1]);

			// Template-Objekt erzeugen
			$this->Template = new \FrontendTemplate('spielerregister_infobox');

			$found = false; // Spieler erstmal auf nicht gefunden setzen
			// Parameter prüfen
			if(is_int($data[0]))
			{
				// Übergeben wurde die Spielerregister-ID, SQL-Abfrage zusammenbauen
				$objPlayer = \Database::getInstance()->prepare("SELECT * FROM tl_spielerregister WHERE id=?")
				                                     ->execute($data[0]);
			}
			elseif($data[0])
			{
				// Übergeben wurde ein String, Trennung nach Komma versuchen
				$teile = explode(',', $data[0]);
				if(count($teile) == 1)
				{
					// Trennung nach Leerzeichen versuchen
					$teile = explode(' ', $data[0]);
					if(count($teile) == 1)
					{
						// Nur 1 Wort vorhanden
					}
					elseif(count($teile) > 1)
					{
						$objPlayer = \Database::getInstance()->prepare("SELECT * FROM tl_spielerregister WHERE surname1 = ? AND firstname1 = ?")
						                                     ->execute($teile[1], $teile[0]);
					}
				}
			}

			// Suche auswerten
			if($objPlayer && $objPlayer->numRows == 1)
			{
				$this->Template->playername = $data[1] ? $data[1] : $data[0];
				$this->Template->playerid = $objPlayer->id;
			}
			else
			{
				// Template mit Vorgabewerten füllen
				$this->Template->playername = $data[1] ? $data[1] : $data[0];
				$this->Template->gefunden = false;
			}
						
			// Geparstes Template zurückgeben
			return $this->Template->parse();
		}

		// Nicht unser Insert-Tag
		return false;

	}

}
