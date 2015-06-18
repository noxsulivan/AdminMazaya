<?


include "../inc/classes.php";
include "../inc/funcoes.php";
include "../inc/conecta.php";
include "../inc/aut.php";


	$admin->ini_formulario();
	$admin->campo_simples('Fabricante', 'fabricante',$res["fabricante"]);
	$admin->end_formulario();
?>