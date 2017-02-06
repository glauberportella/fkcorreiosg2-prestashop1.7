
{assign var="class_tab_2" value=""}
{assign var="class_tab_3" value=""}
{assign var="class_tab_4" value=""}
{assign var="class_tab_5" value=""}
{assign var="class_tab_6" value=""}
{assign var="class_tab_7" value=""}
{assign var="class_tab_8" value=""}
{assign var="class_tab_9" value=""}
{assign var="class_tab_10" value=""}

{if $fkcorreiosg2['tabSelect'] == "2"}
    {assign var="class_tab_2" value="active"}
{elseif $fkcorreiosg2['tabSelect'] == "3"}
    {assign var="class_tab_3" value="active"}
{elseif $fkcorreiosg2['tabSelect'] == "4"}
    {assign var="class_tab_4" value="active"}
{elseif $fkcorreiosg2['tabSelect'] == "5"}
    {assign var="class_tab_5" value="active"}
{elseif $fkcorreiosg2['tabSelect'] == "6"}
    {assign var="class_tab_6" value="active"}
{elseif $fkcorreiosg2['tabSelect'] == "7"}
    {assign var="class_tab_7" value="active"}
{elseif $fkcorreiosg2['tabSelect'] == "8"}
    {assign var="class_tab_8" value="active"}
{elseif $fkcorreiosg2['tabSelect'] == "9"}
    {assign var="class_tab_9" value="active"}
{elseif $fkcorreiosg2['tabSelect'] == "10"}
    {assign var="class_tab_10" value="active"}
{else}
    {assign var="class_tab_2" value="active"}
{/if}

<ul class="nav nav-tabs" data-tabs="tabs">
    <li class="{$class_tab_2}"><a href="#tab_2" data-toggle="tab">{l s="Configuração geral" mod="fkcorreiosg2"}</a></li>
    <li class="{$class_tab_3}"><a href="#tab_3" data-toggle="tab">{l s="Cadastro CEP" mod="fkcorreiosg2"}</a></li>
    <li class="{$class_tab_4}"><a href="#tab_4" data-toggle="tab">{l s="Cadastro Embalagens" mod="fkcorreiosg2"}</a></li>
    <li class="{$class_tab_5}"><a href="#tab_5" data-toggle="tab">{l s="Especificações Correios" mod="fkcorreiosg2"}</a></li>
    <li class="{$class_tab_6}"><a href="#tab_6" data-toggle="tab">{l s="Serviços" mod="fkcorreiosg2"}</a></li>
    <li class="{$class_tab_7}"><a href="#tab_7" data-toggle="tab">{l s="Frete Grátis" mod="fkcorreiosg2"}</a></li>
    <li class="{$class_tab_8}"><a href="#tab_8" data-toggle="tab">{l s="Tabelas Offline" mod="fkcorreiosg2"}</a></li>
    <li class="{$class_tab_9}"><a href="#tab_9" data-toggle="tab">{l s="Complementos" mod="fkcorreiosg2"}</a></li>
    <li class="{$class_tab_10}"><a href="#tab_10" data-toggle="tab">{l s="Informações da Configuração" mod="fkcorreiosg2"}</a></li>
</ul>
<div class="tab-content">

    <div class="tab-pane {$class_tab_2}" id="tab_2">
        {include file="{$fkcorreiosg2['pathInclude']}{$tab_2['nameTpl']}"}
    </div>
    <div class="tab-pane {$class_tab_3}" id="tab_3">
        {include file="{$fkcorreiosg2['pathInclude']}{$tab_3['nameTpl']}"}
    </div>
    <div class="tab-pane {$class_tab_4}" id="tab_4">
        {include file="{$fkcorreiosg2['pathInclude']}{$tab_4['nameTpl']}"}
    </div>
    <div class="tab-pane {$class_tab_5}" id="tab_5">
        {include file="{$fkcorreiosg2['pathInclude']}{$tab_5['nameTpl']}"}
    </div>
    <div class="tab-pane {$class_tab_6}" id="tab_6">
        {include file="{$fkcorreiosg2['pathInclude']}{$tab_6['nameTpl']}"}
    </div>
    <div class="tab-pane {$class_tab_7}" id="tab_7">
        {include file="{$fkcorreiosg2['pathInclude']}{$tab_7['nameTpl']}"}
    </div>
    <div class="tab-pane {$class_tab_8}" id="tab_8">
        {include file="{$fkcorreiosg2['pathInclude']}{$tab_8['nameTpl']}"}
    </div>
    <div class="tab-pane {$class_tab_9}" id="tab_9">
        {include file="{$fkcorreiosg2['pathInclude']}{$tab_9['nameTpl']}"}
    </div>
    <div class="tab-pane {$class_tab_10}" id="tab_10">
        {include file="{$fkcorreiosg2['pathInclude']}{$tab_10['nameTpl']}"}
    </div>

</div>


