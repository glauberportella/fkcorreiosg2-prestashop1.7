
<div class="fkcorreiosg2-box fkcorreiosg2-clear" style="background-color: {$fkcorreiosg2['corFundo']}; border: {$fkcorreiosg2['borda']}; border-radius: {$fkcorreiosg2['raioBorda']}; width: {$fkcorreiosg2['largura']};">

    <p class="fkcorreiosg2-titulo" id="fkcorreiosg2-desc-detalhada-titulo" style="color: {$fkcorreiosg2['corFonteTitulo']};">{l s='Cálculo do Frete' mod='fkcorreiosg2'}</p>

    {* original action url {$link->getPageLink('product', true)}&id_product={$fkcorreiosg2['idProduto']} *}
    <form class="form-inline" action="{url entity='product' id=$fkcorreiosg2['idProduto']}" method="post">
        <div class="fkcorreiosg2-form form-group">
            <input class="fkcorreiosg2-mask-cep fkcorreiosg2-col-lg-15 form-control" type="text" name="fkcorreiosg2_cep" placeholder="Informe o CEP" value="{$fkcorreiosg2['cepCookie']}"/>
        </div>
        <input class="fkcorreiosg2-button btn" style="background-color: {$fkcorreiosg2['corBotao']}; color: {$fkcorreiosg2['corFonteBotao']};" type="submit" name="btnSubmit" value="{l s='Calcular frete' mod='fkcorreiosg2'}"/>
    </form>

    <p class="fkcorreiosg2-faixa-msg" id="fkcorreiosg2-desc-detalhada-faixa-msg" style="background-color: {$fkcorreiosg2['corFaixaMsg']}; color: {$fkcorreiosg2['corFonteMsg']};">{$fkcorreiosg2['msgStatus']}</p>

    {if isset($fkcorreiosg2['transportadoras'])}
        <div class="fkcorreiosg2-transportadoras">
            <div {if $fkcorreiosg2['lightBox'] == true} class="fkcorreiosg2-fancybox" {/if}>
                {foreach $fkcorreiosg2['transportadoras'] as $transp}
                    <div class="row">
                        <div class="col-md-4 fkcorreiosg2-responsivo">
                            <img class="img-responsive" src="{$transp['urlLogo']}" alt="{$transp['nomeTransportadora']}"/>
                        </div>
                        <div class="col-md-4 fkcorreiosg2-desc-detalhada-nome">
                            <small><b>{$transp['nomeTransportadora']}</b></small>
                            <br>
                            <small>{$transp['prazoEntrega']}</small>
                        </div>
                        <div class="col-md-4 fkcorreiosg2-desc-detalhada-valor">
                            {$transp['valorFreteFmt']}
                        </div>
                    </div>
                {/foreach}

                {if $fkcorreiosg2['msgTransp'] != ''}
                <div class="row">
                    <div class="fkcorreiosg2-msg-transp col-md-12">
                        {$fkcorreiosg2['msgTransp']}
                    </div>
                </div>
                {/if}

            </div>
        </div>
    {/if}

</div>
<div class="fkcorreiosg2-clear"></div>
