@extends('layouts.main')
@section('subtitle', "Dashboard")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="row">
	<div class="col-12 col-lg-12 col-xxl-12 d-flex">
		<div class="card flex-fill w-100">
			<div class="card-header">
				<div class="card-actions float-end">
                <div class="col-auto">
						<select class="form-select form-select-sm bg-light border-0" id="revenueMonthSelect" role="tablist">
							<option value="#revenue1709247600">March 2024</option>
							<option value="#revenue1711926000">April 2024</option>
							<option value="#revenue1710194404" selected>All times</option>
						</select>
					</div>
				</div>
				<h5 class="card-title mb-0">RTM rates</h5>
			</div>
			<div class="card-body d-flex w-100">
				<div class="align-self-center chart chart-lg">
					<div id="chartjs-dashboard-bar"></div>
				</div>
			</div>
		</div>
	</div>
</div>			
@endsection
@section('script')
<script>
    var options = {
        series: [{
            name: 'Total',
            data: [@foreach($data as $date=>$elem) {{$elem['total']}}, @endforeach]
        }, {
            name: 'Responses',
            data: [@foreach($data as $date=>$elem) {{$elem['responses']}}, @endforeach]
        }, {
            name: 'orders',
            data: [@foreach($data as $date=>$elem) {{$elem['orders']??0}}, @endforeach]
        }],
        chart: {
            type: 'bar',
            height: 350,
            stacked: true,
            stackType: '100%'
        },
        stroke: {
            width: 1,
            colors: ['#ff']
        },
        xaxis: {
            categories: [@foreach($data as $date=>$elem) '{{$date}}', @endforeach]
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val
                }
            }
        },
        fill: {
            opacity: 1
        },
        legend: {
            position: 'top',
            offsetX: 40
        }
    };
var chart = new ApexCharts(document.querySelector("#chartjs-dashboard-bar"), options);
chart.render();
</script>
@endsection