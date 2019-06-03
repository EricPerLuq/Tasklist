
<html>
<head>
	<title></title>
</head>
<body>
	<?php
  //conexion en PDO
      $database = parse_url(getenv("DATABASE_URL"));
      $db = new PDO("pgsql:" . sprintf(
        "host=%s;port=%s;user=%s;password=%s;dbname=%s",
        $database["host"],
        $database["port"],
        $database["user"],
        $database["pass"],
        ltrim($database["path"], "/")
      ));
	if(isset($_GET['Hecho'])){
		$query = $db->prepare("UPDATE TaskList SET Estado = 1 WHERE id = ".$_GET['Hecho'].";");
		$query->execute();
	}else if(isset($_GET['Pendiente'])){
		$query = $db->prepare("UPDATE TaskList SET Estado = 0 WHERE id = ".$_GET['Pendiente'].";");
		$query->execute();
	}else if(isset($_GET['Borrar'])){
		$query = $db->prepare("DELETE FROM TaskList WHERE id = ".$_GET['Borrar'].";");
		$query->execute();
	}else if(isset($_GET['NuevaTarea'])){
		$query = $db->prepare("INSERT INTO TaskList (Tarea,Estado) VALUES ('".$_GET['NuevaTarea']."',0)");
		if (!$query) {
    		echo "\nPDO::errorInfo():\n";
    		print_r($dbh->errorInfo());
		}
		$query->execute();
	}
	$Pendiente = $db->query("SELECT * FROM tasklist WHERE Estado = 0");
	$Hecho = $db->query("SELECT * FROM tasklist WHERE Estado = 1");
	?>
	<h2>TaskList</h2>
		<form action="#" method="GET">
			<input type="text" name="NuevaTarea"> <input type="submit" value="Insert New">
		</form>
		<br><br>
		<b>Tareas por hacer: <br></b>
	<ul>
		<?
		foreach($Pendiente as $row){
			echo "<li>" . $row['Tarea']." <a href='?Hecho=".$row['id']."'>Hecho</a> <a href='?Borrar=".$row['id']."'>Borrar</a></li><br>";
		}
?>
	</ul>

		<b>Tareas hechas: <br></b>
	<ul>
		<?
		foreach($Hecho as $row){
			echo "<li>" . $row['Tarea']." <a href='?Pendiente=".$row['id']."'>Pendiente</a> <a href='?Borrar=".$row['id']."'>Borrar</a></li><br>";
		}
?>
	</ul>
</body>
</html>
