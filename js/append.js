function AppendData(data, send){

	for(i = 0; i < data.length; i++){
		id = i + parseInt(send["FR"]) - 1;
		$("#show_area").append("<div class='book_area' id='book_area" + id + "'></div>");
		//タイトル
		var title = data[i][0]["title"].split("/");
		$("#book_area" + id).append("<div class='title'><span class='char_red'>" + title[0] + "</span>/" + title[1] + "</div>");
		//データの種類
		$("#book_area" + id).append("<div class='book_menu' id='book_menu" + id + "'></div>");
		$("#book_menu" + id).append("<span class='field'>分野</span>");
		$("#book_menu" + id).append("<span class='address'>アドレス</span>");
		$("#book_menu" + id).append("<span class='library'>蔵書番号</span>");
		$("#book_menu" + id).append("<span class='state'>資料状態</span>");
		$("#book_menu" + id).append("<span class='reservation'>予約</span>");
		$("#book_menu" + id).css("display", "none");
		//図書データ
		var menu = ["field", "address", "library", "state", "reservation"];
		for(j = 0; j < data[i].length; j++){
			$("#book_area" + id).append("<div class='book_data' id='book_data" + id + "_" + j + "'><div>");
			if(data[i][j]["state"] == "保管") $("#book_data" + id + "_" + j).css("background-color", "#b0ffff");
			else if(data[i][j]["state"] == "禁帯") $("#book_data" + id + "_" + j).css("background-color", "#b0ffb0");
			else $("#book_data" + id + "_" + j).css("background-color", "#ffb0b0");

			for(k = 0; k < menu.length; k++){
				$("#book_data" + id + "_" + j).append("<span class='" + menu[k] + "' id='" + menu[k] + id + "_"+ j + "'>" + data[i][j][menu[k]] + "</span>");
				if(data[i][j][menu[k]] == "　") $("#" + menu[k] + id + "_" + j).css("background-color", "#a0a0a0");
			}
			$("#book_data" + id + "_" + j).css("display", "none");
		}
		//書誌番号
		$("#book_area" + id).append("<div class='bibliography_area' id='bibliography_area" + id + "'></div>");
		$("#bibliography_area" + id).append("<span class='bibliography'>書誌番号</span>");
		$("#bibliography_area" + id).append("<span class='bibliography_data'>" + data[i][0]["bibliography"] + "</span>");
		$("#bibliography_area" + id).css("display", "none");

		//関連図書
		if(data[i][0]["relation"][0] != null){
			$("#book_area" + id).append("<div class='bibliography_area' id='relation_area" + id + "'></div>");
			$("#relation_area" + id).append("<div class='bibliography'>関連図書</div>");
			for(k = 0; k < data[i][0]["relation"].length; k++){
				$("#relation_area" + id).append("<div class='relation_data'><a href='showrelation.php?id=" + data[i][0]["relation"][k]["id"] + "' class='booklink' target='_blank'>" + data[i][0]["relation"][k]["title"] + "</a></div>");
			}
		}
		$("#book_area" + id).append("<div id='detail_area" + id + "' class='detail_area'></div>");
		$("#detail_area" + id).append("<input type='button' id='" + id + "_" + j + "' class='detail' value='詳細'>");
		

	}
	if(i == send["LN"]){
		$("#show_area").append("<div id='next_area' class='next_area'></div>");
		$("#next_area").append("<input type='button' id='next_button' class='next_button' value='さらに表示'>");
	}		
}