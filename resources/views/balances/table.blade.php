<h3>Total: ${!! $balanceTotal !!}</h3>
<div class="row">
    @foreach($weeklyBalance as $k=>$balance)
        <div class="col-md-3" style="font-size:13px">Semana {!! $k !!}: ${!! $balance !!}</div>
    @endforeach
</div>
<div class="row">
    <div class="col-md-3" id="dayByday" style="height: 250px;"></div>
    <div class="col-md-3" id="monthBymonth" style="height: 250px;"></div>
    <div class="col-md-3" id="types" style="height: 250px;"></div>
    <div class="col-md-3" id="bartypes" style="height: 250px;"></div>
</div>
<table class="table table-responsive" id="balances-table">
    <thead>
        <th>Monto</th>
        <th>Descripci&oacute;n</th>
        <th>Fecha</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($balances as $balance)
        <tr>
            <td>${!! $balance->amount !!}</td>
            <td>{!! $balance->type !!}</td>
            <td>{!! date('d F y',strtotime($balance->created_at)) !!}</td>
            <td>
                {!! Form::open(['route' => ['balances.destroy', $balance->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('balances.show', [$balance->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('balances.edit', [$balance->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function(){
        new Morris.Line({
            // ID of the element in which to draw the chart.
            element: 'dayByday',
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.
            data: [
                    @foreach($balancesDay as $k=>$balance)
                { day: '{!! $k !!}', value: {!! $balance !!} },
                @endforeach
            ],
            // The name of the data record attribute that contains x-values.
            xkey: 'day',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['value'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['Valor']
        });

        new Morris.Line({
            // ID of the element in which to draw the chart.
            element: 'monthBymonth',
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.
            data: [
                    @foreach($balancesMonth as $k=>$balance)
                { day: '{!! $k !!}', value: {!! $balance !!} },
                @endforeach
            ],
            // The name of the data record attribute that contains x-values.
            xkey: 'day',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['value'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['Valor']
        });
        new Morris.Donut({
            element: 'types',
            data: [
                @foreach($balancesType as $k=>$balance)
                    { label: '{!! $k !!}', value: {!! $balance !!} },
                @endforeach
            ]
        });
        new Morris.Bar({
            element: 'bartypes',
            data: [
                @foreach($balancesType as $k=>$balance)
                { label: '{!! $k !!}', a: {!! $balance !!} },
                @endforeach
            ],
            xkey: 'label',
            ykeys: 'a',
            labels: 'gastos'
        });

    });

</script>