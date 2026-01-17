<style>
#MnihlaTable {
 font-family: 'Droid Arabic Kufi',Tahoma, Geneva, sans-serif !important;
  border-collapse: collapse;
  width: 50%;
	margin: 0 auto;
   
}

#MnihlaTable td, #MnihlaTable th {
  border: 1px solid #ddd;
  padding: 8px;
}

#MnihlaTable tr:nth-child(even){background-color: #f2f2f2;}

#MnihlaTable tr:hover {background-color: #ddd;}

#MnihlaTable th {
	padding-top: 12px;
	padding-bottom: 12px;
	text-align: center;
	background-color: #09F;
	color: white;
}
.form-style .form-group
{
	display:inline-block;
	margin-bottom:0;
	vertical-align:middle;
	padding:10px;
}
.letableau
{
	width:50%;
	border: solid #CCC;
	text-align:center;
	margin: 0 auto;
	
}
</style>
<?php


$conn = new mysqli("cjemmeltafyu.mysql.db", "cjemmeltafyu", "Yu85AiuO","cjemmeltafyu");
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sql = "SELECT * FROM `u2016_permibatir` WHERE `greeting` LIKE '".$_GET["numdossier"]."' AND `cin` LIKE '".$_GET["cin"]."' ORDER BY `cin` DESC ";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$sql2 = "SELECT * FROM `u2016_categories` WHERE `id` = ".$row["catid"];
$result2 = $conn->query($sql2);
$row2 = $result2->fetch_assoc();

 
 
  echo '<table id="MnihlaTable">';
		echo '<tr>';
    		echo '<td>N° Unique</td>';
    		echo '<td>' . $row["id"]. '</td>';				
 		echo '</tr>';
		 echo '<tr>';
    		echo '<td>N° Dossier</td>';
    		echo '<td>' . $row["greeting"]. '</td>';				
 		echo '</tr>';
		 echo '<tr>';
    		echo '<td>Nom et Prénom</td>';
    		echo '<td>' . $row["name"]. '</td>';				
 		echo '</tr>';
		 echo '<tr>';
    		echo '<td>CIN</td>';
    		echo '<td>' . $row["cin"]. '</td>';				
 		echo '</tr>';			
		 echo '<tr>';
    		echo '<td>Type</td>';
    		echo '<td>' . $row["typebatir"]. '</td>';				
 		echo '</tr>';
		
		 echo '<tr>';
    		echo '<td>ingénieur</td>';
    		echo '<td >' . $row["ingenieur"]. '</td>';				
 		echo '</tr>';
		
		 echo '<tr>';
    		echo '<td>Resultat</td>';
    		echo '<td style="color:#F00;">' . $row2["title"]. '</td>';				
 		echo '</tr>';
		 
		 echo '</table>';
		 echo '<br>';
 
 
  
echo '<div align="center"><input type="button" value="Imprimer" onClick="window.print()"></div>';

 echo '<div align="center"><a type="button" href="http://c-jemmel.tn/index.php/ar/alalaka-maa-al-mouwatin/moutabaat-roukhas-al-binaa" >Retour</a></div>';

 