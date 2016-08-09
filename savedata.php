<?php

class SaveData{

	function save_bookdata($resultData, $link){
		$link->set_charset("utf8");
		$sql = "select * from booksdata where bibNumber = ".$resultData["bibliography"];
		$result = $link->query($sql);
		if($result->num_rows <= 0){
			//booksdata
			$sql = "insert into booksdata value(
				".$resultData["bibliography"].",
				'".$resultData["title"]."',
				'".$resultData["year"]."'
			)";
			$result = $link->query($sql);
			//if(!$result) echo die("insert error booksdata");
			//bookdata
			for($i = 0; $i < count($resultData["library"]); $i++){
				$sql = "insert into bookdata value(
					".$resultData["library"][$i].",
					".$resultData["bibliography"].",
					'".$resultData["field"][$i]."',
					'".$resultData["address"][$i]."',
					'".$resultData["state"][$i]."',
					'".$resultData["reservation"][$i]."'
				)";
				$result = $link->query($sql);
				//if(!$result) echo die("insert error bookdata");
			}
			//author
			for($i = 0; $i < count($resultData["author"]); $i++){
				$sql = "insert into author value(".$resultData["bibliography"].",'".$resultData["author"][$i]."')";
				$result = $link->query($sql);
				//if(!$result) echo die("insert error author");
			}
			//subject
			for($i = 0; $i < count($resultData["subject"]); $i++){
				$sql = "insert into subject value(".$resultData["bibliography"].",'".$resultData["subject"][$i]."')";
				$result = $link->query($sql);
				//if(!$result) echo die("insert error subject");
			}
			//publication
			for($i = 0; $i < count($resultData["publication"]); $i++){
				$sql = "insert into publication value(".$resultData["bibliography"].",'".$resultData["publication"][$i]."')";
				$result = $link->query($sql);
				//if(!$result) echo die("insert error publication");
			}

		}else{
			/*
			while($row = $result->fetch_assoc()){
				echo $row["bibNumber"]." : ".$row["title"]." : ".$row["publication"]." : ".$row["year"]."<br />";
			}
			*/
		}

	}

	function save_searchdata(){
		
	}
}

?>