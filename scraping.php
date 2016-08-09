<?php
//require_once("relation.php");
require_once("lib/phpQuery-onefile.php");
class Scraping{

	function scraping_all($html, $data_array){

		$doc = phpQuery::newDocument($html);
		$obj = pq($doc);
		$result = array();
		$data_point = array(
			"field" => 0,
			"address" => 1,
			"library" => 3,
			"state" => 5,
			"reservation" => 6
		);
		$result["id"] = $data_array["CD"];
		$result["alltitle"] = $obj->find("a-tr:eq(0)")->text();
		$result["bibliography"] = substr($obj->find("input[name='II".$data_array["CD"]."']")->val(), 0, -2);
		//$result["relation"] = Relation::relationdata($result["bibliography"]);

		for($i = 1; $i < count($obj->find("form[name='fms']")->find("tr")); $i++){
			foreach ($data_point as $key => $value) {
				$result[$key][$i - 1] = Scraping::trim_emspace($obj->find("form[name='fms']")->find("tr:eq(".$i.")")->find("td:eq(".$value.")")->text());
			}
		}

		$count = count($obj->find("table:eq(5)")->find("tr"));
		for($i = 0; $i < $count; $i++){
			$key = $obj->find("table:eq(5)")->find("tr:eq(".$i.")")->find("td:eq(0)")->text();
			$key = substr($key, 1, -1);
			switch($key){
				case "書名・責任表示":
					$value = $obj->find("table:eq(5)")->find("tr:eq(".$i.")")->find("td:eq(1)")->text();
					$title = explode("/", $value);
					$result["title"] = trim($title[0]);
					break;

				case "著者標目":
					$value = $obj->find("table:eq(5)")->find("tr:eq(".$i.")")->find("td:eq(1)")->find("a")->text();
					$result["author"][] = Scraping::author_trim($value);
					break;
			
				case "出版・頒布事項":
					$value = $obj->find("table:eq(5)")->find("tr:eq(".$i.")")->find("td:eq(1)")->text();
					$value = explode(",", $value);
					$publication = explode(":", $value[0]);
					$year = explode(".", $value[1]);
					$result["publication"][] = trim($publication[1]);
					if(2 <= count($year)){
						if(strlen($year[1]) < 2) $year[1] = "0".$year[1];
						$result["year"] = trim($year[0])."-".$year[1]."-01";

					}else{
						$result["year"] = trim($year[0])."-01-01";
					}
					break;

				case "件名標目":
					$value = $obj->find("table:eq(5)")->find("tr:eq(".$i.")")->find("td:eq(1)")->text();
					$subject = explode(":", $value);
					$result["subject"][] = $subject[1];
					break;

				case "刊年":
					$value = $obj->find("table:eq(5)")->find("tr:eq(".$i.")")->find("td:eq(1)")->text();
					$insurance_year = $value."-01-01";
					break;

				default:
					break;
			}
		}
		
		if(strlen($result["year"]) != 10) $result["year"] = $insurance_year;
		if(!$result["bibliography"]){
			$result = null;
			exit();
		}

		return $result;
	}

	function scraping_title($html){
		$doc = phpQuery::newDocument($html);
		$obj = pq($doc);
		$title = $obj->find("a-tr:eq(0)")->text();

		return $title;
	}

	function trim_emspace ($str) {
		$str = @preg_replace('/^[ 　]+/u', '', $str);
		$str = @preg_replace('/[ 　]+$/u', '', $str);
		if(strlen($str) == 0) $str = "　";

		return $str;
	}

	function author_trim($str){
		$str = preg_replace('/[][}{)(!"#$%&\'~|\*+,\/@.\^<>`;:?_=\\\\-]/i', '', $str);
		$str = preg_replace('/\d+/', '', $str);
		$str =  preg_replace('/( |　)/', '', $str);

		return $str;
	}

	
}

?>