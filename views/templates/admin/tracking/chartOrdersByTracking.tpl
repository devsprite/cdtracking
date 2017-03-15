<script>
    var trackingProspectsHeader = {$trackingProspectsHeader};
    var trackingProspectsValues = {$trackingProspectsValues};
</script>

<div id="chartOrdersByTracking" class="panel panel-primary col-xs-6">
    <div class="panel-heading">
        <h4><i class="icon-chevron-down toggleChart" data-canvas="trackingProspects" title="chartOrdersByTracking"></i>
            Nbr de Commande du {$dateBetween['debut']|date_format:'%d/%m/%Y'} au
            {$dateBetween['fin']|date_format:'%d/%m/%Y'}
            <a class="btn btn-primary pull-right" href="{$LinkFile}&export_csv=4"><i class="icon-file-text"></i></a></h4>
    </div>
    <div class="panel-body">
        <table class="table table-striped tableChart">
            <thead>
            <tr>
                <th class="">Tracer</th>
                <th class="text-center">Nombre de vente</th>
                <th class="text-right">Taux de transformation</th>
            </tr>
            </thead>
            <tbody>
            {foreach item=item from=$trackingProspects}
            <tr>
                <td class="">{$item['tracer']}</td>
                <td class="text-center">{$item['nombre']}</td>
                <td class="text-right">{$item['taux']} %</td>
            </tr>
            {/foreach}
            <tr>
                <td colspan="3">Total : <strong>{$totalTrackingProspects}</strong></td>
            </tr>
            </tbody>
        </table>
        <div class="canvasChart">
            <canvas id="trackingProspects" height="250"></canvas>
        </div>
    </div>
</div>

</div> <!-- EndRow -->
