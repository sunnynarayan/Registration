<html>
	<head>
		<title>Registration</title>
		<link rel="stylesheet" href="style.css" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="script.js"></script>
	</head>
	<body>
		<div class="main">
			<h1>Registration</h1><br/>
			<hr id="line1">
			<form id="userDetails" action="" method="post" enctype="multipart/form-data">
				<label for = "FName"><span>First Name : </span></label> <input required id="FirstN" name = "FirstN" type="text"  autofocus><br>
				<label for = "LName"> <span>Last Name : </span></label> <input id="LastN" name = "LastN" required type="text"><br>
			 	<label for = "Mobile"><span> Mobile : </span></label><input required id = "mobile" name = "mobile" type="tel"><br>
			 	<label for = "Email"><span> Email : </span></label> <input required id = "Email" name = "email" type="email"><br>
			 	<label for = "Photo"><span> Photo(png/ jpg/ jpeg) : </span></label><input id = "pic"required name = "pic" type="file"><br>
			 	<label for = "Resume"><span> Resume(pdf) : </span></label> <input class="inp" required id = "resume" name = "resume" type="file"><br>
				<hr id="line2">
				<input type="submit" value="Upload" id="submit"/> 
	</form>
	</div>
	<div id="message"></div>
	</body>
</html>
