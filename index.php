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
define("BLANK_TOKEN"," ");
define("O_TOKEN","0");
define("X_TOKEN","X");

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
	inicia_partida();
	check_accio();
	//TODO:check_final();
	render_partida();
}

function inicia_partida() {
	if( !isset($_SESSION["t11"]) ) {
		$_SESSION["t11"] = BLANK_TOKEN;
		$_SESSION["t12"] = BLANK_TOKEN;
		$_SESSION["t13"] = BLANK_TOKEN;
		$_SESSION["t21"] = BLANK_TOKEN;
		$_SESSION["t22"] = BLANK_TOKEN;
		$_SESSION["t23"] = BLANK_TOKEN;
		$_SESSION["t31"] = BLANK_TOKEN;
		$_SESSION["t32"] = BLANK_TOKEN;
		$_SESSION["t33"] = O_TOKEN;
	}	
}

function check_accio() {
	if( isset($_GET["cela"]) ) {
		$cela = $_GET["cela"];
		echo "<p>Has clicat ".$cela."</p>";
		$_SESSION[$cela] = X_TOKEN;
	}
}

function render_partida() {
	echo "<table border=1>\n";
	for($i=1;$i<=3;$i++) {
		echo "<tr>\n";
		for($j=1;$j<=3;$j++) {
			echo "<td>";
			switch( $_SESSION["t$i$j"] ) {
				case O_TOKEN:
					echo "<button disabled=true>O</button>";
					break;
				case X_TOKEN:
					echo "<button disabled=true>X</button>";
					break;
				case BLANK_TOKEN:
					echo "<button onclick='location.href=\"?cela=t$i$j\"'>??</button>";
					break;
			}
			echo "</td>\n";
		}
		echo "</tr>\n";
	}
	echo "</table>\n";
}

?>

</body>

</html>
