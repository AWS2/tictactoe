<?php

define("JUGADOR","JUGADOR");
define("SESID","SESID");
define("BLANK_TOKEN"," ");
define("O_TOKEN","O");
define("X_TOKEN","X");
# cookies expiren als 5'
$expire = time()+60*5;

# la gestió de la sessió cal fer-la abans der començar el HTML
session_start();
# tancament de sessió
if( isset($_GET['sortir']) ) {
	# esborra dades
	session_destroy();
	# engega sessió nova
	session_start();
	session_regenerate_id();
	# esborra cookies
	setcookie(JUGADOR,NULL,-1);
	setcookie(SESID,NULL,-1);
}

# if( sesid>0 && sesid<10 )
if( isset($_GET["choose_session"]) ) {
	$ses = $_GET["choose_session"];
	#echo "<p>You selected session $ses</p>\n";
	session_write_close();
	$ret = session_id($_GET["choose_session"]);
	session_start();
} else if( isset($_SESSION["njugadors"]) && $_SESSION["njugadors"]>=2 && !isset($_COOKIE[JUGADOR]) ) {
	# sessió plena
	session_regenerate_id();
}
# inicialitzem sessió i assignem tokens O / X
$init = "";
if( session_id()>0 && session_id()<10 )
if( !isset($_SESSION["t11"]) ) {
	$_SESSION["t11"] = BLANK_TOKEN;
	$_SESSION["t12"] = BLANK_TOKEN;
	$_SESSION["t13"] = BLANK_TOKEN;
	$_SESSION["t21"] = BLANK_TOKEN;
	$_SESSION["t22"] = BLANK_TOKEN;
	$_SESSION["t23"] = BLANK_TOKEN;
	$_SESSION["t31"] = BLANK_TOKEN;
	$_SESSION["t32"] = BLANK_TOKEN;
	$_SESSION["t33"] = BLANK_TOKEN;
	$_SESSION["njugadors"] = 1;
	setcookie( JUGADOR, O_TOKEN ); #, $expire );
	setcookie( SESID, session_id()); #, $expire );
	global $init;
	$init = O_TOKEN;
} elseif( !isset($_COOKIE[JUGADOR]) && $_SESSION["njugadors"]==1 ) {
	setcookie( JUGADOR, X_TOKEN ); #, $expire) );
	setcookie( SESID, session_id() ); #, $expire );
	$_SESSION["njugadors"]=2;
	global $init;
	$init = X_TOKEN;
}

echo "INIT $init<br>";

?>

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

if( isset($_GET['sortir']) ) {
	echo "<p>Has sortit de la sessió.</p>\n";
	echo "<p><a href='index.php'>Torna-hi ;)</a></p>\n";
	echo "</body></html>";
	die();
}

juga_partida();

function juga_partida() {
	# missatges de estat de la partida
	status_partida();
	check_accio();
	//TODO:check_final();
	render_partida();
}

function status_partida() {

	echo "<p>ID: ".session_id()."</p>\n";

	# inicialització
	global $init;
	if( $init ) {
		echo "<p>Iniciant partida</p>\n";
		echo "<p>Assignat jugador $init</p>";
		echo "<a href='index.php'>Comença la partida</a>\n";
		echo "</body></html>";
		die();
	}
	$ses = session_id();
	if( $ses>0 && $ses<10 ) {
		if( isset($_COOKIE["SESID"]) && $_COOKIE[SESID]==session_id() ) {
			# partida
			echo "<p>Jugant partida: ".session_id()."</p>\n";
			echo "<p>Ets el jugador ".$_COOKIE[JUGADOR]."</p>\n";
			echo "<p><a href='?sortir=1'>Abandonar partida</a></p>\n";
		} else {
			# encara no te sessió o és un intrús (SESID!=session_id)
			# oferim sessions a triar
			echo "<p>Sense sessió o intrús</p>\n";
			menu();
		}
	} else {
		# sessió no inicialitzada
		echo "<p>Sessió no inicialitzada</p>\n";
		menu();
	}
}

function menu() {
	echo "<ul>\n";
	for($i=1;$i<10;$i++) {
		echo "<li><a href='index.php?choose_session=$i'>Session $i</a></li>\n";
	}
	echo "</ul>\n";
	echo "</body></html>";
	die();
}

function check_accio() {
	if( isset($_GET["cela"]) ) {
		$cela = $_GET["cela"];
		$token = $_COOKIE[JUGADOR];
		echo "<p>Has clicat ".$cela."</p>\n";
		$_SESSION[$cela] = $token;
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
