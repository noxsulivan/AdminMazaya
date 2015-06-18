<?
include('ini.php');


		$sql = "select * from facebook group by email";
		$db->query($sql);
		echo "<h2>inicial: 1 | final: ".$db->rows."</h2>";
		
		echo "<table>";
		while($res = $db->fetch()){

			echo "<tr><td>".++$i."</td><td>".$res['email']."</td></tr>";
					
		}
		echo "</table>";
					
					
?>