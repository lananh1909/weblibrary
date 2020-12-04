<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Trang chủ</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/login.css?ver=<?php echo rand(111,999)?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>

	<?php
        if(isset($_SESSION['user_id'])){
            require('headerAfterLogin.php');
        } else {
          require('header.php');
        }
      ?>

      <section>
        <div id="search-container">
	        <div style="margin-left:150px;">
	            <input id="input-search" type="text" placeholder="Search.." name="search">
	            <button id="search-btn" type="submit" onClick="showResult()"><i class="fa fa-search"></i></button>
	        </div>
	    </div>

    
        <div id="search_result" style="overflow-x:auto;width:90%;margin:70px">
            <table id="table" class="search-table">
                <tr>
                    <th class="title">Title</th>
                    <th class="author">Authors</th>
                    <th>Language</th>
                    <th>Publication date</th>
                    <th></th>
                    <th></th>
                </tr>

                <?php foreach ($allbooks as $key => $value):?>

                <tr id="<?= $value['book_id']?>">
                    <td class="title"><?= $value['title']?></td>
                    <td class="author"><?= $value['authors']?></</td>
                    <td><?= $value['language_name']?></</td>
                    <td><?= $value['publication_date']?></</td>
                    <td style="text-align: center">
                        <button style="padding: 3px" onclick="addBorrow(this.parentNode.parentNode)"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </td>

                    <td>
                        <button style="padding: 3px;display: inline;" onclick="myFunc(this.parentNode.parentNode)"><i class="fa fa-info-circle" aria-hidden="true"></i></button>
                    </td>
                        
                    
                </tr>

                <?php endforeach ?>

            </table>
        </div>  
        <div id="action">
            <button id="btn_back" onclick="showResult()">BACK</button>
            <button id="btn_add" onclick="window.location.href='<?php base_url() ?>MuonSach'">ADD</button>
        </div>
      </section>

      <<script>
      	function myFunc(row){
      		var book_id = row.id;
      		var post;
            $.ajax({
            	url: 'homePage/getBookDetail',
            	type: 'POST',
            	dataType: 'json',
            	data: {id: book_id},
            })
            .done(function(data) {
            	console.log(data);
            	post = data[0];
            	var form = '<form action="#" method="post">';
            	form += '<div class="imgcontainer">';
            	form += '<h2>Thông tin sách</h2> </div>';
            	form += '<div class="container">';
            	form += '<label for="book_id"><b>Book Id</b></label>';
            	form += '<input type="text" name="book_id" value="';
            	form += post.book_id + '"readonly>';
            	form += '<label for="title"><b>Title</b></label>';
            	form += '<input type="text" name="title" value="' + post.title + '" readonly>';
            	form += '<label for="authors"><b>Authors</b></label>';
            	form += '<input type="text" name="authors" value="'+ post.authors + '" readonly>';
            	form += '<label for="language"><b>Language</b></label>';
            	form += '<input type="text" name="language" value="' + post.language_name + '" readonly>';
            	form += '<label for="num_pages"><b>Num Pages</b></label>';
            	form += '<input type="text" name="num_pages" value="'+ post.num_page + '" readonly>';
            	form += '<label for="rating"><b>Rating</b></label>';
            	form += '<input type="text" name="rating" value="'+ post.rating + '" readonly>';
            	form += '<label for="publication_date"><b>Publication Date</b></label>';
            	form += '<input type="text" name="publication_date" value="'+ post.publication_date + '" readonly>';
            	form += '<label for="publisher"><b>Publisher</b></label>';
            	form += '<input type="text" name="publisher" value="'+ post.publisher + '" readonly>';
            	form += '</div>';
            	form += '</form>';

            	$('#search_result').html(form);
            	$('#action').show();


            })
            .fail(function(data) {
            	console.log("error");
            	console.log(data);

            })
            .always(function() {
            	console.log("complete");
            });
            
        }
      </script>


      <script>

        function showResult() {
        	var key = document.getElementById("input-search").value;
            $.ajax({
            	url: 'homePage/findBook',
            	type: 'POST',
            	dataType: 'json',
            	data: {keyword: key},
            })
            .done(function(data) {
            	console.log("success");
            	var table = '<table id="table" class="search-table">';
            	table += '<tr>';
            	table += '<th class="title">Title</th>';
            	table += '<th class="author">Authors</th>';
            	table += '<th>Language</th>';
            	table += '<th>Publication date</th>';
                table += '<th></th>';
                table += '<th></th>';

            	data.forEach(function sumElement(element){
				    table += '<tr id="' +element.book_id +'">';
	            	table += '<td class="title">'+ element.title +'</td>';
	            	table += '<td class="author">'+ element.authors +'</td>';
	            	table += '<td>'+ element.language_name +'</</td>';
	            	table += '<td>'+ element.publication_date +'</</td>';
                    table += '<td style="text-align: center">';
                    table += '<button style="padding: 3px" onclick="addBorrow(this.parentNode.parentNode)"><i class="fa fa-plus" aria-hidden="true"></i></button>';
                    table += '</td>';
                    table += '<td style="text-align: center">';
                    table += '<button style="padding: 3px" onclick="myFunc(this.parentNode.parentNode)"><i class="fa fa-info-circle" aria-hidden="true"></i></button>';
                    table += '</td>';
	            	table += '</tr>';
				});
				table += '</table>';
				$('#search_result').html(table);
            	$('#action').hide();
            })
            .fail(function() {
            	console.log("error");
            })
            .always(function() {
            	console.log("complete");
                console.log(key);
            });
            
        }
    </script>

    <script>
        function addBorrow(row) {
            var row_id = row.id;
            $.ajax({
                url: 'homePage/getBookDetail',
                type: 'POST',
                dataType: 'json',
                data: {id: row_id},
            })
            .done(function() {
                console.log("success");
                window.location.href='<?php echo base_url() ?>MuonSach';
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
            
        }
    </script>
	
</body>
</html>

            