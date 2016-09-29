<html>
<head>
	<title>Tic Tac Toe</title>
	<link rel="stylesheet" type="text/css" href="tictactoe.css" />
	<script type="text/javascript" src="tictactoe.js"></script>
</head>

<body>
<h1>Tic Tac Toe</h1>
<p>Just fill the gaps with a circle or a cross.</p>

<h2>Available sessions ready to play</h2>

<?php
# tancament de sessió
if( isset($_GET['sortir']) ) {
	session_start();
	session_regenerate_id();
	echo "<p>Has sortit de la sessió.</p>\n";
	echo "<p><a href='index.php'>Torna-hi ;)</a></p>\n";
	echo "</body></html>";
	die();
}

session_start();

$ses = session_id();
if( $ses>0 && $ses<10 ) {
	# partida en marxa
	juga_partida();
} else {
	# acaba de triar sessió
	if( isset($_GET["choose_session"]) ) {
		$ses = $_GET["choose_session"];
		echo "<p>You selected session $ses</p>\n";
		session_write_close();
		$ret = session_id($_GET["choose_session"]);
		session_start();
		juga_partida();
	} else {
		# oferim sessions a triar
		echo "<ul>\n";
		for($i=1;$i<10;$i++) {
			echo "<li><a href='index.php?choose_session=$i'>Session $i</a></li>\n";
		}
		echo "</ul>\n";
	}
}

function juga_partida() {
	echo "<p>Sessió: ".session_id()."</p>\n";
	echo "<p><a href='?sortir=1'>Sortir de la sessió</a></p>";
}

?>

</body>

</html>
