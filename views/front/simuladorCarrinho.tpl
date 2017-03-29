<div class="row">
    <div class="col-md-12">
        <a id="fkcorreiosg2-abrir-simulador" class="btn btn-primary btn-block" href="#">Simular Frete</a>
        <div id="fkcorreiosg2-simulador" class="fkcorreiosg2-box fkcorreiosg2-clear" style="background-color: {$fkcorreiosg2['corFundo']}; border: {$fkcorreiosg2['borda']}; border-radius: {$fkcorreiosg2['raioBorda']}; display: none;">

            <form class="form-inline" action="#" method="post">
              <div class="fkcorreiosg2-form form-group">
                <input class="fkcorreiosg2-mask-cep fkcorreiosg2-col-lg-40 form-control" type="text" name="fkcorreiosg2_cep" placeholder="Informe o CEP" value="{$fkcorreiosg2['cepCookie']}"/>
              </div>
                <input class="fkcorreiosg2-button btn" style="background-color: {$fkcorreiosg2['corBotao']}; color: {$fkcorreiosg2['corFonteBotao']};" type="submit" name="btnSubmit" value="{l s='Calcular' mod='fkcorreiosg2'}"/>
            </form>

            <p class="fkcorreiosg2-faixa-msg" id="fkcorreiosg2-carrinho-faixa-msg" style="background-color: {$fkcorreiosg2['corFaixaMsg']}; color: {$fkcorreiosg2['corFonteMsg']};">{$fkcorreiosg2['msgStatus']}</p>

            {if isset($fkcorreiosg2['transportadoras'])}
                {foreach $fkcorreiosg2['transportadoras'] as $transp}
                    <div class="fkcorreiosg2-transportadoras row">
                        <div class="col-md-4">
                            <img class="img-responsive" src="{$transp['urlLogo']}" alt="{$transp['nomeTransportadora']}"/>
                        </div>
                        <div class="col-md-4">
                            <small><b>{$transp['nomeTransportadora']}</b></small>
                            <br>
                            <small>{$transp['prazoEntrega']}</small>
                        </div>
                        <div class="col-md-4">
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

                    {*
                    <table class="table">
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
                                    {$transp['valorFreteFmt']}
                                </td>
                            </tr>
                        {/foreach}
                    </table>
                    *}

            {/if}

        </div>
        <div class="fkcorreiosg2-clear"></div>
    </div>
</div>
