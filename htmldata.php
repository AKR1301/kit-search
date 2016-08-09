<?php

class HtmlData{

	function htmldata_get($set_data){
		$data_array = HtmlData::data_set($set_data);
		$data = http_build_query($data_array, "", "&");

		$header = array(
			"Content-Type: application/x-xxx-form-urlencoded",
			"Content-Length: ".strlen($data)
		);

		$context = array(
			"http" => array(
				"method" => "POST",
				"header" => implode("\r\n", $header),
				"content" => $data
			)	
		);

		$url = "http://linkit.kanazawa-it.ac.jp/opac/cgi/searchS.cgi";
		$html = file_get_contents($url, false, stream_context_create($context));

		return $html;
	}

	function data_set($set_data){
		$data_array = array(
			"SFR" => "1",
			"BFR" => "1",
			"HC" => "10",
			"AC" => "5",
			"SC" => "F",
			"RM" => "100",
			"SC" => "F",
			"RS" => "0",
			"GM" => "2",
			"LG" => "jpn",
			"IO" => "1",
			"DB" => "0",
			"DocType" => "1",
			"SW0" => "otr",
			"RI0" => "AV",
			"CO6" => "0",
			"RI6" => "KB",
			"SL6" => "100",
			"KS" => "0",
			"LN" => "1"
		);

		foreach ($set_data as $key => $value) {
			$data_array[$key] = $value;
		}

		return $data_array;
	}

}

?>