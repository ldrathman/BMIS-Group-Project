<?php # Script 19.6 - browse_prints.php
// This page displays the available prints (products).

// Set the page title and include the HTML header:
$page_title = 'Browse the Prints';
include ('includes/header.html');

require ('../mysqli_connect.php');

// default query for this page:
$q = "SELECT artists.artist_id, CONCAT_WS(' ', first_name, middle_name, last_name) AS artist,
print_name, price, description, print_id FROM artists, prints WHERE artists.artist_id = prints.
artit_id ORDER BY artists.last_name ASC, prints.print_name ASC";

// Are we looking at a particular artist
if (isset($_GET['aid']) && filter_var($_GET['aid'], FILTER_VALIDATE_INT, array('min_range' => 1)) ) {
		// Overwrite the query:
		$q = "SELECT artists.artists_id, CONCAT_WS(' ', first_name, middle_name, last_name) AS artist,
		print_name, price, description, print_id FROM artists, prints WHERE artists.artist_id = prints.
		artist_id AND prints.artists_id={$_GET['aid']} ORDER BY prints.print_name";
}

// Create the table head:
echo "<table border='0' width='90%'' cellspacing='3' align='center'>
	echo "<tr>";
	echo "<td align='left' width='20%'><b>Artist</b></td>";
	echo "<td align='left' width='20%'><b>Print Name</b></td>";
	echo "<td align='left' width='20%'><b>Description</b></td>";
	echo "<td align='left' width='20%'><b>Price</b></td>";
	echo "</tr>";

// Display all the prints, linked to URLs:	
$r = mysqli_query ($dbc, $q);
while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {

	echo "\t<tr>";
	echo "<td align=\'left\'><a href=\'browse_prints.php?aid={$row['print_id']}\'>{$row['artist']}";
	echo "</a></td>";
	echo "<td align=\'left\'><a href=\'view_print.php?pid={$row['print_id']}\'>{$row['print_name']}</td>";
	echo"<td align=\'left\'>{$row['description']}</td>";
	echo"<td align=\'right\'>\${row['price']}</td>";
	echo"</tr>\n";
	
} //End of while loop.

echo "</table>";
mysqli_close($dbc);
include ('includes/gooter.html');
?>
