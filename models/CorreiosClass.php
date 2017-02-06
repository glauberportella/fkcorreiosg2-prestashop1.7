<?php

include_once(dirname(__FILE__).'/../../../config/config.inc.php');
include_once('FKcorreiosg2Class.php');

class CorreiosClass {

    private $empresa;
    private $senha;
    private $codServico;
    private $cepOrigem;
    private $cepDestino;
    private $peso;
    private $formato;
    private $comprimento;
    private $altura;
    private $largura;
    private $diametro;
    private $cubagem;
    private $maoPropria;
    private $valorDeclarado;
    private $avisoRecebimento;

    private $retornoCorreios = array();
    private $valorFrete;
    private $prazoEntrega;
    private $codRetorno;
    private $msgRetorno;

    public function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function setCodServico($codServico) {
        $this->codServico = $codServico;
    }

    public function setCepOrigem($cepOrigem) {
        $this->cepOrigem = preg_replace("/[^0-9]/", "", $cepOrigem);
    }

    public function setCepDestino($cepDestino) {
        $this->cepDestino = preg_replace("/[^0-9]/", "", $cepDestino);
    }

    public function setPeso($peso) {
        $this->peso = str_replace(",",".",$peso);
    }

    public function setFormato($formato) {
        $this->formato = $formato;
    }

    public function setComprimento($comprimento) {
        $this->comprimento = str_replace(",",".",$comprimento);
    }

    public function setAltura($altura) {
        $this->altura = str_replace(",",".",$altura);
    }

    public function setLargura($largura) {
        $this->largura = str_replace(",",".",$largura);
    }

    public function setDiametro($diametro) {
        $this->diametro = str_replace(",",".",$diametro);
    }

    public function setCubagem($cubagem) {
        $this->cubagem = str_replace(",",".",$cubagem);
    }

    public function setMaoPropria($maoPropria) {
        $this->maoPropria = $maoPropria;
    }

    public function setValorDeclarado($valorDeclarado) {
        $this->valorDeclarado = str_replace(",",".",$valorDeclarado);
    }

    public function setAvisoRecebimento($avisoRecebimento) {
        $this->avisoRecebimento = $avisoRecebimento;
    }

    public function getRetornoCorreios() {
        return $this->retornoCorreios;
    }

    public function getValorFrete() {
        return str_replace(",",".",$this->valorFrete);
    }

    public function getPrazoEntrega() {
        return $this->prazoEntrega;
    }

    public function getCodRetorno() {
        return $this->codRetorno;
    }

    public function getMsgRetorno() {
        return $this->msgRetorno;
    }

    public function calculaPrecoPrazo() {

        // Recupera e monta string com todos os serviços ativos dos Correios
        $fkclass = new FKcorreiosg2Class();
        $dados = $fkclass->recuperaServicosCorreiosAtivos();

        $servicos = array(
            'pesquisaCorreios'  => '',
            'servicos'          => array(),
        );

        foreach ($dados as $reg) {

            if ($servicos['pesquisaCorreios'] == '') {
                $servicos['pesquisaCorreios'] = $reg['cod_servico'];
            }else {
                $servicos['pesquisaCorreios'] .= ','.$reg['cod_servico'];
            }

            $servicos['servicos'][$reg['cod_servico']] = $reg['id'];
        }

        $parm = array(
            'nCdEmpresa'            => $this->empresa,
            'sDsSenha'              => $this->senha,
            'nCdServico'            => $servicos['pesquisaCorreios'],
            'sCepOrigem'            => $this->cepOrigem,
            'sCepDestino'           => $this->cepDestino,
            'nVlPeso'               => $this->peso,
            'nCdFormato'            => $this->formato,
            'nVlComprimento'        => $this->comprimento,
            'nVlAltura'             => $this->altura,
            'nVlLargura'            => $this->largura,
            'nVlDiametro'           => $this->diametro,
            'sCdMaoPropria'         => $this->maoPropria,
            'nVlValorDeclarado'     => $this->valorDeclarado,
            'sCdAvisoRecebimento'   => $this->avisoRecebimento
        );

        try {
            $ws = new SoapClient(Configuration::get('FKCORREIOSG2_URL_WS_CORREIOS'));
            $arrayRetorno = $ws->CalcPrecoPrazo($parm);
            $retornos = $arrayRetorno->CalcPrecoPrazoResult->Servicos->cServico;

            // Se somente 1 servico dos Correios ativo
            if (count($retornos) == 1) {
                $retornosTmp[] = $retornos;
            }else {
                $retornosTmp = $retornos;
            }

            foreach ($retornosTmp as $retorno) {

                if ($retorno->Valor > 0) {
                    $this->retornoCorreios[$retorno->Codigo] = array(
                        'gravarCache'   => true,
                        'idTranspAtual' => $servicos['servicos'][$retorno->Codigo],
                        'valorFrete'    => str_replace(",",".",$retorno->Valor),
                        'prazoEntrega'  => $retorno->PrazoEntrega,
                        'codRetorno'    => $retorno->Erro,
                        'msgRetorno'    => $retorno->MsgErro
                    );
                }else {
                    // Verifica o erro
                    $trataErro = $this->trataErro($retorno->Erro, $retorno->MsgErro);

                    if (!$trataErro['calculoOffline']) {
                        $this->retornoCorreios[$retorno->Codigo] = array(
                            'gravarCache'   => true,
                            'idTranspAtual' => $servicos['servicos'][$retorno->Codigo],
                            'valorFrete'    => '0',
                            'prazoEntrega'  => '',
                            'codRetorno'    => $retorno->Erro,
                            'msgRetorno'    => $retorno->MsgErro
                        );
                    }else {
                        $this->retornoCorreios[$retorno->Codigo] = array(
                            'gravarCache'   => false,
                            'idTranspAtual' => $servicos['servicos'][$retorno->Codigo],
                            'valorFrete'    => '',
                            'prazoEntrega'  => '',
                            'codRetorno'    => $retorno->Erro,
                            'msgRetorno'    => $retorno->MsgErro
                        );
                    }
                }

            }

            // Retorna dados da consulta principal nas propriedades da classe
            if ($this->retornoCorreios[$this->codServico]['valorFrete'] > 0) {
                $this->valorFrete = $this->retornoCorreios[$this->codServico]['valorFrete'];
                $this->prazoEntrega = $this->retornoCorreios[$this->codServico]['prazoEntrega'];
                $this->codRetorno = $this->retornoCorreios[$this->codServico]['codRetorno'];
                $this->msgRetorno = $this->retornoCorreios[$this->codServico]['msgRetorno'];
            }else {
                $this->valorFrete = '0';
                $this->prazoEntrega = '';

                if ($this->retornoCorreios[$this->codServico]['codRetorno'] == '0') {
                    $this->codRetorno = 'fk01';
                    $this->msgRetorno = '';
                }else {
                    $this->codRetorno = $this->retornoCorreios[$this->codServico]['codRetorno'];
                    $this->msgRetorno = $this->retornoCorreios[$this->codServico]['msgRetorno'];
                }

                return false;
            }

            return true;

        } catch (Exception $e) {
            $this->valorFrete = '0';
            $this->prazoEntrega = '';
            $this->codRetorno = 'fk99';
            $this->msgRetorno = '';
            return false;
        }

    }

    public function calculaTabOffline($idEspCorreios, $idTabOffline, $tipo) {

        // Recupera dados da tabela Especificacoes dos Correios
        $sql = "SELECT *
                FROM "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios
                WHERE id = ".(int)$idEspCorreios;

        $espCorreios = Db::getInstance()->getRow($sql);

        // Recupera dados da tabela Tabelas Offline
        $sql = "SELECT id_cadastro_cep
                FROM "._DB_PREFIX_."fkcorreiosg2_tabelas_offline
                WHERE id = ".(int)$idTabOffline;

        $tabOffline = Db::getInstance()->getRow($sql);

        // Verifica se e Minha Cidade, Capital ou Interior
        if ($tipo == 'cidade') {
            $cepDestino = Configuration::get('FKCORREIOSG2_MEU_CEP');
            $intervaloPeso = $espCorreios['intervalo_pesos_estadual'];
        }else {
            // Recupera dados do Cadastro de Cep
            $sql = "SELECT *
                    FROM "._DB_PREFIX_."fkcorreiosg2_cadastro_cep
                    WHERE id = ".(int)$tabOffline['id_cadastro_cep'];

            $cadCep = Db::getInstance()->getRow($sql);

            if ($tipo == 'capital') {
                $cepDestino = $cadCep['cep_base_capital'];
            }else {
                $cepDestino = $cadCep['cep_base_interior'];
            }

            // Verifica o intervalo de peso a ser utilizado no calculo
            $fkclass = new FKcorreiosg2Class();
            $ufOrigem = $fkclass->recuperaUF(Configuration::get('FKCORREIOSG2_MEU_CEP'));

            if (!$ufOrigem) {
                return 'erro';
            }

            if ($ufOrigem == $cadCep['estado']) {
                $intervaloPeso = $espCorreios['intervalo_pesos_estadual'];
            }else {
                $intervaloPeso = $espCorreios['intervalo_pesos_nacional'];
            }
        }

        // Cria array com os pesos a serem calculados
        $arrayPesos = explode('/', $intervaloPeso);

        // Aciona webservice dos Correios
        $retorno = '';

        foreach ($arrayPesos as $peso) {

            if ($peso == '') {
                continue;
            }

            if ($peso > 0) {

                // Tenta 3x gerar a tabela
                for ($i=1; $i <= 3; $i++) {

                    $this->setEmpresa($espCorreios['cod_administrativo']);
                    $this->setSenha($espCorreios['senha']);
                    $this->setCodServico($espCorreios['cod_servico']);
                    $this->setCepOrigem(Configuration::get('FKCORREIOSG2_MEU_CEP'));
                    $this->setCepDestino($cepDestino);
                    $this->setPeso($peso);
                    $this->setFormato('1');
                    $this->setComprimento($espCorreios['comprimento_min']);
                    $this->setAltura($espCorreios['altura_min']);
                    $this->setLargura($espCorreios['largura_min']);
                    $this->setDiametro('0');
                    $this->setMaoPropria('N');
                    $this->setValorDeclarado('0.00');
                    $this->setAvisoRecebimento('N');

                    if ($this->calculaPrecoPrazo()) {
                        // coloca o prazo de entrega no retorna
                        if ($retorno == '') {
                            $retorno = $this->prazoEntrega.'|';
                        }

                        $retorno .= $peso.':'.$this->getValorFrete().'/';
                        break;
                    }else {
                        $retorno = 'erro';
                    }

                }
            }
        }

        return $retorno;

    }

    public function trataErro($codRetorno, $msgRetorno) {

        switch($codRetorno) {

            case '-33':
                return array('calculoOffline' => true, 'mensagemErro' => 'Sistema dos Correios fora do ar.');

            case '-888':
                return array('calculoOffline' => true, 'mensagemErro' => 'Erro ao calcular a tarifa.');

            case '7':
                return array('calculoOffline' => true, 'mensagemErro' => 'Serviço dos Correios indisponível.');

            case '010':
                return array('calculoOffline' => true, 'mensagemErro' => 'Área com entrega temporariamente sujeita a prazo diferenciado.');

            case '012':
                return array('calculoOffline' => true, 'mensagemErro' => 'O CEP de destino pertence a uma área com restrição temporária de entrega.');

            case 'fk01':
                return array('calculoOffline' => true, 'mensagemErro' => 'O web services dos Correios retornou a transação OK mas com valor do frete ZERO.');

            case 'fk99':
                return array('calculoOffline' => true, 'mensagemErro' => 'Não foi possível acessar o webservice dos Correios.');

        }

        return array('calculoOffline' => false, 'mensagemErro' => $msgRetorno);
    }

}
