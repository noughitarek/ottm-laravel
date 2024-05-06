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
				<h5 class="card-title mb-0">Response times</h5>
			</div>
			<div class="card-body d-flex w-100">
				<div class="align-self-center chart chart-lg">
					<div id="responseTime"></div>
				</div>
			</div>
		</div>
	</div>
  <div class="col-6">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col mt-0">
            <h5 class="card-title">07:00=>15:00 hr</h5>
          </div>
        </div>
        <h1 class="mt-1 mb-3">{{$data['averageResponseRatef7t15']}}</h1>
        <div class="mb-0">
          <span class="text-muted">Min</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col mt-0">
            <h5 class="card-title">15:00=>24:00 hr</h5>
          </div>  
        </div>
        <h1 class="mt-1 mb-3">{{$data['averageResponseRatef15t23']}}</h1>
        <div class="mb-0">
          <span class="text-muted">Min</span>
        </div>
      </div>
    </div>
  </div>
</div>			
@endsection
@section('script')
<script>
    var options = {
          series: [
          {
            name: "Total messages",
            data: [@foreach($data['remarketingMessages'] as $date=>$elem) {{$elem['total']}}, @endforeach]
          },
          {
            name: "Total interval messages",
            data: [@foreach($data['remarketingIntervalMessages'] as $date=>$elem) {{$elem['total_interval']}}, @endforeach]
          },
        ],
          chart: {
          height: 350,
          type: 'line',
          dropShadow: {
            enabled: true,
            color: '#000',
            top: 18,
            left: 7,
            blur: 10,
            opacity: 0.2
          },
          zoom: {
            enabled: false
          },
          toolbar: {
            show: false
          }
        },
        dataLabels: {
          enabled: true,
        },
        stroke: {
          curve: 'smooth'
        },
        title: {
          text: 'Total messages per day',
          align: 'left'
        },
        grid: {
          row: {
            opacity: 0.5
          },
        },
        markers: {
          size: 1
        },
        xaxis: {
          categories:  [@foreach($data as $date=>$elem) '{{$date}}', @endforeach],
          title: {
            text: 'Month'
          }
        },
        yaxis: {
          title: {
            text: 'Temperature'
          },
          min: 0,
        },
        legend: {
          position: 'top',
          horizontalAlign: 'right',
          floating: true,
          offsetY: -25,
          offsetX: -5
        }
        };
        var rtmRates = new ApexCharts(document.querySelector("#chartjs-dashboard-bar"), options);
        rtmRates.render();
</script>
<script>
var options = {
    series: [{
    name: '3 May',
    data: [@foreach($data['responseTime'] as $date=>$elem) '<?=$elem["3 May"]??""?>', @endforeach]
  }, {
    name: '4 May',
    data: [@foreach($data['responseTime'] as $date=>$elem) '<?=$elem["4 May"]??""?>', @endforeach]
  }],
    chart: {
    type: 'bar',
    height: 350
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: '55%',
      endingShape: 'rounded'
    },
  },
  dataLabels: {
    enabled: false
  },
  stroke: {
    show: true,
    width: 2,
    colors: ['transparent']
  },
  xaxis: {
    categories:  [@foreach($data['responseTime'] as $date=>$elem) '{{$date}}', @endforeach],
  },
  yaxis: {
    title: {
      text: 'Min'
    }
  },
  fill: {
    opacity: 1
  },
  tooltip: {
    y: {
      formatter: function (val) {
        return val + " Min"
      }
    }
  }
  };  
  var chart = new ApexCharts(document.querySelector("#responseTime"), options);
  chart.render();
</script>
@endsection