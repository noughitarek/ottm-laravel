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
          <form method="get" class="row g-2">
            <div class="col-auto">
              <select name="page" class="form-select form-select-sm bg-light border-0">
                <option value>All</option>
                @foreach($pages as $page)
                <option value="{{$page->id}}" {{(($_GET['page']??0)==$page->id)?'selected':''}}>{{$page->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-4">
							<input name="datetime" type="text" value="{{$_GET['datetime']??$ResponseTime::defaultDateTime()}}" class="form-select form-select-sm bg-light border-0 flatpickr-range" placeholder="Select date.." />
            </div>
            <div class="col-auto">
              <select name="type" class="form-select form-select-sm bg-light border-0">
                <option {{(($_GET['type']??'Hourly') == 'Minutely')?"selected":""}}>Minutely</option>
                <option {{(($_GET['type']??'Hourly') == 'Hourly')?"selected":""}}>Hourly</option>
                <option {{(($_GET['type']??'Hourly') == 'Daily')?"selected":""}}>Daily</option>
                <option {{(($_GET['type']??'Hourly') == 'Weekly')?"selected":""}}>Weekly</option>
                <option {{(($_GET['type']??'Hourly') == 'Monthly')?"selected":""}}>Monthly</option>
              </select>
            </div>
            <div class="col-auto">
              <button class="btn btn-sm btn-primary rounded" id="updateButton">Lookup</button>
            </div>
          </form>
      </div>
      <div class="card-body pt-2 pb-3">
        <div class="chart chart-sm">
          <div id="responseTime"></div>
          <div class="row">
            <div class="col-6">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col mt-0">
                      <h5 class="card-title">7, 8, 9, 10, 11, 12, 13, 14</h5>
                    </div>
                  </div>
                  <h1 class="mt-1 mb-3">{{$ResponseTime::range([7, 8, 9, 10, 11, 12, 13, 14])}}</h1>
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
                      <h5 class="card-title">15, 16, 17, 18, 20, 21, 22, 23</h5>
                    </div>  
                  </div>
                  <h1 class="mt-1 mb-3">{{$ResponseTime::range([15, 16, 17, 18, 20, 21, 22, 23])}}</h1>
                  <div class="mb-0">
                    <span class="text-muted">Min</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
document.addEventListener("DOMContentLoaded", function() {
	flatpickr(".flatpickr-range", {
		mode: "range",
    enableTime: true,
	});
});
</script>
<script>
var options = {
    series: [{
    name: 'Average response time',
    data: [@foreach($ResponseTime::Get_Date() as $elem) '<?=number_format($elem['average'], 2)??""?>', @endforeach]
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
    categories:  [@foreach($ResponseTime::Get_Date() as $elem) '{{$elem['time']}}', @endforeach]
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
  const chart = new ApexCharts(document.querySelector("#responseTime"), options);
  chart.render();
</script>
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
@endsection