<?php
/*
*******************************************************************************
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*******************************************************************************
*
* This class detect the language of a given text.
* Requires pspell and an aspell dictionary for each language that you want to detect.
*
* @author Marco Martinelli (https://github.com/martinellimarco)
*/
class LangDetector{
	var $langs;
	var $badChars = array(',','.',';',':','?','!','#','(',')','[',']','{','}','<','>','+','-','_','&','@','*','\'','"','^','\\','/','%','$','€','£','0','1','2','3','4','5','6','7','8','9','|');
	var $thres = 0.75;

	/**
	 * $langs is an array of language codes, e.g. ['it','en','fr']
	 */
	function LangDetector($langs){
		$this->langs = $langs;
	}

	private function filter($word){
		return strlen(trim($word))>0;
	}

	/**
	 * Returns an associative array that map each language code to the probability that $text is of that language.
	 */
	function getProbabilities($text){
		$probs=Array();

		$words = array_filter(explode(' ',str_replace($this->badChars,' ',$text)), array($this, 'filter'));

		$totalWords=count($words);

		foreach($this->langs as $lang){
			$pspell = pspell_new($lang);
			$goodWords=0;
			foreach($words as $word){
				if(pspell_check($pspell, $word)){
					$goodWords++;
				}
			}
			$probs[$lang]=$goodWords/$totalWords;
		}

		arsort($probs);
		return $probs;
	}

	/**
	 * Returns the most probable language for the given $text if the probability is above a threshold (0.75 by default), false otherwhise.
	 */
	function getLang($text){
		$probs = $this->getProbabilities($text);
		$lang = key($probs);
		if($probs[$lang]>=$this->thres){
			return $lang;
		}else{
			return false;
		}
	}

	/**
	 * Set the threshold used by the getLang function.
	 */
	function setThres($thres){
		$this->thres=$thres;
	}
}
