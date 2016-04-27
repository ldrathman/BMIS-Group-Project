<!DOCTYPE html PUBLIC ' -//W3C//DTD  XHTML  1.0  Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml xmlns = "http://w3.org/1999/ xhtml" xml:land="en" lan ="en">
<head>
<body>
<meta http-equiv="content-type"
		content ="text/html; charset=utf-8" />
		<title> Add a Print</title>
</head>
<body>
<?php # Script 19.2 - add_print.php
//This page allows the administrator to add a print (product). 

require('ecommerce/mysqli_connect.php'); 

if($_SERVER['REQUEST_METHOD'] == 'POST')
{//Handle the form.

//Validate the incoming data...

		$errors= array(); 
		
//Check for a print name:
	if(!empty($_POST['print_name']))
	{
		$pn = trim($_POST['print_name']) 
	}else{
	$errors[] = 'Please enter the print\'s name!';
		}
//Check for an image:
	if(is_uploaded_file ($_FILES['image']['tmp_name']); 
	{
	//Create a temporary file name:
	$temp = '../../uploads/' . mds($_FILES['image']['name']); 
	}
	//Move the file over:
	if(move_uploaded_file($_FILES['image']['tmp_name'], $temp)){
		echo "<p>The file has been uploaded!</p>"; 
		
	//Set the $i variable to the image's name:
	$i = $_FILES['image']['name']; 
	} else{//Couldn't move the file over
		$errors[] = "The file could not be moved."; 
		$temp = $_FILES['image']['tmp_name']; 
		}
	
	//Check for a size (not required):
	$s = (!empty($_POSRT['size']))) ? trim($_POST['size']) : NULL; 
	
	//Check for a price: 
	if (is_numeric($_POST['price'])&&($_POST['price'] > 0)) {
	$p = (float) $_POST['price']; 
	} else{
	$errors[] = "Please enter the print\'s price!";
	}
	
	//Check for a description (not required):
	$d = (!empty($_POST['description'])) ? trim($_POST['description']) : NULL; 
	
	//Validate the artist...
	if(isset($_POST['artist'])&& filter_var($_POST['artist'], FILTER_VALIDATE_INT, array('min_range' => 1)) ) { 
	$a = $_POST['artist']; 
	}
	else {//No artist selected. 
		$errors[] = "Please select the print\'s artist!";
		}
	
	if(empty($errors)) {//If everything's OK.
	//Add the print to the database:
	$q = 'INSERT INTO prints (artist_id, print_name, price, size, description, image_name)
	VALUES(?, ?, ?, ?, ?, ?)'; 
	$stmt = mysqli_prepare($dbc, $q); 
	mysqli_stmt_bind_param($stmt, 'isdsss', $a, $pn, $p, $s, $d, $1); 
	mysqli_stmt_execute($stmt); 
	}
	//Check the results...
	if(mysqli_stmt_affected_rows($stmt) ==1) {
	
	//Print a message:
	echo "<p>The print has been added.</p>";
	
	//Rename the image: 
	$id=mysqli_stmt_insert_id($stmt); //Get the Print ID
	rename ($temp, '../../uploads/$id'); 
	
	//Clear $_POST:
	$_POST = array(); 
	}else{//Error!
		echo "<p style='font-weight:bold; color: #COO'> Your Submission could not be processed due to a system error</p>";
		}
	mysqli_stmt_close($stmt); 
	}
	//End of $errors IF
	
	//Delete the uploaded file if it still exists:
	if(isset($temp)&& file_exists($temp)&& is_file($temp) ) {
	unlink($temp); 
	}
} //End of submission IF

//Check for any errors and print them:
if(!empty($errors) && is_array($errors) ) {
	echo '<h1>Error!</h1>
	<p style='font-weight: bold; color: #COO'> The following error(s) occured: <br/>';
	foreach ($errors as $msg){
		echo "- $msg<br/>\n"; 
		}
		echo "Please reselect the print image and try again </p>";
		}
		//display the form...
?>
		
<h1> Add a Print </h1>
	<form enctype="multipart/form-data" action="add_print.php" method="post">
	
	<input type="hidden" name="MAX_FILE_SIZE" value = "524288"/>
	
	<fieldset><legend>Fill out the form to add a print to the catalog:</legend>
	
	<p><b>Print nameL</b> <input type="text" name="print_name" size="30" maxlength="60"
	value="<?php if (isset($_POST['print_name'])) echo htmlspecialchars($_POST['print_name']); ?>"
	/></p>
	
	<p><b>Artist:</b>
	<select name="artist"><option>Select One</option>
	<?php //Retrieve all the artists and add to the pull-down menu.
	$q = "SELECT artist_id, CONCAT_WS(' ', first_name, middle_name, last_name) FROM artists ORDER BY last_name, first_name ASC"; 
	$r = mysqli_query($dbc, $q); 
	if(mysqli_num_rows($r) > 0) {
		while($row = mysqli_fetch_array ($r, MYSQLI_NUM)) 
		{
			echo "<option value=\'$row[0]\'"; 
			//Check for stickyness:
			if(isset($_POST['existing'])&&($_POST['existing'] ==$row[0]) ) 
				echo "selected='selected'"; 
			echo ">$row[1]</option>\n"; 
		}
		else
		{
		echo "<option> Please add a new artist first</option>";
		}
		mysqli_close($dbc); //Close the database connection.
?>
		</select></p>
		
		<p><b>Price:</b> <input type="text" name="price" size="10" maxlength="10" value="<?php if
		(isset($_POST['price'])) echo $_POST['price']; ?>" /> <small> Do not include the dollar sign or commas.</small></p>
		
		<p><b>Description:</b> <textarea name="description" cols="40" rows="5"> <?php if (isset($_POST['description']))
		echo $_POST['description']; ?></textarea> (optional)</p>
		
		</fieldset>
		
		<div align="center"><input type="submit" name="submit" value="Submit" /></div>
</form>
	
</body>
</html>
	
	
			
		
		