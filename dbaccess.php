<?php

class DBAccess{

	function db_login(){
		$link = new mysqli("mysql530.db.sakura.ne.jp","akrhome","akr1916ia","akrhome_book1");
		if($link->connect_error){
			echo $link->connect_error;
		}

		return $link;
	}

	function db_logout($link){
		$link->close();
	}

}

?>