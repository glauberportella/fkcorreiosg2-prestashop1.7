<?php

include_once(dirname(__FILE__).'/models/CorreiosClass.php');
include_once(dirname(__FILE__).'/models/FKCorreiosg2Class.php');

if (isset($_REQUEST['func'])) {

    switch ($_REQUEST['func']) {

        case '1':
            // Recupera dados do POST
            $idEspCorreios = $_REQUEST['idEspCorreios'];
            $idTabOffline = $_REQUEST['idTabOffline'];
            $tipo = $_REQUEST['tipo'];

            // Instancia CorreiosClass
            $correiosClass = new CorreiosClass();
            $retorno = $correiosClass->calculaTabOffline($idEspCorreios, $idTabOffline, $tipo);
            echo $retorno;
            break;

        case '2':
            // Recupera dados do POST
            $codRastreio = $_REQUEST['codRastreio'];

            // Instancia FKCorreiosg2Class
            $fkcorreiosg2Class = new FKcorreiosg2Class();
            $retorno = $fkcorreiosg2Class->recuperaRastreio($codRastreio);
            echo $retorno;
            break;

        default:
            break;

    }
}else {
    // Retorna erro caso tenha problemas no Post
    echo 'erro';
}

