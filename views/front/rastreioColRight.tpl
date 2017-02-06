
<div class="fkcorreiosg2-rastreio-coluna fkcorreiosg2-clear">
    <div class="fkcorreiosg2-rastreio-titulo">
        {l s='Rastreio Encomenda' mod='fkcorreiosg2'}
    </div>

    <div class="fkcorreiosg2-rastreio-conteudo">

        <input type="text" name="fkcorreiosg2_rastreio_coluna_right" id="fkcorreiosg2_rastreio_coluna_right" placeholder="{l s="Informe o código de rastreio" mod="fkcorreiosg2"}" value="">
        <button class="fkcorreiosg2-button-rastreio" type="button" onclick="mostraRastreio($('#fkcorreiosg2_rastreio_coluna_right').val(), '{$fkcorreiosg2_rastreio["urlFuncoesRastreio"]}')">
            <span>Ok</span>
        </button>

    </div>
</div>