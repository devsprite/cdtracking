<div class="panel">
    <div id="chartGroupProspects" class="panel-heading">
        <h4><i class="icon-chevron-right" title="chartGroupProspects"></i> Prospects par groupes et tracers</h4>
        <p>Attention : un prospect peux appartenir à plusieurs groupes en même temps</p>
    </div>
    <div class="panel-body">
        <div>
            <a class="btn btn-primary" href="{$LinkFile}&export_csv=1">CSV</a>
        </div>
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th><strong>Groupes</strong></th>
                {foreach item=group key=name from=$tracers}
                <th colspan="2" class="text-center">{$name|lower|ucfirst|truncate:12}</th>
                {/foreach}
            </tr>
            <tr>
                <th></th>
                {foreach item=group key=name from=$tracers}
                <th class="text-center">Nbr</th>
                <th class="text-center">Répar.</th>
                {/foreach}
            </tr>
            </thead>
            <tbody>
            {foreach item=group key=name from=$prospectsByGroups}
            <tr>
                <td>{$name}</td>
                {foreach item=tracer from=$group}
                <td class="text-center">{($tracer[1])?$tracer[1]:""}</td>
                <td class="text-center">{if $tracer[1] != 0}{$tracer[2]} %{/if}</td>
                {/foreach}
            </tr>
            {/foreach}
            </tbody>
            <tfoot>
            <tr>
                <td title="Chaque prospect est compté une fois mais peux appartenir à plusieurs groupes en même temps,
                            le total peux être inférieur au total de la colonne correspondante">Total</td>
                {foreach item=item from=$tracers}
                <td class="text-center">{$item}</td>
                <td></td>
                {/foreach}
            </tr>
            </tfoot>
        </table>
    </div>
</div>