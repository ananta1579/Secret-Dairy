<?php
    session_start();

    $dairyContent = "";


    if(array_key_exists("id", $_COOKIE)) {
        $_SESSION['id'] = $_COOKIE['id'];
    }

    if(array_key_exists("id", $_SESSION)) {
        // echo "<p>Logged In! <a href='index.php?logout=1'>Log out</a></p>";

        include("connection.php");
       
        $query = "SELECT dairy FROM secretdairy WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";

        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_array($result); 

        $dairyContent = $row['dairy'];
        
    } else {
        header("Location: index.php");
    }

    include("header.php");
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
              rel="stylesheet" 
              integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
              crossorigin="anonymous"> -->

<!-- logout nav bar -->

<nav class="navbar navbar-light bg-faded navbar-fixed-top">
<a href="#" class="navbar-brand" style="margin: 20px">Secret Diary</a>

<div class="pull-xs-right" style="margin:20px">
<a href='index.php?logout=1'><button style="display: inline-block;
        background-color: #7b38d8;
        padding: 10pxpx;
        width: 20pxpx;
        color: #ffffff;
        text-align: center;">Logout</button></a>
</div>


</nav>
<div class="container-fluid">
    <textarea id="dairy" class="form-control" style="width:100%;
	overflow:hidden;
	background-color:#FFF;
	color:#222;
	font-family:Courier, monospace;
	font-weight:normal;
	font-size:24px;
	resize:none;
	line-height:40px;
	padding-left:10px;
	padding-right:10px;
	padding-top:45px;
	padding-bottom:34px;
	background-image:url(https://static.tumblr.com/maopbtg/nBUmgtogx/paper.png);
	background-repeat:repeat;
	-webkit-border-radius:12px;
	border-radius:12px;
	-webkit-box-shadow: 0px 2px 14px #000;
	box-shadow: 0px 2px 14px #000;
	border-top:1px solid #FFF;
	border-bottom:1px solid #FFF;">
    <?php echo $dairyContent; ?>
    </textarea>
</div>
<script>
$("#dairy").bind('input propertychange', function() {
                $.ajax({
                    method: "POST",
                    url: "updatedatabase.php",
                    data: {content: $("#dairy").val()}
                });
            });
 </script>