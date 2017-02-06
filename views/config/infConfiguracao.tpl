
<div class="fkcorreiosg2-panel">

    <div class="fkcorreiosg2-panel-heading">
        {l s="Informações da Configuração" mod="fkcorreiosg2"}
    </div>

    <div class="fkcorreiosg2-panel-header">
        <button type="button" value="1" name="btnAjuda" class="fkcorreiosg2-button fkcorreiosg2-float-right" onClick="window.open('http://www.modulosfk.com.br/modulosfk/ajuda/fkcorreiosg2_v1_0_0.pdf','Janela','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=900,height=500,left=500,top=150'); return false;">
            <i class="process-icon-help"></i>
            {l s="Ajuda" mod="fkcorreiosg2"}
        </button>
    </div>

    <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-60 fkcorreiosg2-sub-panel" id="fkcorreiosg2_inf_configuracao">

        <div class="fkcorreiosg2-panel-heading">
            {l s="PHP" mod="fkcorreiosg2"}
        </div>

        <div class="fkcorreiosg2-inf-configuracao">
            <label class="fkcorreiosg2-label">
                {l s="SOAP:" mod="fkcorreiosg2"}
            </label>

            {if $tab_10['soap']}
                <img src="{$tab_10['urlImg']}ok_24.png">
            {else}
                <img src="{$tab_10['urlImg']}erro_24.png">
                <span class="fkcorreiosg2-color-red">{$tab_10['msgSoap']}</span>
            {/if}
        </div>

    </div>

    <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-60 fkcorreiosg2-sub-panel">

        <div class="fkcorreiosg2-panel-heading">
            {l s="Prestashop" mod="fkcorreiosg2"}
        </div>

        <div class="row fkcorreiosg2-inf-configuracao">
            <label class="fkcorreiosg2-label">
                {l s="Módulos não Nativos:" mod="fkcorreiosg2"}
            </label>

            {if $tab_10['modulosNativos']}
                <img src="{$tab_10['urlImg']}ok_24.png">
            {else}
                <img src="{$tab_10['urlImg']}erro_24.png">
                <span class="fkcorreiosg2-color-red">{$tab_10['msgModulosNativos']}</span>
            {/if}
        </div>
        <div class="row fkcorreiosg2-inf-configuracao">
            <label class="fkcorreiosg2-label">
                {l s="Overrides:" mod="fkcorreiosg2"}
            </label>

            {if $tab_10['overrides']}
                <img src="{$tab_10['urlImg']}ok_24.png">
            {else}
                <img src="{$tab_10['urlImg']}erro_24.png">
                <span class="fkcorreiosg2-color-red">{$tab_10['msgOverrides']}</span>
            {/if}
        </div>
        <div class="row fkcorreiosg2-inf-configuracao">
            <label class="fkcorreiosg2-label">
                {l s="Custos de Envio:" mod="fkcorreiosg2"}
            </label>

            {if $tab_10['custosEnvio']}
                <img src="{$tab_10['urlImg']}ok_24.png">
            {else}
                <img src="{$tab_10['urlImg']}alerta_24.png">
                <span class="fkcorreiosg2-color-blue">{$tab_10['msgCustosEnvio']}</span>
            {/if}
        </div>
        <div class="row fkcorreiosg2-inf-configuracao">
            <label class="fkcorreiosg2-label">
                {l s="Frete Grátis por Valor:" mod="fkcorreiosg2"}
            </label>

            {if $tab_10['freteGratisValor']}
                <img src="{$tab_10['urlImg']}ok_24.png">
            {else}
                <img src="{$tab_10['urlImg']}erro_24.png">
                <span class="fkcorreiosg2-color-red">{$tab_10['msgFreteGratisValor']}</span>
            {/if}
        </div>
        <div class="row fkcorreiosg2-inf-configuracao">
            <label class="fkcorreiosg2-label">
                {l s="Frete Grátis por Peso:" mod="fkcorreiosg2"}
            </label>

            {if $tab_10['freteGratisPeso']}
                <img src="{$tab_10['urlImg']}ok_24.png">
            {else}
                <img src="{$tab_10['urlImg']}erro_24.png">
                <span class="fkcorreiosg2-color-red">{$tab_10['msgFreteGratisPeso']}</span>
            {/if}
        </div>
        <div class="row fkcorreiosg2-inf-configuracao">
            <label class="fkcorreiosg2-label">
                {l s="Regiões:" mod="fkcorreiosg2"}
            </label>

            {if $tab_10['regioes']}
                <img src="{$tab_10['urlImg']}ok_24.png">
            {else}
                <img src="{$tab_10['urlImg']}erro_24.png">
                <span class="fkcorreiosg2-color-red">{$tab_10['msgRegioes']}</span>
            {/if}
        </div>
        <div class="row fkcorreiosg2-inf-configuracao">
            <label class="fkcorreiosg2-label">
                {l s="Dimensões e Peso Zerados:" mod="fkcorreiosg2"}
            </label>

            {if $tab_10['dimPesoZero']}
                <img src="{$tab_10['urlImg']}ok_24.png">
            {else}
                <img src="{$tab_10['urlImg']}erro_24.png">
                <span class="fkcorreiosg2-color-red">{$tab_10['msgDimPesoZero']}</span>
            {/if}
        </div>
        <div class="row fkcorreiosg2-inf-configuracao">
            <label class="fkcorreiosg2-label">
                {l s="Dimensões e Peso maior que o permitido:" mod="fkcorreiosg2"}
            </label>

            {if $tab_10['dimPesoMaior']}
                <img src="{$tab_10['urlImg']}ok_24.png">
            {else}
                <img src="{$tab_10['urlImg']}alerta_24.png">
                <span class="fkcorreiosg2-color-blue">{$tab_10['msgDimPesoMaior']}</span>
            {/if}
        </div>
        <div class="row fkcorreiosg2-inf-configuracao">
            <label class="fkcorreiosg2-label">
                {l s="Gestão Avançada de Estoque:" mod="fkcorreiosg2"}
            </label>

            {if $tab_10['gestaoAvancadaEstoque']}
                <img src="{$tab_10['urlImg']}ok_24.png">
            {else}
                <img src="{$tab_10['urlImg']}alerta_24.png">
                <span class="fkcorreiosg2-color-blue">{$tab_10['msgGestaoAvancadaEstoque']}</span>
            {/if}
        </div>

    </div>

    <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-60 fkcorreiosg2-sub-panel">

        <div class="fkcorreiosg2-panel-heading">
            {l s="Módulo" mod="fkcorreiosg2"}
        </div>

        <div class="row fkcorreiosg2-inf-configuracao">
            <label class="fkcorreiosg2-label">
                {l s="Meu CEP:" mod="fkcorreiosg2"}
            </label>

            {if $tab_10['meuCep']}
                <img src="{$tab_10['urlImg']}ok_24.png">
            {else}
                <img src="{$tab_10['urlImg']}erro_24.png">
                <span class="fkcorreiosg2-color-red">{$tab_10['msgMeuCep']}</span>
            {/if}
        </div>
        <div class="row fkcorreiosg2-inf-configuracao">
            <label class="fkcorreiosg2-label">
                {l s="Minha Cidade:" mod="fkcorreiosg2"}
            </label>

            {if $tab_10['minhaCidade']}
                <img src="{$tab_10['urlImg']}ok_24.png">
            {else}
                <img src="{$tab_10['urlImg']}erro_24.png">
                <span class="fkcorreiosg2-color-red">{$tab_10['msgMinhaCidade']}</span>
            {/if}
        </div>
        <div class="row fkcorreiosg2-inf-configuracao">
            <label class="fkcorreiosg2-label">
                {l s="Serviços:" mod="fkcorreiosg2"}
            </label>

            {if $tab_10['servicos']}
                <img src="{$tab_10['urlImg']}ok_24.png">
            {else}
                <img src="{$tab_10['urlImg']}alerta_24.png">
                <span class="fkcorreiosg2-color-blue">{$tab_10['msgServicos']}</span>
            {/if}
        </div>
        <div class="row fkcorreiosg2-inf-configuracao">
            <label class="fkcorreiosg2-label">
                {l s="Tabelas Offline:" mod="fkcorreiosg2"}
            </label>

            {if $tab_10['tabOffline']}
                <img src="{$tab_10['urlImg']}ok_24.png">
            {else}
                <img src="{$tab_10['urlImg']}alerta_24.png">
                <span class="fkcorreiosg2-color-blue">{$tab_10['msgTabOffline']}</span>
            {/if}
        </div>

    </div>

</div>