<div class="row">
    <div class="panel">
        <div class="panel-heading">
            <h4>Prospects par employés et tracers</h4>
        </div>
        <div class="panel-body">
            <table class="table table-stripped">
                <thead>
                <tr>
                    <th class="col-xs-2"><strong>Employés</strong></th>
                    {foreach item=tracer key=key from=$tracers}
                    <th class="col-xs-1">{$tracer}</th>
                    {/foreach}
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                {foreach item=employe key=key from=$employees}
                {if !empty($employe['tracersByGroups'])}
                <tr>
                    <td>{$employe['firstname']} {$employe['lastname']}</td>
                    {foreach item=tracer key=key from=$tracers}
                            <td>{$employe['tracersByGroups'][$tracer]}</td>
                    {/foreach}
                        <td><strong>{$employe['TotalCountTracersByGroups']}</strong></td>
                </tr>
                {/if}
                {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>