<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'teraware_db.php';
require_once 'import.php';
require_once 'client.php';

if(!isset($_SESSION))
    session_start();

$_SESSION['SI_USUCODIGO'] = 1;
$db = new Import();

/* dados nivel 0 - faz uma vez e não precisa atualizar
- estados
- cidades
- usuarios
- vendedores
- marpabancoag

*/

//  tabelas
/*  
- marpacliente
- marpaclientehist
- marpaclienteobs 
- marpaclienteobscob 
- marpaclienterel
- marpaetiqueta
- marpaetiquetaerro
*/

/*
marpaprospeccao
marpaindicacao
*/

/*
marpaab
marpaca
marpafolha */


marpafin
marpafinhist
marpafinpc
marpafinpchist
marparetorno
marpafinca
marpacahist
marpafolha
marpafolhahist

marpagold
marpagoldcli
marpagoldclihist


/*
marpaprocesso
marpaprocessoprz
marpaprocessoprazo
marpaprocessoprzhist
marpaprocessoprazohist
marpaproccartapasta
marpaproccartas
marpapatente 
marpapatenteprz


*/


/*
marpaagenda
marpavisita*/



// prospeccao / indicao 

// guias

// averbacao
// averbacao andamento
// registro produto
// programa de computador
// marcas
// patentes

// clientes abs
// clientes cas
// clientes folhas


$data = $db->db_query("SELECT sigla FROM marpacliente
							where codigovendedor = 221    ");
if (count($data) == 0) {
	die;
}

foreach ($data as $cli) {
	$insert = new Client($cli['sigla']);
	print_r($insert->getClicodigo());
	echo "\n";
}


?>