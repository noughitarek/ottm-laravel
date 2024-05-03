@extends('layouts.main')
@section('subtitle', "Dashboard")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="row">
  <div class="col-12">
    <div class="card flex-fill w-100">
      <div class="card-header">
          <form method="get" class="row g-2">
            <div class="col-auto">
              <select name="page" class="form-select form-select-sm bg-light border-0">
                <option value>All</option>
                @foreach($pages as $page)
                <option value="{{$page->id}}">{{$page->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-auto">
							<input name="datetime" type="text" value="2024-05-02 to 2024-05-03" class="form-select form-select-sm bg-light border-0 flatpickr-range" placeholder="Select date.." />
            </div>
            <div class="col-auto">
              <select name="type" class="form-select form-select-sm bg-light border-0">
                <option>Minutely</option>
                <option selected>Hourly</option>
                <option>Daily</option>
                <option>Weekly</option>
                <option>Monthly</option>
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
    name: '2 May',
    data: [@foreach($ResponseTime::Get_Date($_GET['type']??'Hourly') as $elem) '<?=number_format($elem['average'], 2)??""?>', @endforeach]
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
    categories:  [@foreach($ResponseTime::Get_Date($_GET['type']??'Hourly') as $elem) '{{$elem['time']}}', @endforeach]
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
@endsection