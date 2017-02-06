
jQuery(function() {
    $('.fkcorreiosg2-mask-cep').mask('99999-999');
});

function fkcorreiosg2Toggle(id) {
    $('#' + id).toggle('slow','linear');
};

function fkcorreiosg2Excluir(msg) {

    if (confirm(msg)) {
        return true;
    }

    return false;
};

function fkcorreiosg2ExcluirConf(msg, id) {

    if ($("#" + id).is(":checked")) {

        if (confirm(msg)) {
            return true;
        }

        $("#" + id).attr("checked", false);
        return false;
    }

    return true;

};

function fkcorreiosg2IncluirCepCidade() {

    var campo = '';

    campo = $('#fkcorreiosg2_cep_cidade').val();
    campo += $('#fkcorreiosg2_cidade_cep1').val().replace(/[^0-9]/g,'');
    campo += ':';
    campo += $('#fkcorreiosg2_cidade_cep2').val().replace(/[^0-9]/g,'');
    campo += '/';

    $('#fkcorreiosg2_cep_cidade').val(campo);
    $('#fkcorreiosg2_cidade_cep1').val('');
    $('#fkcorreiosg2_cidade_cep2').val('');
    $('#fkcorreiosg2_cidade_cep1').focus();

};

function fkcorreiosg2IncluirCepServicos(id) {

    var campo = '';

    campo = $('#fkcorreiosg2_servicos_cep_' + id).val();
    campo += $('#fkcorreiosg2_servicos_cep1_' + id).val().replace(/[^0-9]/g,'');
    campo += ':';
    campo += $('#fkcorreiosg2_servicos_cep2_' + id).val().replace(/[^0-9]/g,'');
    campo += '/';

    $('#fkcorreiosg2_servicos_cep_' + id).val(campo);
    $('#fkcorreiosg2_servicos_cep1_' + id).val('');
    $('#fkcorreiosg2_servicos_cep2_' + id).val('');
    $('#fkcorreiosg2_servicos_cep1_' + id).focus();
};

function fkcorreiosg2IncluirCepServicosExcluido(id) {

    var campo = '';

    campo = $('#fkcorreiosg2_servicos_cep_excluido_' + id).val();
    campo += $('#fkcorreiosg2_servicos_cep1_excluido_' + id).val().replace(/[^0-9]/g,'');
    campo += ':';
    campo += $('#fkcorreiosg2_servicos_cep2_excluido_' + id).val().replace(/[^0-9]/g,'');
    campo += '/';

    $('#fkcorreiosg2_servicos_cep_excluido_' + id).val(campo);
    $('#fkcorreiosg2_servicos_cep1_excluido_' + id).val('');
    $('#fkcorreiosg2_servicos_cep2_excluido_' + id).val('');
    $('#fkcorreiosg2_servicos_cep1_excluido_' + id).focus();
};

function fkcorreiosg2IncluirCepFreteGratis(id) {

    var campo = '';

    campo = $('#fkcorreiosg2_frete_gratis_cep_' + id).val();
    campo += $('#fkcorreiosg2_frete_gratis_cep1_' + id).val().replace(/[^0-9]/g,'');
    campo += ':';
    campo += $('#fkcorreiosg2_frete_gratis_cep2_' + id).val().replace(/[^0-9]/g,'');
    campo += '/';

    $('#fkcorreiosg2_frete_gratis_cep_' + id).val(campo);
    $('#fkcorreiosg2_frete_gratis_cep1_' + id).val('');
    $('#fkcorreiosg2_frete_gratis_cep2_' + id).val('');
    $('#fkcorreiosg2_frete_gratis_cep1_' + id).focus();
};

function fkcorreiosg2IncluirCepFreteGratisExcluido(id) {

    var campo = '';

    campo = $('#fkcorreiosg2_frete_gratis_cep_excluido_' + id).val();
    campo += $('#fkcorreiosg2_frete_gratis_cep1_excluido_' + id).val().replace(/[^0-9]/g,'');
    campo += ':';
    campo += $('#fkcorreiosg2_frete_gratis_cep2_excluido_' + id).val().replace(/[^0-9]/g,'');
    campo += '/';

    $('#fkcorreiosg2_frete_gratis_cep_excluido_' + id).val(campo);
    $('#fkcorreiosg2_frete_gratis_cep1_excluido_' + id).val('');
    $('#fkcorreiosg2_frete_gratis_cep2_excluido_' + id).val('');
    $('#fkcorreiosg2_frete_gratis_cep1_excluido_' + id).focus();
};

function fkcorreiosg2IncluirProdutosFreteGratis(id) {

    var campo = "";

    campo = $("#fkcorreiosg2_frete_gratis_produtos_" + id).val();
    campo += $('#fkcorreiosg2_frete_gratis_produto_' + id).val().replace(/[^0-9]/g,'');
    campo += "/";

    $("#fkcorreiosg2_frete_gratis_produtos_" + id).val(campo);
    $("#fkcorreiosg2_frete_gratis_produto_" + id).val("");
    $("#fkcorreiosg2_frete_gratis_produto_" + id).focus();
};

function fkcorreiosg2Marcar(idClass) {
    $('.' + idClass).each(
        function(){
            $(this).attr('checked', true);
        }
    );
};

function fkcorreiosg2Desmarcar(idClass) {
    $('.' + idClass).each(
        function(){
            $(this).attr('checked', false);
        }
    );
};

function fkcorreiosg2TabOffGeral(urlImg, urlFuncoes, idEspCorreios) {

    var tipo = '';
    var erro = false;

    // Recupera todos objetos da classe
    $(".fkcorreiosg2_tab_offline_" + idEspCorreios).each(function(){

        // Recupera o controle sendo executado atraves do seu id
        var controle = $(this).attr("id").toString();

        // Verifica o tipo (cidade, capital ou interior)
        if (controle.indexOf('capital') != -1) {
            tipo = 'capital';
        }else {
            if (controle.indexOf('interior') != -1) {
                tipo = 'interior';
            }else {
                tipo = 'cidade'
            }
        }

        // Recupera id da tabela Tabelas Offline
        var pos = controle.lastIndexOf('_');

        if (pos != -1) {
            // id da tabela Tabelas Offline
            var idTabOffline = controle.substring(parseInt(pos) + 1);

            // Foco no controle sendo executado
            $("#" + controle).focus();

            // Limpa o controle sendo executado
            $("#" + controle).val("");

            // Imagem Processando
            $("#fkcorreiosg2_tab_offline_img_" + tipo + "_" + idTabOffline).attr("src", urlImg + "processando_32.gif");
            $("#fkcorreiosg2_tab_offline_img_" + tipo + "_" + idTabOffline).css("display", "block");

            // Chama funcoes.php
            $.ajax({
                type: "POST",
                async: false,
                url: urlFuncoes,
                data: {func: "1", idEspCorreios: idEspCorreios, idTabOffline: idTabOffline, tipo: tipo}
            }).done(function(retorno) {

                retorno = retorno.trim();

                if (retorno.substr(0, 4) != "erro") {

                    // Recupera prazo de entrega e tabela
                    var pos = retorno.indexOf('|');
                    var prazoEntrega = retorno.substring(0, pos);
                    var tabOffline = retorno.substring(parseInt(pos) + 1);

                    // Preenche o prazo de entrega
                    $("#fkcorreiosg2_tab_offline_prazo_" + tipo + "_" + idTabOffline).val(prazoEntrega);

                    // Preenche a tabela offline
                    $("#" + controle).val(tabOffline);

                    // Imagem ok
                    $("#fkcorreiosg2_tab_offline_img_" + tipo + "_" + idTabOffline).attr("src", urlImg + "ok_32.png");
                }else {
                    // Imagem erro
                    $("#fkcorreiosg2_tab_offline_img_" + tipo + "_" + idTabOffline).attr("src", urlImg + "erro_32.png");
                    erro = true;
                }
            });
        }else {
            erro = true;
        }

    });

    $("#fkcorreiosg2_tab_offline_status_" + idEspCorreios).css("display", "block");

    if (erro == false) {
        $("#fkcorreiosg2_tab_offline_status_" + idEspCorreios).css("color","green");
        $("#fkcorreiosg2_tab_offline_status_" + idEspCorreios).html("Tabelas processadas com sucesso");
    }else {
        $("#fkcorreiosg2_tab_offline_status_" + idEspCorreios).css("color","red");
        $("#fkcorreiosg2_tab_offline_status_" + idEspCorreios).html("Existem tabelas com erros. Favor verificar e refazê-las");
    }

    $("html, body").animate({ scrollTop: 0 }, "slow");
}

function fkcorreiosg2TabOffEspecifica(controle, urlImg, urlFuncoes, idEspCorreios) {

    var tipo = '';
    var erro = false;

    // Verifica o tipo (cidade, capital ou interior)
    if (controle.indexOf('capital') != -1) {
        tipo = 'capital';
    }else {
        if (controle.indexOf('interior') != -1) {
            tipo = 'interior';
        }else {
            tipo = 'cidade'
        }
    }

    // Recupera id da tabela Tabelas Offline
    var pos = controle.lastIndexOf('_');

    if (pos != -1) {
        // id da tabela Tabelas Offline
        var idTabOffline = controle.substring(parseInt(pos) + 1);

        // Foco no controle sendo executado
        $("#" + controle).focus();

        // Limpa o controle sendo executado
        $("#" + controle).val("");

        // Imagem Processando
        $("#fkcorreiosg2_tab_offline_img_" + tipo + "_" + idTabOffline).attr("src", urlImg + "processando_32.gif");
        $("#fkcorreiosg2_tab_offline_img_" + tipo + "_" + idTabOffline).css("display", "block");

        // Chama funcoes.php
        $.ajax({
            type: "POST",
            async: false,
            url: urlFuncoes,
            data: {func: "1", idEspCorreios: idEspCorreios, idTabOffline: idTabOffline, tipo: tipo}
        }).done(function(retorno) {

            retorno = retorno.trim();

            if (retorno.substr(0, 4) != "erro") {

                // Recupera prazo de entrega e tabela
                var pos = retorno.indexOf('|');
                var prazoEntrega = retorno.substring(0, pos);
                var tabOffline = retorno.substring(parseInt(pos) + 1);

                // Preenche o prazo de entrega
                $("#fkcorreiosg2_tab_offline_prazo_" + tipo + "_" + idTabOffline).val(prazoEntrega);

                // Preenche a tabela offline
                $("#" + controle).val(tabOffline);

                // Imagem ok
                $("#fkcorreiosg2_tab_offline_img_" + tipo + "_" + idTabOffline).attr("src", urlImg + "ok_32.png");
            }else {
                // Imagem erro
                $("#fkcorreiosg2_tab_offline_img_" + tipo + "_" + idTabOffline).attr("src", urlImg + "erro_32.png");
                erro = true;
            }
        });
    }else {
        erro = true;
    }

}


