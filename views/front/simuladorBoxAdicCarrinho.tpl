<div class="fkcorreiosg2-box fkcorreiosg2-clear" style="background-color: {$fkcorreiosg2['corFundo']};" id="fkcorreiosg2_adic_carrinho">

    <p class="fkcorreiosg2-titulo" style="color: {$fkcorreiosg2['corFonteTitulo']};">{l s='Cálculo do Frete' mod='fkcorreiosg2'}</p>

    <div class="fkcorreiosg2-form form-inline">
        <div class="form-group">
            <input class="fkcorreiosg2-mask-cep fkcorreiosg2-col-lg-40 form-control" type="text" name="fkcorreiosg2_cep_adic_carrinho" id="fkcorreiosg2_cep_adic_carrinho" placeholder="Informe o CEP" value="{$fkcorreiosg2['cepCookie']}"/>
            {* href original: {$link->getPageLink('product', true)}&id_product={$fkcorreiosg2['idProduto']}&origem=adicCarrinho *}        
        </div>
        <a class="fkcorreiosg2-button btn" style="background-color: {$fkcorreiosg2['corBotao']}; color: {$fkcorreiosg2['corFonteBotao']};" id="fkcorreiosg2_link_cep_adic_carrinho" href="{url entity='product' id=$fkcorreiosg2['idProduto'] params=['origem' => 'adicCarrinho']}">{l s='Calcular frete' mod='fkcorreiosg2'}</a>
    </div>

    <p class="fkcorreiosg2-faixa-msg" id="fkcorreiosg2-adic-carrinho-faixa-msg" style="background-color: {$fkcorreiosg2['corFaixaMsg']}; color: {$fkcorreiosg2['corFonteMsg']};">{$fkcorreiosg2['msgStatus']}</p>

    {if isset($fkcorreiosg2['transportadoras'])}
        <div class="fkcorreiosg2-transportadoras">
            <div {if $fkcorreiosg2['lightBox'] == true} class="fkcorreiosg2-fancybox" {/if}>
                    {foreach $fkcorreiosg2['transportadoras'] as $transp}
                        <div class="row">
                            <div class="fkcorreiosg2-responsivo col-md-4">
                                <img class="img-responsive" src="{$transp['urlLogo']}" alt="{$transp['nomeTransportadora']}"/>
                            </div>
                            <div class="col-md-4" id="fkcorreiosg2-adic-carrinho-nome">
                                <small><b>{$transp['nomeTransportadora']}</b></small>
                                <br>
                                <small>{$transp['prazoEntrega']}</small>
                            </div>
                            <div class="col-md-4" id="fkcorreiosg2-adic-carrinho-valor">
                                {$transp['valorFreteFmt']}
                            </div>
                        </div>
                    {/foreach}

                {if $fkcorreiosg2['msgTransp'] != ''}
                <div class="row">
                    <div class="fkcorreiosg2-msg-transp" id="fkcorreiosg2-adic-carrinho-msg-transp col-md-12">
                        {$fkcorreiosg2['msgTransp']}
                    </div>
                </div>
                {/if}

            </div>
        </div>
    {/if}

</div>

