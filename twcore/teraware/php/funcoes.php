<?php
    /*************************************************
        Arquivo de funções gerais usadas pelo sistema
    ***************************************************/

/**************  Funções relacionadas a formatar dados  ****************/

    /*************************************************
        Data: 22/10/2009 - Pedro Godinho
        Formatar data e hora do banco no formato brasileiro
        $pData: parâmetro data do banco
    ***************************************************/
    function formatar_data_hora($pData)
    {
        if (empty($pData) or $pData=="//") {
            return "";
        }
        $data = substr($pData, 0, 10);

        $datatrans = explode("-", $data);
        return "$datatrans[2]/$datatrans[1]/$datatrans[0] ".substr($pData, 10, 6);
    }

    //Adiciona dias, meses ou anos em uma data, retorna a nova data
    function addDiaMesAnoData($data, $dias = 0, $meses = 0, $anos = 0)
    {
        $newDate = explode("/", $data);
        $dia = $newDate[0];
        $mes = $newDate[1];
        $ano = $newDate[2];

        $vSDateReturn = date('d/m/Y', mktime(0, 0, 0, $mes + $meses, $dia + $dias, $ano + $anos));

        return $vSDateReturn;
    }

    function formatar_data_banco_edicao($pData)
    {
        $pData = substr($pData, 0, 10);
        $datatrans = explode("-", $pData);
        return "$datatrans[2]/$datatrans[1]/$datatrans[0] ";//.substr($pData,10,6);
    }

    /*************************************************
        Data: 22/10/2009 - Pedro Godinho
        Formatar data e hora do banco no formato brasileiro
        $pData: parâmetro data do banco
    ***************************************************/
    function formatar_data($pData)
    {
        if (empty($pData) or $pData=="//") {
            return "";
        }
        $data = substr($pData, 0, 10);

        $datatrans = explode("-", $data);
        return "$datatrans[2]/$datatrans[1]/$datatrans[0] ";
    }

    /*************************************************
        Data: 22/10/2009 - Pedro Godinho
        Formatar data formato brasileiro para formato do banco
        $pData: parâmetro data formato brasileiro
    ***************************************************/
    function formatar_data_banco($pData)
    {
        return date("Y-m-d", strtotime(str_replace('/', '-', $pData)));
    }

    /*************************************************
        Data: 22/10/2009 - Pedro Godinho
        Formatar CNPJ
        $pCNPJ: parâmetro CNPJ
    ***************************************************/
    function formatar_cnpj($pCNPJ)
    {
        $pCNPJ = filterNumber($pCNPJ);
        $pCNPJ = str_pad($pCNPJ, 14, '0', STR_PAD_LEFT);
        return substr($pCNPJ, 0, 2).".".substr($pCNPJ, 2, 3).".".substr($pCNPJ, 5, 3)."/".substr($pCNPJ, 8, 4)."-".substr($pCNPJ, 12, 2);
    }

    /*************************************************
        Data: 22/10/2009 - Pedro Godinho
        Formatar CPF
        $pCPF: parâmetro CPF
    ***************************************************/
    function formatar_cpf($pCPF)
    {
        $pCPF = filterNumber($pCPF);
        $pCPF = str_pad($pCPF, 11, '0', STR_PAD_LEFT);
        return substr($pCPF, 0, 3).".".substr($pCPF, 3, 3).".".substr($pCPF, 6, 3)."-".substr($pCPF, 9, 4);
    }

    /*************************************************
        Data: 22/10/2009 - Pedro Godinho
        Formatar Telefone
        $pFone: parâmetro telefone
    ***************************************************/
    function formatar_fone($pFone)
    {
        return substr($pFone, 0, 2)."-".substr($pFone, 2);
    }

    /*************************************************
        Data: 22/10/2009 - Pedro Godinho
        Formatar Valor Monetário
        $pMoeda: parâmetro Moeda
        $pSimbolo: parâmetro mostrar simbolo - default Sim
    ***************************************************/
    function formatar_moeda($pMoeda, $pSimbolo = true)
    {
        if ($pMoeda) {
            if ($pSimbolo) {
                return "R$ ".number_format($pMoeda, 2, ',', '.');
            } else {
                return number_format($pMoeda, 2, ',', '.');
            }
        }
    }
    /*************************************************
        Data: 22/10/2009 - Pedro Godinho
        Formatar Valor Monetário
        $pMoeda: parâmetro Moeda
        $pSimbolo: parâmetro mostrar simbolo - default Sim
    ***************************************************/
    function formatar_moeda_4_casas_decimais($pMoeda, $pSimbolo = true)
    {
        if ($pMoeda) {
            if ($pSimbolo) {
                return "R$ ".number_format($pMoeda, 4, ',', '.');
            } else {
                return number_format($pMoeda, 4, ',', '.');
            }
        }
    }

    /*************************************************
        Data: 22/10/2009 - Pedro Godinho
        Formatar CEP
        $pCEP: parâmetro CEP
    ***************************************************/
    function formatar_cep($pCEP)
    {
        if (empty($pCEP)) {
            return null;
        }
        return substr($pCEP, 0, 2).substr($pCEP, 2, 3)."-".substr($pCEP, 5);
    }


/************************************************************************/

/**************  Funções relacionadas a arquivos  ****************/


    /*************************************************
        Data: 22/10/2009 - Pedro Godinho
        Mostrar todos os arquivos de um diretorio
        $pDir: parâmetro diretorio desejado
        $pFormato: parâmetro formato do arquivo - default .php
    ***************************************************/
    function listar_diretorio($pDir, $pFormato = '.php')
    {
        ls_recursive($pDir);
        $dir_array = ls_recursive($pDir, $pFormato);
        return $dir_array;
    }

    /*************************************************
        Data: 22/10/2009 - Pedro Godinho
        Percorrer todos arquivos de um diretorio
        $pDir: parâmetro diretorio desejado
        $pFormato: parâmetro formato do arquivo - default .php
    ***************************************************/
    function ls_recursive($pDir, $pFormato = '.php')
    {
        if (is_dir($pDir)) {
            $dirhandle=opendir($pDir);
            while (($file = readdir($dirhandle)) !== false) {
                if (($file!=".")&&($file!="..")&&(strstr($file, $pFormato))) {
                    $currentfile=$file;//'$dir."/".' ia na frente pra mostrar o diretorio
                    if (!$i) {
                        $i = 0;
                    }
                    $dir_array[$i] = $currentfile;
                    $i++;
                    if (is_dir($currentfile)) {
                        ls_recursive($currentfile);
                    }
                }
            }
        }
        return $dir_array;
    }
/************************************************************************/
  function carregarArquivos($pSArquivo)
  {
      if (move_uploaded_file($pSArquivo['tmp_name'], $pSArquivo['name'])) {
          //echo "Arquivo Carregado com sucesso!";
      } else {
          echo "Falha ao carragar o arquivo";
      }
  }

/************************************************************************/
// criptografia

    function Randomizar($iv_len)
    {
        $iv = '';
        while ($iv_len--> 0) {
            $iv .= chr(mt_rand() & 0xff);
        }
        return $iv;
    }

    function Encriptar($texto, $senha, $iv_len = 16)
    {
        $texto .= "\x13";
        $n = strlen($texto);
        if ($n % 16) {
            $texto .= str_repeat("\0", 16 - ($n % 16));
        }
        $i = 0;
        $Enc_Texto = Randomizar($iv_len);
        $iv = substr($senha ^ $Enc_Texto, 0, 512);
        while ($i < $n) {
            $Bloco = substr($texto, $i, 16) ^ pack('H*', md5($iv));
            $Enc_Texto .= $Bloco;
            $iv = substr($Bloco . $iv, 0, 512) ^ $senha;
            $i += 16;
        }
        return base64_encode($Enc_Texto);
    }

    function Desencriptar($Enc_Texto, $senha, $iv_len = 16)
    {
        $Enc_Texto = base64_decode($Enc_Texto);
        $n = strlen($Enc_Texto);
        $i = $iv_len;
        $texto = '';
        $iv = substr($senha ^ substr($Enc_Texto, 0, $iv_len), 0, 512);
        while ($i < $n) {
            $Bloco = substr($Enc_Texto, $i, 16);
            $texto .= $Bloco ^ pack('H*', md5($iv));
            $iv = substr($Bloco . $iv, 0, 512) ^ $senha;
            $i += 16;
        }
        return preg_replace('/\\x13\\x00*$/', '', $texto);
    }

    function randomizarAleatorio()
    {
        $str = 'abcdefghijlmnopqrs1234567890';
        $misturada = str_shuffle($str);

        // Isto exibirá algo como: bfdaec
        return $misturada;
    }

    /****************************************************************************
    * Verifica Acesso
    * Data: 14/09/2010
    * Raphael Henkel Bohrer
    *
    * Retorna um vetor com valores S - N referente ao acesso
    * de um id-usuário a uma tabela ACESSO
    *
    * $ACESSO[0] = Consulta
    * $ACESSO[1] = Inclusão
    * $ACESSO[2] = Alteração
    * $ACESSO[3] = Exclusão
    * Parametros: ( CODUSUARIO, ACETABELA )
    ******************************************************************************/
    function VerificaAcesso($usuario, $tabela)
    {
        $sqlAcesso = "Select * From ACESSO Where ACEUSUARIO = $usuario and ACETABELA = $tabela";
        //echo $sqlAcesso."<BR>";
        $vConexao = sql_conectar_banco(vGBancoDisplay);
        $RS_ACESSO = sql_executa($vConexao, $sqlAcesso, false);
        while ($reg_ACESSO = sql_retorno_lista($RS_ACESSO)) { //SQL ACESSO
            $ACESSO[0]  = $reg_ACESSO->ACECONSULTA;
            $ACESSO[1]  = $reg_ACESSO->ACEINCLUSAO;
            $ACESSO[2]  = $reg_ACESSO->ACEALTERACAO;
            $ACESSO[3]  = $reg_ACESSO->ACEEXCLUSAO;
        }
        return array( $ACESSO[0],$ACESSO[1],$ACESSO[2],$ACESSO[3] );
    }


    /****************************************************************************
    * Verifica Perfil do usuario
    * Data: 24/09/2010
    * Raphael Henkel Bohrer
    *
    * Retorna o campo USUPERFIL ta tabela USUARIOS
    *
    * Parametros: ( USUCODIGO )
    ******************************************************************************/
    function VerificaPerfil($USUCODIGO)
    {
        $sqlAcesso = "Select USUPERFIL From USUARIOS Where USUCODIGO = $USUCODIGO";
        //echo $sqlAcesso."<BR>";
        $vConexao = sql_conectar_banco(vGBancoDisplay);
        $RS_ACESSO = sql_executa($vConexao, $sqlAcesso, false);
        while ($reg_ACESSO = sql_retorno_lista($RS_ACESSO)) {
            $Perfil  = $reg_ACESSO->USUPERFIL;
        }
        return $Perfil;
    }

    /*************************************************
        Data: 05/10/2010 - Pedro Godinho
        Gerador de digito para chave da tabela
        $pCodigo: parâmetro código a ser incrementado
        $pFilial: parâmetro código da filial
    ***************************************************/
    function setDigito($pCodigo, $pFilial)
    {
        $vSCodigo = $pCodigo . str_pad($pFilial, 2, "0");
        $vIPeso = 2;
        $vISoma = 0;
        for ($i=strlen($vSCodigo);$i>=1;$i--) {
            $vISoma = $vISoma + ($vSCodigo[$i] * $vIPeso);
            if ($vIPeso < 9) {
                $vIPeso = $vIPeso + 1;
            } else {
                $vIPeso = 2;
            }
        }
        $vISoma = ($vISoma * 10);
        $vIDigito = ($vISoma % 11) % 10;
        return $vSCodigo.$vIDigito;
    }


    function verificarVazio($value)
    {
        if (is_array($value)) {
            if (sizeof($value) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
                return true;
            } else {
                return false;
            }
        }
    }
    function valida_cpf($pCPF)
    {
        $cpf = str_replace('.', '', $pCPF);
        $cpf = str_replace('-', '', $cpf);
        // verifica se e numerico
        if (!is_numeric($cpf)) {
            return false;
        }
        // verifica se esta usando a repeticao de um numero
        if (($cpf == '11111111111') || ($cpf == '22222222222')
             || ($cpf == '33333333333') || ($cpf == '44444444444')
             || ($cpf == '55555555555') || ($cpf == '66666666666')
             || ($cpf == '77777777777') || ($cpf == '88888888888')
             || ($cpf == '99999999999') || ($cpf == '00000000000')) {
            return false;
        }
        //PEGA O DIGITO VERIFICADOR
        $dv_informado = substr($cpf, 9, 2);
        for ($i=0; $i<=8; $i++) {
            $digito[$i] = substr($cpf, $i, 1);
        }
        //CALCULA O VALOR DO 10o DIGITO DE VERIFICACAO
        $posicao = 10;
        $soma = 0;
        for ($i=0; $i<=8; $i++) {
            $soma = $soma + $digito[$i] * $posicao;
            $posicao = $posicao - 1;
        }
        $digito[9] = $soma % 11;
        if ($digito[9] < 2) {
            $digito[9] = 0;
        } else {
            $digito[9] = 11 - $digito[9];
        }
        //CALCULA O VALOR DO 11o DIGITO DE VERIFICACAO
        $posicao = 11;
        $soma = 0;
        for ($i=0; $i<=9; $i++) {
            $soma = $soma + $digito[$i] * $posicao;
            $posicao = $posicao - 1;
        }
        $digito[10] = $soma % 11;
        if ($digito[10] < 2) {
            $digito[10] = 0;
        } else {
            $digito[10] = 11 - $digito[10];
        }
        //VERIFICA SE O DV CALCULADO E IGUAL AO INFORMADO
        $dv = $digito[9] * 10 + $digito[10];
        if ($dv != $dv_informado) {
            return false;
        }
        return true;
    }


    //teste de validação de cnpj

    function valida_CNPJ($cnpj)
    {
        $cnpj = str_pad(filterNumber($cnpj), 14, '0', STR_PAD_LEFT);
        if (strlen($cnpj) != 14) {
            return false;
        } else {
            for ($t = 12; $t < 14; $t++) {
                for ($d = 0, $p = $t - 7, $c = 0; $c < $t; $c++) {
                    $d += $cnpj{$c} * $p;
                    $p   = ($p < 3) ? 9 : --$p;
                }

                $d = ((10 * $d) % 11) % 10;

                if ($cnpj{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }
    function getSegundos($pSTempo)
    {
        list($vSHora, $vSMinuto) = explode(":", $pSTempo);
        $vISegundos = ($vSHora * 3600) + ($vSMinuto * 60);
        return $vISegundos;
    }

    function segundosToHorasString($pISegundos)
    {
        $vIHoras = floor($pISegundos / 3600);
        $vIHorasSeg = ($vIHoras * 3600);
        $vIDifHoras = ($pISegundos - $vIHorasSeg);
        $vIMinutos = ($vIDifHoras / 60);
        $vIMinutosSeg = ($vIMinutos * 60);
        $vIDifMinutos = ($vIDifHoras - $vIMinutosSeg);
        if (strlen($vIMinutos) == 1) {
            $vIMinutos = '0'.$vIMinutos;
        }
        return $vIHoras.':'.$vIMinutos;
    }
    /*************************************************
        Data: 29/05/2011 - Pedro Godinho
        Upload de arquivo para diretório
        $pData: parâmetro data do banco
    ***************************************************/
    function uploadArquivo($pArquivo, $pDiretorio, $pNomeArquivo)
    {//faz Uploaded da imagem e retorna o nome dela
        move_uploaded_file($pArquivo['tmp_name'], $pDiretorio.'/'.$pNomeArquivo);
        chmod($pDiretorio.'/'.$pNomeArquivo, 0777);
    }
    /*************************************************
        Data: 16/08/2011 - Pedro Godinho
        Periodicidade data conforme parametro
        $pPeriodo: parâmetro periodo
        $pIncremento: parâmetro incremento
        $pData: parâmetro data
    ***************************************************/

    function novaDataPeriodo($pPeriodo, $pIncremento, $pData)
    {
        if ($pPeriodo == '') {
            $pPeriodo = 'Mensal';
        }
        //$pDataa =  $pData;
        $pData =  explode("/", $pData);
        $ano = $pData[2];
        $mes = $pData[1];
        $dia = $pData[0];


        if ($pPeriodo == '8Meses') {
            $vmeses = (8 * $pIncremento);
            $vDataVencimentoTemp = date('d/m/Y', mktime(0, 0, 0, ($mes + $vmeses), $dia, $ano));
        }



        if ($pPeriodo == '20Dias') {
            $vDias = (20 * $pIncremento);
            $vDataVencimentoTemp = date('d/m/Y', mktime(0, 0, 0, $mes, $dia + $vDias, $ano));
        }


        if ($pPeriodo == 'Mensal') {
            //$vDataNova = date('d/m/Y', mktime(0,0,0,$mes + $pIncremento,$dia,$ano));
            $vDataNova = date('d/m/Y', mktime(0, 0, 0, ($mes + $pIncremento), $dia, $ano));
            /*
            $vDiadasemana = date("w", mktime(0,0,0,$mes + $pIncremento,$dia,$ano));
            // se cair no final de semana tirar por enquanto
            switch($vDiadasemana){
                case "0" : $vDataNova = date('d/m/Y', mktime( 0, 0, 0, $mes + $pIncremento, $dia + 1, $ano ));
                    break;
                case "6" : $vDataNova = date('d/m/Y', mktime( 0, 0, 0, $mes + $pIncremento, $dia + 2, $ano ));
                    break;
            } */
            $vDataVencimentoTemp = $vDataNova;
        } elseif ($pPeriodo == 'Anual') {
            $vmeses = (12 * $pIncremento);
            //$vDataNova = date('d/m/Y', mktime(0,0,0,$mes + $pIncremento,$dia,$ano));
            $vDataNova = date('d/m/Y', mktime(0, 0, 0, ($mes + $vmeses), $dia, $ano));

            //$vDataNova = date('d/m/Y', mktime(0,0,0,0,0,($ano + $pIncremento)));
            // $vDataNova = new DateTime('$pDataa');
            //imprime 10/10/2009
            //echo $date1->format('d/m/Y');

            //adiciona 1 semana na data
            // $vDataNova->modify('+$pIncremento year');
            //imprime 17/10/2009
            /*
            $vDiadasemana = date("w", mktime(0,0,0,$mes,$dia,$ano + $pIncremento));
            switch($vDiadasemana){
                case "0" : $vDataNova = date('d/m/Y', mktime(0,0,0,$mes,$dia + 1,$ano + $pIncremento));
                    break;
                case "6" : $vDataNova = date('d/m/Y', mktime(0,0,0,$mes,$dia + 2,$ano + $pIncremento));
                    break;
            } */
            $vDataVencimentoTemp = $vDataNova;
        } elseif ($pPeriodo == 'Semanal') {
            $vDias = (7 * $pIncremento);
            $vDataNova = date('d/m/Y', mktime(0, 0, 0, $mes, $dia + $vDias, $ano));
            /*
            $vDiadasemana = date("w", mktime(0,0,0,$mes,$dia + $vDias,$ano));
            switch($vDiadasemana){
                case "0" : $vDataNova = date('d/m/Y', mktime(0,0,0,$mes,(($dia + $vDias) + 1),$ano));
                    break;
                case "6" : $vDataNova = date('d/m/Y', mktime(0,0,0,$mes,(($dia + $vDias) + 2),$ano));
                    break;
            } */
            $vDataVencimentoTemp = $vDataNova;
        } elseif ($pPeriodo == 'Trimestral') {
            $vmeses = (3 * $pIncremento);
            //$vDataNova = date('d/m/Y', mktime(0,0,0,$mes + $pIncremento,$dia,$ano));
            $vDataNova = date('d/m/Y', mktime(0, 0, 0, ($mes + $vmeses), $dia, $ano));

            //$vDataNova = date('d/m/Y', mktime(0,0,0,0,0,($ano + $pIncremento)));
            // $vDataNova = new DateTime('$pDataa');
            //imprime 10/10/2009
            //echo $date1->format('d/m/Y');

            //adiciona 1 semana na data
            // $vDataNova->modify('+$pIncremento year');
            //imprime 17/10/2009
            /*
            $vDiadasemana = date("w", mktime(0,0,0,$mes,$dia,$ano + $pIncremento));
            switch($vDiadasemana){
                case "0" : $vDataNova = date('d/m/Y', mktime(0,0,0,$mes,$dia + 1,$ano + $pIncremento));
                    break;
                case "6" : $vDataNova = date('d/m/Y', mktime(0,0,0,$mes,$dia + 2,$ano + $pIncremento));
                    break;
            } */
            $vDataVencimentoTemp = $vDataNova;
        } elseif ($pPeriodo == 'Semestral') {
            $vmeses = (6 * $pIncremento);
            //$vDataNova = date('d/m/Y', mktime(0,0,0,$mes + $pIncremento,$dia,$ano));
            $vDataNova = date('d/m/Y', mktime(0, 0, 0, ($mes + $vmeses), $dia, $ano));

            //$vDataNova = date('d/m/Y', mktime(0,0,0,0,0,($ano + $pIncremento)));
            // $vDataNova = new DateTime('$pDataa');
            //imprime 10/10/2009
            //echo $date1->format('d/m/Y');

            //adiciona 1 semana na data
            // $vDataNova->modify('+$pIncremento year');
            //imprime 17/10/2009
            /*
            $vDiadasemana = date("w", mktime(0,0,0,$mes,$dia,$ano + $pIncremento));
            switch($vDiadasemana){
                case "0" : $vDataNova = date('d/m/Y', mktime(0,0,0,$mes,$dia + 1,$ano + $pIncremento));
                    break;
                case "6" : $vDataNova = date('d/m/Y', mktime(0,0,0,$mes,$dia + 2,$ano + $pIncremento));
                    break;
            } */
            $vDataVencimentoTemp = $vDataNova;
        } elseif ($pPeriodo == 'Quinzenal') {
            $vDias = (15 * $pIncremento);
            $vDataNova = date('d/m/Y', mktime(0, 0, 0, $mes, $dia + $vDias, $ano));
            /*
            $vDiadasemana = date("w", mktime(0,0,0,$mes,$dia + $vDias,$ano));
            switch($vDiadasemana){
                case "0" : $vDataNova = date('d/m/Y', mktime(0,0,0,$mes,(($dia + $vDias) + 1),$ano));
                    break;
                case "6" : $vDataNova = date('d/m/Y', mktime(0,0,0,$mes,(($dia + $vDias) + 2),$ano));
                    break;
            } */
            $vDataVencimentoTemp = $vDataNova;
        } elseif ($pPeriodo == 'Diario') {
            $vDataNova = date('d/m/Y', mktime(0, 0, 0, $mes, ($dia + $pIncremento), $ano));
            $vDataVencimentoTemp = $vDataNova;
        }



        return $vDataVencimentoTemp;
    }


    /***************************************************
        Data: 10/02/2014 - Marcelo Serpa
     ***************************************************/

    function gerarNovaDataPeriodo($pSPeriodoBase, $pIPeriodoAdicional, $pIIncremento, $pSData)
    {
        $vAPeriodoBasePermitidos = array('Mensal', 'Diario', 'Anual' );

        if ($pSPeriodoBase == '') {
            $pSPeriodoBase = 'Mensal';
        }

        if (!in_array($pSPeriodoBase, $vAPeriodoBasePermitidos)) {
            return false;
        }
        list($dia, $mes, $ano) = explode("/", $pSData);

        $vIAddMes = 0;
        $vIAddAno = 0;
        $vIAddDia = 0;

        if ($pIPeriodoAdicional > 0 && $pIIncremento > 0) {
            if ($pSPeriodoBase == 'Mensal') {
                $vIAddMes = $pIPeriodoAdicional * $pIIncremento;
            } elseif ($pSPeriodoBase == 'Diario') {
                $vIAddDia = $pIPeriodoAdicional * $pIIncremento;
            } elseif ($pSPeriodoBase == 'Anual') {
                $vIAddAno = $pIPeriodoAdicional * $pIIncremento;
            }
        } //else { return false; }

        return date('d/m/Y', mktime(0, 0, 0, $mes + $vIAddMes, $dia + $vIAddDia, $ano + $vIAddAno));
    }

    /***************************************************
        Data: 03/10/2011 - Pedro Godinho
        Verificar acesso tela cadastro
        $pSTabela: acesso (tabela) verificada
     ***************************************************/
    function verificarAcessoCadastro($pSTabela, $pSMethod)
    {
        if ($_SESSION['SS_AMBIENTE'] != 'DESENV') {
            if ($pSMethod =="update") {
                if ($_SESSION['SA_ACESSOS']['TABELA'][$pSTabela]['ALTERACAO'] != "S") {
                    echo "<script language='javaScript'>window.location.href='../interface/main.php'</script>";
                    return;
                }
            } elseif ($pSMethod =="insert") {
                if ($_SESSION['SA_ACESSOS']['TABELA'][$pSTabela]['INCLUSAO'] != "S") {
                    echo "<script language='javaScript'>window.location.href='../interface/main.php'</script>";
                    return;
                }
            } elseif ($pSMethod =="consultar") {
                if ($_SESSION['SA_ACESSOS']['TABELA'][$pSTabela]['CONSULTA'] != "S") {
                    echo "<script language='javaScript'>window.location.href='../interface/main.php'</script>";
                    return;
                }
            } else {
                echo "<script language='javaScript'>window.location.href='../interface/main.php'</script>";
                return;
            }
        }
    }


    /***************************************************
        Data: 02/05/2018 - Jonatan Colussi
        Verificar acesso tela cadastro
        $pSTabela: acesso (tabela) verificada
     ***************************************************/
    function verificarAcessoCadastroNoRedirect($pSTabela, $pSMethod)
    {
        if ($_SESSION['SS_AMBIENTE'] != 'DESENV') {
            if ($pSMethod =="update") {
                if ($_SESSION['SA_ACESSOS']['TABELA'][$pSTabela]['ALTERACAO'] != "S") {
                    return false;
                }
            } elseif ($pSMethod =="insert") {
                if ($_SESSION['SA_ACESSOS']['TABELA'][$pSTabela]['INCLUSAO'] != "S") {
                    return false;
                }
            } elseif ($pSMethod =="consultar") {
                if ($_SESSION['SA_ACESSOS']['TABELA'][$pSTabela]['CONSULTA'] != "S") {
                    return false;
                }
            } elseif ($pSMethod =="excluir") {
                if ($_SESSION['SA_ACESSOS']['TABELA'][$pSTabela]['EXCLUSAO'] != "S") {
                    return false;
                }
            } else {
                return false;
            }
        }

        return true;
    }

    // Função liberar ou restringir acesso a determinados campos de combos e grids...
    function visualizarCamposRegistro($pSMethod, $pSStatus)
    {
        session_start();
        if (($pSMethod == 'consultar') || ($pSStatus == 'N')) {
            $_SESSION['permissao'] = false;
        } else {
            $_SESSION['permissao'] = true;
        }

        return;
    }

    function descricaoMes($pIMes)
    {
        switch ($pIMes) {
        case 1:
            return "Janeiro";
            break;
        case 2:
            return "Fevereiro";
            break;
        case 3:
            return "Março";
            break;
        case 4:
            return "Abril";
            break;
        case 5:
            return "Maio";
            break;
        case 6:
            return "Junho";
            break;
        case 7:
            return "Julho";
            break;
        case 8:
            return "Agosto";
            break;
        case 9:
            return "Setembro";
            break;
        case 10:
            return "Outubro";
            break;
        case 11:
            return "Novembro";
            break;
        case 12:
            return "Dezembro";
            break;
    }
    }

    /*************************************************
        Data: 08/05/2011 - Pedro Godinho
        Adicionar Caracter a esquerda
        $pValor: parâmetro valor a ser inserido caracteres
        $pQtde : parâmetro quantidade de caracteres a inserir
        $pString : parâmetro string padrão 0 a ser inserida
    ***************************************************/
    function adicionarCaracterLeft($pValor, $pQtde, $pString = 0)
    {
        for ($i=strlen($pValor); $i<$pQtde; $i++) {
            $pValor = $pString.$pValor;
        }
        return $pValor;
    }

    /*************************************************
        Data: 08/05/2011 - Pedro Godinho
        Adicionar Caracter a direita
        $pValor: parâmetro valor a ser inserido caracteres
        $pQtde : parâmetro quantidade de caracteres a inserir
        $pString : parâmetro string padrão 0 a ser inserida
    ***************************************************/
    function adicionarCaracterRight($pValor, $pQtde, $pString = 0)
    {
        for ($i=strlen($pValor); $i<$pQtde; $i++) {
            $pValor=$pValor.$pString;
        }
        return $pValor;
    }

    function verificarSessaoAtiva()
    {
        if (!isset($_SESSION['SI_ID_USUARIO'])) {
            echo "<script language='javaScript'>window.location.href='../login.php'</script>";
            return;
        }
    }

    //Função para "manipular" valores vindos do banco de dados. Formatar valor monetário.
    function formatar_valor_monetario_banco($pCValor)
    {
        $pCValor = str_replace('.', '', $pCValor);
        $pCValor = str_replace(',', '.', $pCValor);
        return $pCValor;
    }

    /*************************************************
        Data: 14/11/2011 - Pedro Godinho
        Diferença entre duas datas formato banco em dias
        $pData1: parâmetro Data Inicial
        $pData2: parâmetro Data Final
    ***************************************************/
    function diferencaDatasDias($pData1, $pData2)
    {
        $pData1 = explode("-", $pData1);
        $vData1 = mktime(0, 0, 0, $pData1[1], $pData1[2], $pData1[0]);
        $pData2 = explode("-", $pData2);
        $vData2 = mktime(0, 0, 0, $pData2[1], $pData2[2], $pData2[0]);
        //$data_atual = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $vIDias = (($vData1 - $vData2)/86400);
        $vIDias = floor($vIDias);
        return $vIDias;
    }

    /*************************************************
        Data: 14/11/2011 - Pedro Godinho
        Diferença entre duas datas formato banco em dias
        $pData1: parâmetro Data Inicial
        $pData2: parâmetro Data Final
    ***************************************************/
    function diferencaDatasDiasHoras($pDataHora1, $pDataHora2)
    {
        $vSTemp1 = explode(" ", $pDataHora1);
        $pData1 = explode("-", $vSTemp1[0]);
        $pHora1 = explode(":", $vSTemp1[1]);

        $vData1 = mktime($pHora1[0], $pHora1[1], $pHora1[2], $pData1[1], $pData1[2], $pData1[0]);
        $vSTemp2 = explode(" ", $pDataHora2);
        $pData2 = explode("-", $vSTemp2[0]);
        $pHora2 = explode(":", $vSTemp2[1]);
        $vData2 = mktime($pHora2[0], $pHora2[1], $pHora2[2], $pData2[1], $pData2[2], $pData2[0]);
        $vIDias = (($vData1 - $vData2)/86400);
        return ($vIDias*86400);
    }
    function converte_formata_minutos_horas($mins)
    {
        // Se os minutos estiverem negativos
        if ($mins < 0) {
            $min = abs($mins);
        } else {
            $min = $mins;
        }
        // Arredonda a hora
        $h = floor($min / 60);
        $m = ($min - ($h * 60)) / 100;
        $horas = $h + $m;
        if ($mins < 0) {
            $horas *= -1;
        }
        // Separa a hora dos minutos
        $sep = explode('.', $horas);
        $h = $sep[0];
        if (empty($sep[1])) {
            $sep[1] = 00;
        }
        $m = $sep[1];
        // Aqui um pequeno artifício pra colocar um zero no final
        if (strlen($m) < 2) {
            $m = $m . 0;
        }
        return sprintf('%02d:%02d', $h, $m);
    }

    function valorPorExtenso($valor=0)
    {
        if ((int)$valor > 0) {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");

            $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
            $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
            $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
            $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");

            $z=0;

            $valor = number_format($valor, 2, ".", ".");
            $inteiro = explode(".", $valor);
            for ($i=0;$i<count($inteiro);$i++) {
                for ($ii=strlen($inteiro[$i]);$ii<3;$ii++) {
                    $inteiro[$i] = "0".$inteiro[$i];
                }
            }

            // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
            $fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
            for ($i=0;$i<count($inteiro);$i++) {
                $valor = $inteiro[$i];
                $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
                $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
                $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

                $r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
                $t = count($inteiro)-1-$i;
                $r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
                if ($valor == "000") {
                    $z++;
                } elseif ($z > 0) {
                    $z--;
                }
                if (($t==1) && ($z>0) && ($inteiro[0] > 0)) {
                    $r .= (($z>1) ? " de " : "").$plural[$t];
                }
                if ($r) {
                    $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
                }
            }

            return($rt ? $rt : "zero");
        }
    }
    /*************************************************
        Data: 21/06/2013 - Pedro Godinho
        Limpar quebras de Linhas dados do banco exportadas para Excel
        $pSTexto: parâmetro Texto
    ***************************************************/
    function limparQuebraLinhaBancoExcel($pSTexto)
    {
        $vSTexto = str_replace("\r\n", '', $pSTexto);
        $vSTexto = str_replace("\r", '', $vSTexto);
        $vSTexto = str_replace("\n", '', $vSTexto);
        $vSTexto = str_replace("<br>", '', $vSTexto);
        $vSTexto = str_replace("<br/>", '', $vSTexto);
        return $vSTexto;
    }
    /**
     * $timestamp = strtotime("2013-08-17");
     * Descobrir o dia da semana por extenso
     * @param $timestamp
     * @return (string) $diaSemana
     * */
    function getDayOfWeek($timestamp)
    {
        $date = getdate($timestamp);
        $diaSemana = $date['weekday'];

        if (preg_match('/(sunday|domingo)/mi', $diaSemana)) {
            $diaSemana = 'Domingo';
        } elseif (preg_match('/(monday|segunda)/mi', $diaSemana)) {
            $diaSemana = 'Segunda';
        } elseif (preg_match('/(tuesday|terça)/mi', $diaSemana)) {
            $diaSemana = 'Terça';
        } elseif (preg_match('/(wednesday|quarta)/mi', $diaSemana)) {
            $diaSemana = 'Quarta';
        } elseif (preg_match('/(thursday|quinta)/mi', $diaSemana)) {
            $diaSemana = 'Quinta';
        } elseif (preg_match('/(friday|sexta)/mi', $diaSemana)) {
            $diaSemana = 'Sexta';
        } elseif (preg_match('/(saturday|sábado)/mi', $diaSemana)) {
            $diaSemana = 'Sábado';
        }

        return $diaSemana;
    }
    //Função para verificar próximo dia útil...
    /* TESTE
    $dataHoje = time();
    $proximoDiaUtil = diaUtil($dataHoje);
    $proximoDiaUtil = date('d/m/Y',$proximoDiaUtil );
    */
    function diaUtil($data)
    {
        while (true) {
            if (getDayOfWeek($data) == 'Sábado') {
                $data = $data + (86400 * 2);
                return diaUtil($data);
            } elseif (getDayOfWeek($data) == 'Domingo') {
                $data = $data + (86400 * 1);
                return diaUtil($data);
            }
            // }else if(Feriado($data) == true){ //Caso haja uma tabela feriados cadastrada pode fazer a verificação
            // $data = $data + (86400 * 1);
            // return diaUtil($data);
            // }
            else {
                return $data;
            }
        }
    }

    function convertTypesDB($pSDataType)
    {
        switch ($pSDataType) {
            case "int":
                $vSReturn = "num&eacute;rico";
                break;
            case "char":
                $vSReturn = "string";
                break;
            case "varchar":
                $vSReturn = "string";
                break;
            case "text":
                $vSReturn = "texto";
                break;
            case "date":
                $vSReturn = "data";
                break;
            case "datetime":
                $vSReturn = "data/hora";
                break;
            case "decimal":
                $vSReturn = "decimal";
                break;
        }
        return $vSReturn;
    }

    function formatColumnsDB($pSType, $pSEntrada)
    {
        if ($pSEntrada != "") {
            if ($pSType == "COLUMN_DEFAULT") {
                $vSReturn = " Valor padr&atilde;o: ".$pSEntrada.".";
            }

            if ($pSType == "IS_NULLABLE") {
                if ($pSEntrada == "NO") {
                    $vSReturn = " &Eacute; de preenchimento obrigat&oacute;rio.";
                } else {
                    $vSReturn = " N&atilde;o &eacute; de preenchimento obrigat&oacute;rio.";
                }
            }

            if ($pSType == "DATA_TYPE") {
                $vSReturn = " Tipo: ".convertTypesDB($pSEntrada).".";
            }

            if ($pSType == "CHARACTER_MAXIMUM_LENGTH") {
                $vSReturn = " Tamanho m&aacute;ximo: ".$pSEntrada.".";
            }

            if ($pSType == "CHARACTER_SET_NAME") {
                $vSReturn = " Character set: ".$pSEntrada.".";
            }

            if ($pSType == "COLUMN_COMMENT") {
                $pSEntrada = explode("#", $pSEntrada);
                $vSReturn = $pSEntrada[0];
            }

            return $vSReturn;
        } else {
            return "";
        }
    }
    function filterNumber($pSString)
    {
        return  preg_replace('/[^0-9]/', '', $pSString);
    }
    function removerCaracterEspecial($string)
    {
        $novaString = preg_replace("/[^a-zA-Z0-9_.]/", "", strtr($string, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
        return $novaString;
        // preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($string)
    }
    function removerAcentoEspacoCaracter($phrase)
    {
        $result = strtolower($phrase);
        $result = preg_replace("[à|á|ã|Ã|À|Á]", "a", $result);
        $result = preg_replace("[è|é|È|É]", "e", $result);
        $result = preg_replace("[ì|í|Ì|Í]", "i", $result);
        $result = preg_replace("[ò|ó|õ|Õ|Ò|Ó]", "o", $result);
        $result = preg_replace("[ù|ú|ü|Ù|Ú]", "u", $result);
        $result = preg_replace("[ç|Ç]", "c", $result);
        $result = preg_replace("[ñ|Ñ]", "n", $result);
        $result = preg_replace("/[^a-zA-Z0-9_.]/", "", $result); // trim all special chars
        $result = preg_replace("/[-]+$/", "", $result); // remove ending slashes
        return $result;
    }

    //CARREGA PERMISSÕES DO USUÁRIO EM UMA STRING
    function loadPermissoesToString()
    {
        $vSPermissoes = "";
        foreach ($_SESSION['SA_ACESSOS']['TABELA'] as $key => $value) {
            $vSTemPermissao = "N";

            if ($value['CONSULTA'] == "S") {
                $vSTemPermissao = "S";
            } elseif ($value['INCLUSAO'] == "S") {
                $vSTemPermissao = "S";
            } elseif ($value['ALTERACAO'] == "S") {
                $vSTemPermissao = "S";
            } elseif ($value['EXCLUSAO'] == "S") {
                $vSTemPermissao = "S";
            } elseif ($value['EXPORTAR'] == "S") {
                $vSTemPermissao = "S";
            } else {
                $vSTemPermissao = "N";
            }

            if ($vSTemPermissao == "S") {
                if ($vSPermissoes == "") {
                    $vSPermissoes = $key;
                } else {
                    $vSPermissoes .= ",".$key;
                }
            }
        }
        return $vSPermissoes;
    }

    //PESQUISA CARACTERES CORINGAS
    function searchCoringas($pSFieldDB, $pSTipoDB, $pValue, $pSRestricao = "AND")
    {
        $vSReturn = "";
        $pValue = str_replace("'", "", $pValue);
        $pValue = str_replace('"', '', $pValue);

        if ($pSTipoDB == 'char') {
            $vSCoringa = "";

            if (substr($pValue, 0, 1) == "=") {
                $vSCoringa = "=";
                $pValue = substr($pValue, 1);
            }

            if (substr($pValue, 0, 1) == "*") {
                $vSCoringa = "*-";
                $pValue = substr($pValue, 1);
            }

            if (substr($pValue, -1) == "*") {
                $vSCoringa = "-*";
                $pValue = substr($pValue, 0, strlen($pValue)-1);
            }

            if ($vSCoringa == "") {
                if (strpos($pValue, "+") === false) {
                    $vSCoringa = "";
                } else {
                    $vSCoringa = "+";
                }
            }

            if ($vSCoringa == "") {
                if (strpos($pValue, "|") === false) {
                    $vSCoringa = "";
                } else {
                    $vSCoringa = "|";
                }
            }

            switch ($vSCoringa) {
                case "=":
                    //pesquisa EXATAMENTE as palavras na ordem escrita
                    $vSReturn = " ".$pSRestricao." ".$pSFieldDB." = '".$pValue."' ";
                    break;
                case "*-":
                    //pesquisa que TERMINE com as palavras na ordem escrita
                    $vSReturn = " ".$pSRestricao." ".$pSFieldDB." LIKE '%".$pValue."' ";
                    break;
                case "-*":
                    //pesquisa que INICIE com as palavras na ordem escrita
                    $vSReturn = " ".$pSRestricao." ".$pSFieldDB." LIKE '".$pValue."%' ";
                    break;
                case "+":
                    //pesquisa que CONTENHAM TODAS as palavras indfiferentemente da ordem escrita
                    $vAPalavras = explode("+", $pValue);
                    foreach ($vAPalavras as $key => $value) {
                        if ($value != "") {
                            if ($vSReturn == "") {
                                $vSReturn = " ".$pSRestricao." (".$pSFieldDB." LIKE '%".$value."%' ";
                            } else {
                                $vSReturn .= " AND ".$pSFieldDB." LIKE '%".$value."%' ";
                            }
                        }
                    }
                    $vSReturn .= ") ";
                    break;
                case "|":
                    //pesquisa que CONTENHAM UMA OU MAIS das palavras indfiferentemente da ordem escrita
                    $vAPalavras = explode("|", $pValue);
                    foreach ($vAPalavras as $key => $value) {
                        if ($value != "") {
                            if ($vSReturn == "") {
                                $vSReturn = " ".$pSRestricao." (".$pSFieldDB." LIKE '%".$value."%' ";
                            } else {
                                $vSReturn .= " OR ".$pSFieldDB." LIKE '%".$value."%' ";
                            }
                        }
                    }
                    $vSReturn .= ") ";
                    break;
                default:
                    //pesquisa que contenham as palavras na ordem escrita
                    $vSReturn = " ".$pSRestricao." ".$pSFieldDB." LIKE '%".$pValue."%' ";
            }
        } elseif ($pSTipoDB == 'int') {
            $vSCoringa = "";

            if (substr($pValue, 0, 2) == ">=") {
                $vSCoringa = ">=";
                $pValue = substr($pValue, 2);
            }

            if (substr($pValue, 0, 2) == "<=") {
                $vSCoringa = "<=";
                $pValue = substr($pValue, 2);
            }

            if (substr($pValue, 0, 1) == "!") {
                $vSCoringa = "!";
                $pValue = substr($pValue, 1);
            }

            if (substr($pValue, 0, 1) == ">") {
                $vSCoringa = ">";
                $pValue = substr($pValue, 1);
            }

            if (substr($pValue, 0, 1) == "<") {
                $vSCoringa = "<";
                $pValue = substr($pValue, 1);
            }

            if ($vSCoringa == "") {
                if (strpos($pValue, "+") === false) {
                    //echo "111";
                    $vSCoringa = "";
                } else {
                    //echo "222";
                    $vSCoringa = "+";
                }
            }

            if ($vSCoringa == "") {
                if (strpos($pValue, "-") === false) {
                    //echo "333";
                    $vSCoringa = "";
                } else {
                    //echo "444";
                    $vSCoringa = "-";
                }
            }

            //echo $pValue = filterNumber($pValue);
            //echo "AAA:: ".$vSCoringa." ::AAA";
            switch ($vSCoringa) {
                case "!":
                    //pesquisa os números DIFERENTES do escrito
                    $vSReturn = " ".$pSRestricao." ".$pSFieldDB." <> '".$pValue."'";
                    break;
                case ">":
                    //pesquisa os números MAIORES que o escrito
                    $vSReturn = " ".$pSRestricao." ".$pSFieldDB." > '".$pValue."'";
                    break;
                case "<":
                    //pesquisa os números MENORES que o escrito
                    $vSReturn = " ".$pSRestricao." ".$pSFieldDB." < '".$pValue."'";
                    break;
                case ">=":
                    //pesquisa os números MAIORES OU IGUAIS ao escrito
                    $vSReturn = " ".$pSRestricao." ".$pSFieldDB." >= '".$pValue."'";
                    break;
                case "<=":
                    //pesquisa os números MENORES OU IGUAIS ao escrito
                    $vSReturn = " ".$pSRestricao." ".$pSFieldDB." <= '".$pValue."'";
                    break;
                case "+":
                    //pesquisa APENAS os números escritos
                    $vANumeros = explode("+", $pValue);
                    foreach ($vANumeros as $key => $value) {
                        if ($value != "") {
                            if ($vSReturn == "") {
                                $vSReturn = "'".$value."'";
                            } else {
                                $vSReturn .= ",'".$value."'";
                            }
                        }
                    }
                    $vSReturn = " ".$pSRestricao." ".$pSFieldDB." IN (".$vSReturn.") ";
                    break;
                case "-":
                    //pesquisa os números no INTERVALO escrito
                    $vANumeros = explode("-", $pValue);
                    $vSReturn = " ".$pSRestricao." ".$pSFieldDB." BETWEEN '".$vANumeros[0]."' AND '".$vANumeros[1]."'";
                    break;
                default:
                    //pesquisa EXATAMENTE o número escrito
                    $vSReturn = " ".$pSRestricao." ".$pSFieldDB." = '".$pValue."'";
            }
        }
        return $vSReturn;
    }

    //FUNÇÂO (EM CONSTRUÇÃO PARA LIMPAR NOMES)
    function otimizarNome($nome, $array_config = null)
    {
        $array_limpar = array();
        $nomeLimpo = "";
        if ($nome != '') {
            $array_limpar = explode(" ", $nome);
            if ($array_limpar[0] != "") {
                $nomeLimpo = $array_limpar[0];
                if (sizeof($array_limpar)>1) {
                    $nomeLimpo = $nomeLimpo . " " .end($array_limpar);
                }
                /* ULTIMO NOME ABREVIADO
                if(sizeof($array_limpar)>1){
                    $nomeLimpo = $nomeLimpo . " " .substr(end($array_limpar), 0, 1). ".";
                }*/
            }
        }
        return $nomeLimpo;
    }

    function ativoSimNao($vSValor)
    {
        return $vSValor == "S" ? "Sim" : "Não";
    }

    ////////////////////////
    //////HOMOLOGAÇÃO///////
    ////////////////////////

    function AdicionaZero($valor, $Qtde, $string = 0)
    {
        for ($i=strlen($valor); $i<$Qtde; $i++) {
            $valor=$string.$valor;
        }
        return $valor;
    }
    function AdicionaEspaco($valor, $Qtde, $string = ' ')
    {
        for ($i=strlen($valor); $i<$Qtde; $i++) {
            $valor=$string.$valor;
        }
        return $valor;
    }
    function AdicionaEspacoLeft($valor, $Qtde, $string = ' ')
    {
        for ($i=strlen($valor); $i<$Qtde; $i++) {
            $valor=$valor.$string;
        }
        return $valor;
    }

    function RemoverAcentos($string)
    {
        $str = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($string)));

        return $str;
    }

    function diaSemana($pDData, $pSTipo)
    {
        $ano =  substr("$pDData", 0, 4);
        $mes =  substr("$pDData", 5, -3);
        $dia =  substr("$pDData", 8, 9);

        $diasemana = date("w", mktime(0, 0, 0, $mes, $dia, $ano));
        if ($pSTipo == 'S') {
            switch ($diasemana) {
                case"0": $diasemana = "Domingo";       break;
                case"1": $diasemana = "Segunda-Feira"; break;
                case"2": $diasemana = "Terça-Feira";   break;
                case"3": $diasemana = "Quarta-Feira";  break;
                case"4": $diasemana = "Quinta-Feira";  break;
                case"5": $diasemana = "Sexta-Feira";   break;
                case"6": $diasemana = "Sábado";        break;
            }
        }
        return "$diasemana";
    }
	
	function diaSemana2($diasemana, $pSTipo)
    {
        if ($pSTipo == 'S') {
            switch ($diasemana) {
                case"0": $diasemana = "Dom";       break;
                case"1": $diasemana = "Seg"; break;
                case"2": $diasemana = "Ter";   break;
                case"3": $diasemana = "Qua";  break;
                case"4": $diasemana = "Qui";  break;
                case"5": $diasemana = "Sex";   break;
                case"6": $diasemana = "Sáb";        break;
            }
        }
        return "$diasemana";
    }

    function admAutenticado()
    {
        if (isset($_SESSION['SA_ACESSOS']['HELPDESK'][$_SESSION['SI_USU_EMPRESA']]['ADMINISTRADOR'])) {
            if ($_SESSION['SA_ACESSOS']['HELPDESK'][$_SESSION['SI_USU_EMPRESA']]['ADMINISTRADOR'] == 'S') {
                return true;
            }
        }

        return false;
    }

    function calculaDiferencaEntreDatas($dataTimestampFormatInicial, $dataTimestampFormatFinal)
    {
        $data_ini = strtotime($dataTimestampFormatInicial);
        $data_fim = strtotime($dataTimestampFormatFinal);

        $diferenca_segundos = $data_fim - $data_ini;

        return gmdate("H:i:s", $diferenca_segundos);
    }

    function base64url_encode($s)
    {
        return str_replace("=", "-_", base64_encode($s));
    }

    function base64url_decode($s)
    {
        return str_replace("-_", "=", base64_decode($s));
    }

    /*************************************************
        Data: 07/02/2014 - Pedro Godinho
        Calcular valor pro rata contrato
        $pSTexto: parâmetro Texto
    ***************************************************/
    function calcularProRataContrato($pDDataInicio, $pCValorMensalidade, $pCValorMensalidadeReajustada, $pCValorIndice)
    {
        $diaReajuste = explode("-", $pDDataInicio);
        $diaReajuste = (int) $diaReajuste[2];
        //echo 'dia Reajuste '.$diaReajuste;
        if (($diaReajuste >= 1) && ($diaReajuste < 31)) {
            $diasDiferenca = (30 - $diaReajuste) + 1;
        } elseif (($diaReajuste == 31) || ($diaReajuste == 1)) {
            $diasDiferenca = 0;
        }

        $valorMensAtual = ($pCValorMensalidade / 30) * ($diaReajuste - 1);
        $valorMensReajustada =($pCValorMensalidadeReajustada / 30) * $diasDiferenca;
        if ($pCValorMensalidade == $pCValorMensalidadeReajustada) {
            //echo 'teste';
            $proRata = 0;
        } elseif (($diasDiferenca == 0) && ($pCValorIndice > 0)) {
            $proRata = 0;
        } else {
            $proRata = ($valorMensAtual + $valorMensReajustada) - $pCValorMensalidade;
        }
        return $proRata;
    }
    //IDENTIFICA NAVEGADOR E VERSÃO
    function identificaNavegador()
    {
        $useragent = $_SERVER['HTTP_USER_AGENT'];

        if (preg_match('|MSIE ([0-9].[0-9]{1,2})|', $useragent, $matched)) {
            $browser_version=$matched[1];
            $browser = 'IE';
        } elseif (preg_match('|Opera/([0-9].[0-9]{1,2})|', $useragent, $matched)) {
            $browser_version=$matched[1];
            $browser = 'Opera';
        } elseif (preg_match('|Firefox/([0-9\.]+)|', $useragent, $matched)) {
            $browser_version=$matched[1];
            $browser = 'Firefox';
        } elseif (preg_match('|Chrome/([0-9\.]+)|', $useragent, $matched)) {
            $browser_version=$matched[1];
            $browser = 'Chrome';
        } elseif (preg_match('|Safari/([0-9\.]+)|', $useragent, $matched)) {
            $browser_version=$matched[1];
            $browser = 'Safari';
        } else {
            //navagador não identificado
            $browser_version = 0;
            $browser= 'other';
        }
        return $browser." (".$browser_version.")";
    }

    function pre($a)
    {
        echo("<pre>");
        print_r($a);
        echo("</pre>");
    }

    function pegarHora($pSDataHora)
    {
        list($vSData, $vSHora) = explode(' ', $pSDataHora);
        return substr($vSHora, 0, 5);
    }

    function ftpArquivo($pSCaminhoLocal, $pSCaminhoRemoto, $pSControleTipo, $pSMode = 'A')
    {
        $ftp_server = vGHostFTP;
        $ftp_user 	= vGUsernameFTP;
        $ftp_pass 	= vGPasswordFTP;

        $conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");

        $login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);

        /*
            Modificação para upload dinamico na storage marpa
        */

        //Quebra o caminho em barras
        $arr_caminhoRemoto = explode('/', $pSCaminhoRemoto);

        //Remove o nome do arquivo do array
        $arquivo = end($arr_caminhoRemoto);
        array_pop($arr_caminhoRemoto);

        //Limpa o array de diretórios
        $arr_caminhoRemoto = array_filter($arr_caminhoRemoto, function ($a) {
            return (!empty($a) || $a == '.' || $a == '..');
        });

        //Percorre o array de diretório, entrando neles remotamente, ou criando-os
        foreach ($arr_caminhoRemoto as $dir) {
            //Se não conseguir acessar o diretório
            if (!ftp_chdir($conn_id, $dir)) {
                //Tenta cria-lo
                if (!ftp_mkdir($conn_id, $dir)) {
                    //Se não conseguir criar o diretório, mata o processo;
                    die("Falha na criação de diretórios");
                } else {
                    //Se conseguiu criar o diretório, então acessa
                    ftp_chdir($conn_id, $dir);
                }
            }
        }

        ftp_pasv($conn_id, false);

        if ($pSControleTipo == 'upload') {
            if ($pSMode == 'A') {
                return ftp_put($conn_id, $arquivo, $pSCaminhoLocal, FTP_ASCII);
            } else {
                return ftp_put($conn_id, $arquivo, $pSCaminhoLocal, FTP_BINARY);
            }
        } else {
            if ($pSMode == 'A') {
                if (@ftp_get($conn_id, $pSCaminhoLocal, $arquivo, FTP_ASCII));
            } else {
                if (@ftp_get($conn_id, $pSCaminhoLocal, $arquivo, FTP_BINARY));
            }
        }

        ftp_close($conn_id);

        return true;
    }

    function removerAcentos2($pString)
    {
        $string = 'ÁÍÓÚÉÄÏÖÜËÀÌÒÙÈÃÕÂÎÔÛÊáíóúéäïöüëàìòùèãõâîôûêÇçºª';

        $tr = strtr(
            $pString,
            array(

              'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
              'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
              'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ð' => 'D', 'Ñ' => 'N',
              'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
              'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Ŕ' => 'R',
              'Þ' => 's', 'ß' => 'B', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
              'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
              'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
              'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
              'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y',
              'þ' => 'b', 'ÿ' => 'y', 'ŕ' => 'r', 'º' => '', 'ª' => ''
            )
        );
        return($tr);
    }

    /*************************************************
        Data: 10/06/2016 - Pedro Godinho
        Pegar duas datas e montar o SQL para pesquisa entre datas
        $pSCampoBanco: parâmetro campo banco de dados
        $pDataInicio: parâmetro data inicio
        $pDataFinal: parâmetro data final
    ***************************************************/
    function sql_add_datas_between($pSCampoBanco, $pDataInicio, $pDataFinal)
    {
        $vSSql = '';
        if (verificarVazio($pDataInicio) && verificarVazio($pDataFinal)) {
            $vSSql =" and (".$pSCampoBanco." BETWEEN '".formatar_data_banco($pDataInicio)." 00:00:00' and '".formatar_data_banco($pDataFinal)." 23:59:59')";
        } elseif (verificarVazio($pDataInicio) && !verificarVazio($pDataFinal)) {
            $vSSql =" and (".$pSCampoBanco." >= '".formatar_data_banco($pDataInicio)." 00:00:00')";
        } elseif (verificarVazio($pDataFinal) && !verificarVazio($pDataInicio)) {
            $vSSql =" and (".$pSCampoBanco." <= '".formatar_data_banco($pDataFinal)." 23:59:59')";
        }
        return $vSSql;
    }

    function sql_add_valores_between($pSCampoBanco, $pCValorInicio, $pCValorFinal)
    {
        $vSSql = '';
        if (verificarVazio($pCValorInicio) && verificarVazio($pCValorFinal)) {
            $vSSql =" and (".$pSCampoBanco." BETWEEN '".formatar_valor_monetario_banco($pCValorInicio)."' and '".formatar_valor_monetario_banco($pCValorFinal)."')";
        } elseif (verificarVazio($pCValorInicio) && !verificarVazio($pCValorFinal)) {
            $vSSql =" and (".$pSCampoBanco." >= '".formatar_valor_monetario_banco($pCValorInicio)."')";
        } elseif (verificarVazio($pCValorFinal) && !verificarVazio($pCValorInicio)) {
            $vSSql =" and (".$pSCampoBanco." <= '".formatar_valor_monetario_banco($pCValorFinal)."')";
        }
        return $vSSql;
    }

    function saudacaoUsuario()
    {
        // leitura das datas
        $dia = date('d');
        $mes = date('m');
        $ano = date('Y');
        $semana = date('w');
        $hr = date(" H ");
        $vSRetorno = descricaoDiaSemana($semana).', '.$dia.' de '.descricaoMes($mes).' de '.$ano;
        return $vSRetorno;
    }

    function descricaoDiaSemana($pIDia)
    {
        switch ($pIDia) {
            case 0: $pIDia = "Domingo"; break;
            case 1: $pIDia = "Segunda-feira"; break;
            case 2: $pIDia = "Ter&ccedil;a-feira"; break;
            case 3: $pIDia = "Quarta-feira"; break;
            case 4: $pIDia = "Quinta-feira"; break;
            case 5: $pIDia = "Sexta-feira"; break;
            case 6: $pIDia = "S&aacute;bado"; break;
        }
        return $pIDia;
    }

    function reArrayFiles($file_post)
    {
        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }

    function sweetAlert($pSTitulo, $pSMensagem, $pSTipoMensagem, $vSURL, $pSExibirMensagem = 'S')
    {
        if ($pSExibirMensagem == 'S') {
            if ($pSTipoMensagem == 'S') { // sucesso
                $vSMsg = 'Cadastro efetuado com sucesso!';
                $vSTipoMsg = "success";
            } elseif ($pSTipoMensagem == 'E') { // erro
                $vSMsg = 'Erro ao executar as atualizações!';
                $vSTipoMsg = "error";
            }
            if ($pSMensagem != '') {
                $vSMsg = $pSMensagem;
            }
            echo "<!DOCTYPE html>
					<html>
						<head>
							<link href=\"../assets/plugins/sweet-alert2/sweetalert2.min.css\" rel=\"stylesheet\" type=\"text/css\">
							<script src=\"../assets/js/jquery.min.js\"></script>
							<script src=\"../assets/plugins/sweet-alert2/sweetalert2.min.js\"></script>
							<script src=\"../assets/pages/jquery.sweet-alert.init.js\"></script>
							<script>";
            if ($pSExibirMensagem == 'S') {
                echo "\$(document).ready(function(){ Swal.queue([{title : \"{$pSTitulo}\", text : \"{$vSMsg}\", type : \"{$vSTipoMsg}\",preConfirm: () => {location.href = \"{$vSURL}\"}}]);});";
            } else {
                echo "location.href = \"{$vSURL}\";";
            }

            echo "</script>
						</head>
						<body>
						</body>
						</html>";
        }
    }

function search_volume($pSProduto)
{
    $pos = strpos($pSProduto, 'ML');
    if ($pos == false) {
        $pos = strpos($pSProduto, 'LT');
        if ($pos == false) {
            $pos = strpos($pSProduto, '0G');
            if ($pos == false) {
                $volume = "";
                //exceções - inicio
                $pos = strpos($pSProduto, ' 1KG');
                if ($pos != false) {
                    $volume = 1000;
                }
                $pos = strpos($pSProduto, ' ZERO2L');
                if ($pos != false) {
                    $volume = 2000;
                }
                $pos = strpos($pSProduto, ' 3L');
                if ($pos != false) {
                    $volume = 3000;
                }
                $pos = strpos($pSProduto, ' 1L');
                if ($pos != false) {
                    $volume = 1000;
                }
                $pos = strpos($pSProduto, ' 1 L');
                if ($pos != false) {
                    $volume = 1000;
                }
                $pos = strpos($pSProduto, ' 2L');
                if ($pos != false) {
                    $volume = 2000;
                }
                $pos = strpos($pSProduto, ' 2lt');
                if ($pos != false) {
                    $volume = 2000;
                }
                $pos = strpos($pSProduto, ' 5L');
                if ($pos != false) {
                    $volume = 5000;
                }
                $pos = strpos($pSProduto, '1,5 L');
                if ($pos != false) {
                    $volume = 1500;
                }
                $pos = strpos($pSProduto, '1,5L');
                if ($pos != false) {
                    $volume = 1500;
                }
                $pos = strpos($pSProduto, '1.5L');
                if ($pos != false) {
                    $volume = 1500;
                }
                $pos = strpos($pSProduto, '1.5 L');
                if ($pos != false) {
                    $volume = 1500;
                }
                $pos = strpos($pSProduto, ' PET 600');
                if ($pos != false) {
                    $volume = 600;
                }
                $pos = strpos($pSProduto, ' 200 ML');
                if ($pos != false) {
                    $volume = 200;
                }
                $pos = strpos($pSProduto, ' 600ml');
                if ($pos != false) {
                    $volume = 200;
                }
                //exceções - fim
            } else {
                $volume = substr($pSProduto, $pos-2, 3) . " Gr";
                if (substr($volume, 0, 1) == '.') {
                    $volume = substr($pSProduto, $pos-1, 2) . " Gr";
                }
                if (substr($volume, 0, 3) == '000') {
                    $volume = "1000" . " Gr";
                }
            }
        } else {
            //era assim, vou sacanear		$volume = substr($pSProduto, $pos-1, 1) . " Litro";
            $volume = substr($pSProduto, $pos-1, 1) . "000";
        }
    } else {
        $volume = substr($pSProduto, $pos-3, 3);
        $pos = strpos($pSProduto, ' 200 ML');
        if ($pos != false) {
            $volume = 200;
        }
        $pos = strpos($pSProduto, ' 450 ML');
        if ($pos != false) {
            $volume = 450;
        }
    }
    return $volume;
}
function adicionaDiminuiMeses($pDData, $pIMeses)
{
    $pDData =  explode("-", $pDData);
    $vNovaData = date('Y-m-d', mktime(0, 0, 0, $pDData[1] + $pIMeses, $pDData[2], $pDData[0]));
    return $vNovaData;
}

function isColor($data)
{
    if (preg_match("/#([a-f0-9]{3}){1,2}\b/i", $data)) {
        return true;
    }

    return false;
}

function nameFile($file)
{
    $arr_file = explode('/', $file);
    return end($arr_file);
}

function valorPorExtensoPorcentagem($valor=0)
{
    if ((int)$valor > 0) {
        $singular = array("porcento");
        $plural = array("porcento");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");

        $z=0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        for ($i=0;$i<count($inteiro);$i++) {
            for ($ii=strlen($inteiro[$i]);$ii<3;$ii++) {
                $inteiro[$i] = "0".$inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
        for ($i=0;$i<count($inteiro);$i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
            $t = count($inteiro)-1-$i;
            $r .= $r ? " ".($valor > 1 ? $plural[0] : $singular[0]) : "";
            if ($valor == "000") {
                $z++;
            } elseif ($z > 0) {
                $z--;
            }
            if (($t==1) && ($z>0) && ($inteiro[0] > 0)) {
                $r .= (($z>1) ? " de " : "").$plural[0];
            }
            if ($r) {
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
            }
        }

        return($rt ? $rt : "zero");
    }
}

function enviarEmail($pAEmails, $pSAssunto, $pSMensagem, $pAEmailsCC = array())
{
    $phpmailer_diretorio_base = $_SERVER['DOCUMENT_ROOT'].'twcore/vendors/phpmailer/';
    require_once $phpmailer_diretorio_base.'PHPMailerAutoload.php';

    $mail = new PHPMailer;
    $mail->setLanguage('br', $phpmailer_diretorio_base.'language/');
    $mail->isSMTP();

    $mail->Host = 'smtp.gmail.com';
    $mail->CharSet = 'UTF-8';
    $mail->SMTPAuth = true;
    $mail->Port = 465;
    $mail->Username = 'contato@teraware.com.br';
    $mail->Password = 'NZA98email';
    $mail->SMTPSecure = 'ssl';
    $mail->From = 'contato@teraware.com.br';
    $mail->FromName = 'Gestão Inteligência em Administração Pública';
    //$mail->SMTPDebug = 2;
    foreach ($pAEmails as $address) {
        if (filter_var($address, FILTER_VALIDATE_EMAIL)) {
            $mail->addAddress($address, '');
        }
    }

    if (is_string($pAEmailsCC)) {
        $pAEmailsCC = explode(';', $pAEmailsCC);
    }

    foreach ($pAEmailsCC as $cc) {
        if (filter_var($cc, FILTER_VALIDATE_EMAIL)) {
            $mail->addCC($cc, '');
        }
    }

    $mail->isHTML(true);

    $mail->Subject = $pSAssunto;
    $mail->Body = $pSMensagem;

    if (!$mail->send()) {
        echo 'Erro no envio do e-mail. Entre em contato com o administrador do sistema. Erro: ' . $mail->ErrorInfo;
        return false;
    } else {
        echo 'E-mail enviado com sucesso.';
        return true;
    }
}

function gerarProximaData($pDData, $periodo, $tipo_periodo, $padrao = 'BR')
{
    if (preg_match("^\\d{1,2}/\\d{2}/\\d{4}^", $pDData)) {
        $pDData = formatar_data_banco($pDData);
    }
    $data =  "";
    switch ($tipo_periodo) {
        case 'D':
            $tipo = "day";
            break;
        case 'M':
            $tipo = "month";
            break;
        case 'Y':
            $tipo = "year";
            break;
        default:
            # code...
            break;
    }
    $padrao_data = ($padrao == 'BR') ? 'd/m/Y' : 'Y-m-d';
    $data = date($padrao_data, strtotime(" +$periodo $tipo", strtotime($pDData)));
    return $data;
}

if (! function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null)
    {
        $array = array();
        foreach ($input as $value) {
            if (!array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            } else {
                if (!array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if (! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}

function setSelectedOption($options, $defaultValue)
{
    $html = '';
    foreach ($options as $value => $label) {
        if ($value == $defaultValue) {
            $html .= '<option selected value="'.$value.'">'.$label.'</option>'."\n";
        } else {
            $html .= '<option value="'.$value.'">'.$label.'</option>'."\n";
        }
    }

    return $html;
}

function getNextFixedDate(int $day, $date = null)
{
    if (is_null($date)) {
        $date = date('Y-m-d');
    }

    if (date('d', strtotime($date)) < $day) {
        return date('Y-m').'-'.$day;
    } else {
        return date('Y-m', strtotime('+ 1 month', strtotime($date))).'-'.$day;
    }
}

function calcular_idade($data_nascimento, $data_final)
{
    if ($data_nascimento != '') {
        if ($data_final == '') {
            $data_final = date("Y-m-d");
        }
        //conversão das datas para o formato de tempo linux
        $data_nascimento  = strtotime($data_nascimento." 00:00:00");
        $data_final 	  = strtotime($data_final." 00:00:00");
        //cálculo da idade fazendo a diferença entre as duas datas
        $idade = floor(abs($data_final - $data_nascimento) /60 /60 /24 /365);
        return $idade;
    }
}

function converterTamanhoLetra($string, $tamanho = 'minusculo')
{
    $convertida = '';

    switch ($tamanho) {
        case 'maiusculo':
            $convertida = mb_convert_case($string, MB_CASE_UPPER, "UTF-8");
            break;
        case 'titulo':
            $convertida = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
            break;
        case 'minusculo':
        default:
            $convertida = mb_convert_case($string, MB_CASE_LOWER, "UTF-8");
            break;
    }

    return $convertida;
}

function uploadArquivoUnico($diretorio, $codigo, $arquivo, $extensoesPermitidas)
{
    try {
        if ($arquivo['error'] == 0) {
            $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
            $extensao = strtolower($extensao);

            if (in_array($extensao, $extensoesPermitidas)) {
                $nomeArquivo = $codigo.'.'.$extensao;
                $arquivoUpload = $arquivo['tmp_name'];

                $diretorio = "{$diretorio}";

                if (!is_dir($diretorio)) {
                    mkdir($diretorio, 0755, true);
                } else {
                    chmod($diretorio, 0755);
                }
                //Verifica se o diretório foi criado
                if (is_dir($diretorio)) {
                    //Move a imagem para o diretório definido
                    if (move_uploaded_file($arquivoUpload, $diretorio.'/'.$nomeArquivo)) {
                        //Retorna o nome do arquivo
                        return $nomeArquivo;
                    } else {
                        throw new Exception("Ocorreu uma falha ao realizar o upload do arquivo");
                    }
                } else {
                    throw new Exception("Não foi possivel encontrar o diretório");
                }
            } else {
                throw new Exception("Tipo de arquivo não permitido! ({$extensao})");
            }
        } else {
            throw new Exception("Ocorreu uma falha ao realizar o upload do arquivo");
        }
    } catch (Exception $e) {
        echo 'Ocorreu um erro: '.$e->getMessage()."\n";
        return false;
    }
}

function ultimoDiaMes($pIAno, $pIMes)
{
    $pIAno = ($pIAno == '') ? date("Y") : $pIAno;
    $pIMes = ($pIMes == '') ? date("m") : $pIMes;
    $ultimo_dia = date("t", mktime(0, 0, 0, $pIMes, '01', $pIAno));
    return $ultimo_dia;
}

function verificarFinalSemana($pDData)
{
    //$str = '2016-09-24';
    $dt = new DateTime($pDData);
    $weekend = ($dt->format('w') % 6) == 0;
    return $weekend;
}

function pluralize($qtd, $singular, $plural)
{
    return ($qtd == 0 || $qtd > 1) ? $qtd.' '.$plural : $qtd.' '.$singular;
}

function dias_uteis($mes, $ano)
{
    $uteis = 0;
    // Obtém o número de dias no mês
    // (http://php.net/manual/en/function.cal-days-in-month.php)
    $dias_no_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

    for ($dia = 1; $dia <= $dias_no_mes; $dia++) {

    // Aqui você pode verifica se tem feriado
        // ----------------------------------------
        // Obtém o timestamp
        // (http://php.net/manual/pt_BR/function.mktime.php)
        $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
        $semana    = date("N", $timestamp);

        if ($semana < 6) {
            $uteis++;
        }
    }

    return $uteis;
}

function gerarSenhaAleatoria($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
    $lmin = 'abcdefghijklmnopqrstuvwxyz';
    $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '1234567890';
    $simb = '!@#$%*-';
    $retorno = '';
    $caracteres = '';
    $caracteres .= $lmin;
    if ($maiusculas) {
        $caracteres .= $lmai;
    }
    if ($numeros) {
        $caracteres .= $num;
    }
    if ($simbolos) {
        $caracteres .= $simb;
    }
    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand-1];
    }
    return $retorno;
}