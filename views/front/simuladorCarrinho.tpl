
<div class="fkcorreiosg2-box fkcorreiosg2-clear" style="background-color: {$fkcorreiosg2['corFundo']}; border: {$fkcorreiosg2['borda']}; border-radius: {$fkcorreiosg2['raioBorda']}; width: {$fkcorreiosg2['largura']};">

    <p class="fkcorreiosg2-titulo" id="fkcorreiosg2-carrinho-titulo" style="color: {$fkcorreiosg2['corFonteTitulo']};">{l s='Cálculo do Frete' mod='fkcorreiosg2'}</p>

    <form action="#" method="post">
        <div class="fkcorreiosg2-form">
            <input class="fkcorreiosg2-mask-cep fkcorreiosg2-col-lg-15" type="text" name="fkcorreiosg2_cep" placeholder="Informe o CEP" value="{$fkcorreiosg2['cepCookie']}"/>
            <input class="fkcorreiosg2-button" style="background-color: {$fkcorreiosg2['corBotao']}; color: {$fkcorreiosg2['corFonteBotao']};" type="submit" name="btnSubmit" value="{l s='Calcular frete' mod='fkcorreiosg2'}"/>
        </div>
    </form>

    <p class="fkcorreiosg2-faixa-msg" id="fkcorreiosg2-carrinho-faixa-msg" style="background-color: {$fkcorreiosg2['corFaixaMsg']}; color: {$fkcorreiosg2['corFonteMsg']};">{$fkcorreiosg2['msgStatus']}</p>

    {if isset($fkcorreiosg2['transportadoras'])}
        <div class="fkcorreiosg2-transportadoras">
            <table>
                {foreach $fkcorreiosg2['transportadoras'] as $transp}
                    <tr>
                        <td class="fkcorreiosg2-responsivo">
                            <img src="{$transp['urlLogo']}" alt="{$transp['nomeTransportadora']}"/>
                        </td>
                        <td id="fkcorreiosg2-carrinho-nome">
                            <b>{$transp['nomeTransportadora']}</b>
                            <br>
                            {$transp['prazoEntrega']}
                        </td>
                        <td id="fkcorreiosg2-carrinho-valor">
                            {convertPrice price=$transp['valorFrete']}
                        </td>
                    </tr>
                {/foreach}
            </table>

            {if $fkcorreiosg2['msgTransp'] != ''}
                <div class="fkcorreiosg2-msg-transp">
                    {$fkcorreiosg2['msgTransp']}
                </div>
            {/if}

        </div>
    {/if}

</div>
<div class="fkcorreiosg2-clear"></div>