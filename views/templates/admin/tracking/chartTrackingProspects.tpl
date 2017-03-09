<script>
    var countTrackingBetweenDateJsonHeader = {$countTrackingBetweenDateJsonHeader};
    var countTrackingBetweenDateJsonValue = {$countTrackingBetweenDateJsonValue};
</script>
<div class="row">
    <div id="chartTrackingProspects" class="panel panel-primary col-xs-6">
        <div class="panel-heading">
            <h4>
                <i class="icon-chevron-down toggleChart" data-canvas="countTrackingBetweenDate" title="chartTrackingProspects"></i> Nombre de prospects par numéro de tracking du {$dateBetween['debut']|date_format:'%d/%m/%Y'} au
                {$dateBetween['fin']|date_format:'%d/%m/%Y'}</h4>
        </div>
        <div class="panel-body">
            <table class="table table-striped tableChart">
                <thead class="thead">
                <tr>
                    {foreach item=item from=$countTrackingBetweenDate}
                    <th class="text-center">{$item['tracer']}</th>
                    {/foreach}
                </tr>
                </thead>
                <tbody>
                <tr>
                    {foreach item=item from=$countTrackingBetweenDate}
                    <td class="text-center">{((100/$countTrackingNbrProspects)*$item['total'])|string_format:"%.2f"} %</td>
                    {/foreach}
                </tr>
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