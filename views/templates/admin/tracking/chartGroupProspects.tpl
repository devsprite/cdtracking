<div class="panel">
    <div class="panel-heading">
        <h4>Prospects par groupes</h4>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th class="col-xs-1">Groupes</th>
                {foreach item=group key=name from=$tracers}
                <th class="col-xs-1">{$name|lower|ucfirst|truncate:12}</th>
                {/foreach}
            </tr>
            </thead>
            <tbody>
            {foreach item=group key=name from=$prospectsByGroups}
            <tr>
                <td>{$name}</td>
                {foreach item=tracer from=$group}
                <td>{$tracer[1]}</td>
                {/foreach}
            </tr>
            {/foreach}

            </tbody>
        </table>
    </div>
</div>