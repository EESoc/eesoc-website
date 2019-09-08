@extends('layouts.admin')

@section('content')
  Welcome to the Admin Dashboard. Please select a section above or choose one of the <b>top links</b> below to continue.
  <br >
  <br >
  <br >
  <div class="container">
          <a class="btn btn-info btn-lg" href="{{{ route('admin.emails.index') }}}"><span class="glyphicon glyphicon-envelope"></span> Emails</a>
          <a class="btn btn-info btn-lg" href="{{{ route('dashboard.dinner.groups.index') }}}"><span class="glyphicon glyphicon-glass"></span> Dinner</a>
          <a class="btn btn-info btn-lg" href="{{{ action('LockersController@getIndex') }}}"><span class="glyphicon glyphicon-lock"></span> Lockers</a>
          <a class="btn btn-info btn-lg" href="{{{ URL::action('Barryvdh\ElfinderBundle\ElfinderController@showIndex') }}}"><span class="glyphicon glyphicon-folder-open"></span> Files</a>
          <a class="btn btn-info btn-lg" href="{{{ URL::action('Admin\SalesController@getIndex') }}}"><span class="glyphicon glyphicon-gbp"></span>Sales</a>
          <a class="btn btn-info btn-lg" href="{{ action('Admin\SalesController@getSync') }}"><span class="glyphicon glyphicon-refresh"></span> Sync Sales</a>
  </div>
  <!-- <div class="col-md-6">
  <div id="chart"></div>
  </div> -->
  <div class="col-md-12"><div id="chart2"></div></div>
  <script src="https://unpkg.com/frappe-charts@0.0.3/dist/frappe-charts.min.iife.js"></script>
  <script>
     // Javascript
    // var data = {
    //   labels: {{ json_encode($list['date_labels']) }},

    //   datasets: [
    //     {
    //       title: "Members", color: "light-blue",
    //       values: {{ json_encode($list['results_lockers']) }}
    //     },
    //     {
    //       title: "Non-members", color: "violet",
    //       values: {{ json_encode($list['results_bar_night']) }}
    //     },
    //   ]
    // };

    // var chart = new Chart({
    //   parent: "#chart",
    //   title: "Daily Dinner Sales (last 14 days)",
    //   data: data,
    //   type: 'line', // or 'line', 'scatter', 'pie', 'percentage'
    //   height: 250,
    //   show_dots: 0,
    //   region_fill: 1,
    //   is_series: 1
    // });

    var data2 = {
      labels: {{ json_encode($list['prod_labels']) }},

      datasets: [
        {
          color: "green",
          values: {{ json_encode($list['prod_count']) }}
        }
      ]
    };

    var chart2 = new Chart({
      parent: "#chart2",
      title: "Sales Per Product (Only Synced Products)",
      data: data2,
      type: 'bar', // or 'line', 'scatter', 'pie', 'percentage'
      height: 250,
    });
  </script>
  <!-- TODO: Add nicely formatted tiles for quick actions rather than using menu-->
  <!--a href="{{ action('Barryvdh\ElfinderBundle\ElfinderController@showIndex') }}" class="btn btn-info" target="_blank">File manager</a-->
@stop
