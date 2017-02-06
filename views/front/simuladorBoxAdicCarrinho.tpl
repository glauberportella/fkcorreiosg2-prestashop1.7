
<div class="fkcorreiosg2-box fkcorreiosg2-clear" style="background-color: {$fkcorreiosg2['corFundo']};" id="fkcorreiosg2_adic_carrinho">

    <p class="fkcorreiosg2-titulo" style="color: {$fkcorreiosg2['corFonteTitulo']};">{l s='Cálculo do Frete' mod='fkcorreiosg2'}</p>

    <div class="fkcorreiosg2-form">
        <input class="fkcorreiosg2-mask-cep fkcorreiosg2-col-lg-40" type="text" name="fkcorreiosg2_cep_adic_carrinho" id="fkcorreiosg2_cep_adic_carrinho" placeholder="Informe o CEP" value="{$fkcorreiosg2['cepCookie']}"/>
        <a class="fkcorreiosg2-button" style="background-color: {$fkcorreiosg2['corBotao']}; color: {$fkcorreiosg2['corFonteBotao']};" id="fkcorreiosg2_link_cep_adic_carrinho" href="{$link->getPageLink('product', true)|escape:'html'}&id_product={$fkcorreiosg2['idProduto']}&origem=adicCarrinho">{l s='Calcular frete' mod='fkcorreiosg2'}</a>
    </div>

    <p class="fkcorreiosg2-faixa-msg" id="fkcorreiosg2-adic-carrinho-faixa-msg" style="background-color: {$fkcorreiosg2['corFaixaMsg']}; color: {$fkcorreiosg2['corFonteMsg']};">{$fkcorreiosg2['msgStatus']}</p>

    {if isset($fkcorreiosg2['transportadoras'])}
        <div class="fkcorreiosg2-transportadoras">
            <div {if $fkcorreiosg2['lightBox'] == true} class="fkcorreiosg2-fancybox" {/if}>
                <table>
                    {foreach $fkcorreiosg2['transportadoras'] as $transp}
                        <tr>
                            <td class="fkcorreiosg2-responsivo">
                                <img src="{$transp['urlLogo']}" alt="{$transp['nomeTransportadora']}"/>
                            </td>
                            <td id="fkcorreiosg2-adic-carrinho-nome">
                                <b>{$transp['nomeTransportadora']}</b>
                                <br>
                                {$transp['prazoEntrega']}
                            </td>
                            <td id="fkcorreiosg2-adic-carrinho-valor">
                                {convertPrice price=$transp['valorFrete']}
                            </td>
                        </tr>
                    {/foreach}
                </table>

                {if $fkcorreiosg2['msgTransp'] != ''}
                    <div class="fkcorreiosg2-msg-transp" id="fkcorreiosg2-adic-carrinho-msg-transp">
                        {$fkcorreiosg2['msgTransp']}
                    </div>
                {/if}

            </div>
        </div>
    {/if}

</div>

