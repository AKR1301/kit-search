<?php
require_once("htmldata.php");
require_once("scraping.php");
require_once("dbaccess.php");
require_once("savedata.php");

function DataSet(){
	if(isset($_POST["keyword"])) $data_array["SW1"] = $_POST["keyword"];
	else $data_array["SW1"] = null;

	if(isset($_POST["CD"])){
		$data_array["CD"] = $_POST["CD"];
		$data_array["FR"] = $_POST["CD"];
	}else{
		$data_array["CD"] = "1";
		$data_array["FR"] = "1";
	}

	if(isset($_POST["RI"])) $data_array["RI1"] = $_POST["RI"];
	else $data_array["RI1"] = "AL";

	return $data_array;
}

$data_array = Dataset();
$html = HtmlData::htmldata_get($data_array);
$result = Scraping::scraping_all($html, $data_array);
//DB
$link = DBAccess::db_login();
SaveData::save_bookdata($result, $link);
DBAccess::db_logout($link);


header("Content-Type: application/json; charset=utf-8");
echo json_encode($result);
?>