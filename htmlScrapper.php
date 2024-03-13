<?php
require '../simplehtmldom_1_9_1/simple_html_dom.php';
require './DiplomskiRadovi.php';

function dlPage($href) {

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_URL, $href);
    curl_setopt($curl, CURLOPT_REFERER, $href);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $str = curl_exec($curl);
    curl_close($curl);

    // Create a DOM object
    $dom = new simple_html_dom();
    // Load HTML from a string
    $dom->load($str);

    return $dom;
}
function getRadoviOnPage($pageNumber) {
	$url = 'https://stup.ferit.hr/index.php/zavrsni-radovi/page/' . $pageNumber;
	$data = dlPage($url);
	$diplomskiRadovi = array();

	$last_element = null;
	foreach ($data->find('article[id^=blog-1-post] a[href*=https://stup.ferit.hr/]') as $element) {
		if ($last_element ==  $element->href) {
			continue;
		}
		$last_element =  $element->href;
		$new_diplomski = new DiplomskiRadovi();
		$new_diplomski->link_rada = $last_element;
		$new_diplomski->naziv_rada = $element->plaintext;
		$diplomskiRadovi[] = $new_diplomski;
	}

	$index = 0;
	foreach ($data->find('img[src^=https://stup.ferit.hr/wp-content/logos]') as $element) {
		$numbers = intval(preg_replace('/[^0-9]+/', '', $element->src));
		$diplomskiRadovi[$index]->oib_tvrtke = $numbers;
		$index++;
	}

	$index = 0;
	foreach ($data->find('div[class="fusion-post-content-container"]') as $element) {
		$diplomskiRadovi[$index]->tekst_rada = $element->plaintext;
		$index++;
	}

	return $diplomskiRadovi;
}

?>
