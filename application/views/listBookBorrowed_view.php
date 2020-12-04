<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sách của tôi</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/login.css?ver=<?php echo rand(111,999)?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>

	<?php if(!isset($_SESSION['user_id'])){
		redirect(base_url() . 'Login','refresh');} ?>


	<?php
        if(isset($_SESSION['user_id'])){
          if($_SESSION['permission'] == 0){
            require('headerAfterLogin.php');
          } else {
            header('Location: controller/admin');
          }
        } else {
          require('header.php');
        }
    ?>

    <div style="margin: 50px">
    	<h2>Danh sách sách đã mượn</h2>
    </div>

    <div id="books_borrow" style="overflow-x:auto;width:90%;margin:70px">
    	<table id="table" class="search-table">
    		<tr>
    			<th class="title">Title</th>
    			<th class="author">Authors</th>
    			<th>Language</th>
    			<th>Date Borrow</th>
    			<th>Due Date</th>
    		</tr>
    		<?php foreach ($danhsachsachmuon as $key => $value): ?>
                <?php
                    $userTimezone = new DateTimeZone('Asia/Ho_Chi_Minh');
                    $date= new DateTime($value['due_date'], $userTimezone);
                    $now = new DateTime('NOW', $userTimezone);
                    $interval = $date->diff($now);
                    $day = $interval->d;
                    $hour = $interval->h;
                    if($now > $date)
                        $str = 'Đã quá hạn';
                    else{
                        $str1 = ' days ';
                        $str2 = ' hours';
                        if($day == 1)
                            $str1 = ' day ';

                        if($hour == 1)
                            $str2 = ' hour';
                        $str = $day . $str1 . $hour . $str2;
                    }                    
                    
                ?>
    			<tr id="<?= $value['borrow_id']?>">
    				<td class="title"><?= $value['title']?></td>
                    <td class="author"><?= $value['authors']?></td>
                    <td><?= $value['publication_date']?></td>
                    <td><?= $value['date_borrow']?></td>
                    <td><?php echo $str?></td>
    			</tr>
    			
    		<?php endforeach ?>
    		
    	</table>

    	<div>
	    	<button id="btn_back" name="back" onclick="window.location.href='<?php echo base_url() ?>'">BACK</button>
	    </div>
    </div>

    

    <script>
    	function returnBook(book) {
    		var id = book.id;   	
    		var td = book.getElementsByTagName('td');
    		var ask = window.confirm("Are you sure to return " + td[0].innerHTML);
    		if (ask) {
			    $.ajax({
	    			url: 'MyBooks/returnBook',
	    			type: 'POST',
	    			dataType: 'json',
	    			data: {borrow_id: id},
	    		})
	    		.done(function() {
	    			console.log("success");
	    		})
	    		.fail(function() {
	    			console.log("error");
	    		})
	    		.always(function() {
	    			console.log("complete");
	    			$('#' + id).remove();
	    		});
			} else {
			}
    			
    		
    	}
    </script>
	
</body>
</html>