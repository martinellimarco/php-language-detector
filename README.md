# PHP Language Detector
This is a simple library that detect the language of a given text in a defined set of languages.
It requires pspell and an aspell dictionary for each language that you want to detect.

## Prerequisites
Install pspell and one or more aspell dictionaries of your choice
On Ubuntu and other Debian based distro you can type

	sudo apt-get install php5-pspell aspell-en

to install pspell and the english aspell dictionary.

## Usage examples
You have a text and you want to know its language code

	include_once("LangDetector.php");
	
	$text = "Ma la volpe col suo balzo ha raggiunto il quieto fido.";
	$ld = new LangDetector(['it','en','fr']);
	echo "The language is: ".$ld->getLang($text);

This will print

	The language is: it

because $text is in italian.

Please note that getLang will return false if the most probable language has a probability that is lower than a threshold (0.75 by default)!
You can use

	$ld->setThres(0.5);

to change the threshold. A threshold of 0 will cause getLang to always return a valid language code.

You may be interested in the probability of $text to be any of the languages.

	$ld->getProbabilities($text);

will return

	Array
	(
		[it] => 1
		[fr] => 0.45454545454545
		[en] => 0.36363636363636
	)

Finally, the input text is filtered in order to better detect the language.
The filter will strip out all sort of characters allowing the library to detect the language of texts like the following.

	//detect the language of the words in a SEF url
	$ld->getLang("/this-is/a-search-engine-friendly-url/document.php"); //will return 'en'

	//messed text
	$ld->getLang("#HI@how?are€you^"); //will return 'en'

