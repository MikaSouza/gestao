<?php
class NFseSOAP {
    public function envia($dados,$lote,$empresa = 1) {
			try {
        //$URLWebservice = 'https://nfse-hom.procempa.com.br/bhiss-ws/nfse?wsdl';
        $URLWebservice = 'https://nfe.portoalegre.rs.gov.br/bhiss-ws/nfse?wsdl';
        switch ($empresa) {
					default:
					case '1':
					case '5':
            $key = getcwd().'/marpa_public_private.pem';
          break;
          case '3':
          case '6':
            $key = getcwd().'/virtual_public_private.pem';
          break;
					case '4':
						$key = getcwd().'/tributario_public_private.pem';
					break;
				}
        //echo $key;exit();

        $options = array(
            'soap_version'=>SOAP_1_1,
            'exceptions'=>true,
            'trace'=>true,
            'cache_wsdl'=>WSDL_CACHE_MEMORY,
            'local_cert'=>$key,
            'passphrase'   => '15011221',
            'https' => array(
                'curl_verify_ssl_peer'  => true,
                'curl_verify_ssl_host'  => true
            )
          );
        //echo'<pre>';echo htmlspecialchars($data);
        $client = new SoapClient($URLWebservice, $options);
       } catch (Exception $e) {
    	   echo'<pre>';var_dump($e);
			 }
       //$client = new SoapClient(null, $options);
       //exit('foi');
       $options = array(
         'nfseCabecMsg'=>$dados[0],
         'nfseDadosMsg'=>$dados[1]
       );
       //echo 'Functions: <pre>';var_dump($client->__getFunctions());echo '</pre>';exit();
       $retorno = $client->RecepcionarLoteRps($options);
			echo '<pre>';var_dump($retorno);
      return $retorno;
      //echo "<pre>" . var_dump(htmlspecialchars($client->__getLastRequest()));
      //echo '<pre>'.htmlspecialchars($dados[1]);
      //echo "<pre>" . $client->__getLastResponse();
    }

}

?>