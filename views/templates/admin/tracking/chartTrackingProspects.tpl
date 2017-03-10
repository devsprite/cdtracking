<script>
    var countTrackingBetweenDateJsonHeader = {$countTrackingBetweenDateJsonHeader};
    var countTrackingBetweenDateJsonValue = {$countTrackingBetweenDateJsonValue};
</script>
<div class="row">
    <div id="chartTrackingProspects" class="panel panel-primary col-xs-6">
        <div class="panel-heading">
            <h4>
                <i class="icon-chevron-down toggleChart" data-canvas="countTrackingBetweenDate"
                   title="chartTrackingProspects"></i> Nombre de prospect du
                {$dateBetween['debut']|date_format:'%d/%m/%Y'} au
                {$dateBetween['fin']|date_format:'%d/%m/%Y'}
                <a class="btn btn-primary pull-right" href="{$LinkFile}&export_csv=3"><i class="icon-file-text"></i></a></h4>
        </div>
        <div class="panel-body">
            <table class="table table-striped tableChart">
                <thead>
                <tr>
                    <th>Tracer</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-right">Répartition</th>
                </tr>
                </thead>
                <tbody>
                {foreach item=item from=$countTrackingBetweenDate}
                <tr>
                    <td>{$item['tracer']}</td>
                    <td class="text-center">{$item['total']}</td>
                    <td class="text-right">{$item['repartition']} %</td>
                </tr>
                {/foreach}
                <tr>
                    <td colspan="3">Total : <strong>{$countTrackingNbrProspects}</strong></td>
                </tr>
                </tbody>
            </table>
            <div class="canvasChart">
                <canvas id="countTrackingBetweenDate" height="250"></canvas>
            </div>
        </div>
    </div>