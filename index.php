<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>kit library search</title>
		<link rel="stylesheet" type="text/css" href="css/homedesign.css">
		<!--<script type="text/javascript" src="js/append.js"></script>-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	</head>
	<body>
		<div id="headerArea">
			<div class="input_form">
				<select id="option">
					<option selected value="AL">キーワード</optionn>
					<option value="SN">書名</optionn>
					<option value="SA">著者名</optionn>
					<option value="SP">出版社</optionn>
					<option value="SS">件名</optionn>
					<option value="FL">フルタイトル</optionn>
				</select>
				<input type="text" id="keyword">
				<input type="button" id="search" value="検索">
				<input type="hidden" id="CD" value="1">
				<input type="hidden" id="LN" value="10">
			</div>
		</div>
		<div class="show_area" id="show_area">
		</div>
	<script type="text/javascript">

	
	function AppendData(data){

		id = parseInt(data["id"]);
		var title = data["alltitle"].split("/");
		$("#book_area" + id).css("display", "");
		$("#book_area" + id).append("<div class='title'><span class='char_red'>" + title[0] + "</span>/" + title[1] + "</div>");

		$("#book_area" + id).append("<div class='book_menu' id='book_menu" + id + "'></div>");
		$("#book_menu" + id).append("<span class='field'>分野</span>");
		$("#book_menu" + id).append("<span class='address'>アドレス</span>");
		$("#book_menu" + id).append("<span class='library'>蔵書番号</span>");
		$("#book_menu" + id).append("<span class='state'>資料状態</span>");
		$("#book_menu" + id).append("<span class='reservation'>予約</span>");
		$("#book_menu" + id).css("display", "none");

		var menu = ["field", "address", "library", "state", "reservation"];

		for(i = 0; i < data["field"].length; i++){
			$("#book_area" + id).append("<div class='book_data' id='book_data" + id + "_" + i + "'><div>");
			if(data["state"][i] == "保管") $("#book_data" + id + "_" + i).css("background-color", "#b0ffff");
			else if(data["state"][i] == "禁帯") $("#book_data" + id + "_" + i).css("background-color", "#b0ffb0");
			else $("#book_data" + id + "_" + i).css("background-color", "#ffb0b0");

			for(j = 0; j < menu.length; j++){
				$("#book_data" + id + "_" + i).append("<span class='" + menu[j] + "' id='" + menu[j] + id + "_"+ i + "'>" + data[menu[j]][i] + "</span>");
				if(data[menu[j]][i] == "　") $("#" + menu[j] + id + "_" + i).css("background-color", "#a0a0a0");
			}
			$("#book_data" + id + "_" + i).css("display", "none");
		}

		$("#book_area" + id).append("<div class='bibliography_area' id='bibliography_area" + id + "'></div>");
		$("#bibliography_area" + id).append("<span class='bibliography'>書誌番号</span>");
		$("#bibliography_area" + id).append("<span class='bibliography_data'>" + data["bibliography"] + "</span>");
		$("#bibliography_area" + id).css("display", "none");

		/*
		if(data["relation"][0] != null){
			$("#book_area" + id).append("<div class='bibliography_area' id='relation_area" + id + "'></div>");
			$("#relation_area" + id).append("<div class='bibliography'>関連図書</div>");
			for(i = 0; i < data["relation"].length; i++){
				$("#relation_area" + id).append("<div class='relation_data'><a href='showrelation.php?id=" + data["relation"][i]["id"] + "' class='booklink' target='_blank'>" + data["relation"][i]["title"] + "</a></div>");
			}
		}
		*/

		$("#book_area" + id).append("<div id='detail_area" + id + "' class='detail_area'></div>");
		$("#detail_area" + id).append("<input type='button' id='" + id + "_" + j + "' class='detail' value='詳細'>");

	}

	function AppendRelation(relation){
		id = parseInt(relation["id"]);
		if(relation["relation"][0] != null){
			$("#book_area" + id).append("<div class='bibliography_area' id='relation_area" + id + "'></div>");
			$("#relation_area" + id).append("<div class='bibliography'>関連図書</div>");
			for(i = 0; i < relation["relation"].length; i++){
				$("#relation_area" + id).append("<div class='relation_data'><a href='showrelation.php?id=" + relation["relation"][i]["id"] + "' class='booklink' target='_blank'>" + relation["relation"][i]["title"] + "</a></div>");
			}
		}
	}

	function AppendSpace(send){
		var id = send["CD"];
		$("#show_area").append("<div class='book_area' id='book_area" + id + "'></div>");
		$("#book_area" + id).css("display", "none");
	}
	

	function Ajax(send){
		var dfds = [];
		for(i = 0; i < send["LN"]; i++){
			AppendSpace(send);
			$.ajax({
				type: "post",
				url: "receiving.php",
				data: send,
				datatype: "json"

			}).done(function(data){
				if(data){ //図書情報が存在する
					//add
					dfd = $.ajax({
						type: "post",
						url: "relation.php",
						data: {id: data["id"], relation: data["bibliography"]},
						datatype: "json"

					}).done(function(relation){
						AppendRelation(relation);

					}).fail(function(){
						alert("Error : " + error);
						console.log(error + " : " + textStatus + " : " + errorThrown);

					});
					dfds.push(dfd);
					//

					console.log(data);
					AppendData(data);

				}else{
					$("#next_area" + id[0]).css("display", "none");
				}				
	
			}).fail(function(error, textStatus, errorThrown){
				alert("Error : " + error);
				console.log(error + " : " + textStatus + " : " + errorThrown);

			});
			
			send["CD"] = parseInt(send["CD"]) + 1;
		}
		
		$.when.apply($, dfds).done(function(){
			$("#show_area").append("<div id='next_area' class='next_area'></div>");
			$("#next_area").append("<input type='button' id='next_button' class='next_button' value='さらに表示'>");
			$("#search").attr('disabled', false);
		});

	}

	$(function(){
		var send;
		$("#search").click(function(){
			$("#search").attr('disabled', true);
			$("#show_area").empty();
			send = {
				keyword : $("#keyword").val(),
				CD : $("#CD").val(),
				LN : $("#LN").val(),
				RI : $("#option").val()
			};
			Ajax(send);
			
		});
		
		$(document).on("click", "#next_button", function(){
			$("#search").attr('disabled', true);
			$("#next_area").remove();
			Ajax(send);

		});

		$(document).on("click", ".detail", function(){
			var id = $(this).attr("id");
			id = id.split("_");
			var value = $(this).attr("value");
			if(value == "詳細"){
				$(this).attr("value", "閉じる");
				$("#book_menu" + id[0]).css("display", "");
				$("#bibliography_area" + id[0]).css("display", "");
				for(j = 0; j < id[1]; j++){
					$("#book_data" + id[0] + "_" + j).css("display", "");
				}
				
			}else{
				$(this).attr("value", "詳細");
				$("#book_menu" + id[0]).css("display", "none");
				$("#bibliography_area" + id[0]).css("display", "none");
				for(j = 0; j < id[1]; j++){
					$("#book_data" + id[0] + "_" + j).css("display", "none");
				}
			}
		});


	});

	</script>
	</body>
</html>