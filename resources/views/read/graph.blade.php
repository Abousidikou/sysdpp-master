@extends('layouts/master')


@section('content')

<div class="block-header">

    <h2>VISUALISATION DES DONNEES (GRAPHE) du sous domaine {{$subdomain->wording}} avec comme indicateur {{$indicator->wording}}</h2>

</div>


<div class="row clearfix">

        <div class="col-sm-12 col-xs-10">

            <div class="card">

                <div class="header">
                    <h2>
                        Graphe des DONNEES
                    </h2>
                </div>

                <div class="row">
                  <form action="{{route('graph.data.post', ['indicator' => $indicator->id, 'subdomain' => $subdomain->id])}}" method="POST">
                    {{csrf_field()}}
                    <div class="col-md-8">
                      <div class="body">
                        <h4 class="text-center">Sélectionner les niveaux de désagrégation de votre choix {{ request()->get('status')  }}</h4>

                        <select id="optgroup" class="ms" multiple="multiple" name="clevels[]">
                          @foreach ($infoArray as $info)
                            <option value="{{$info->lvhash}}">{{implode('-', $info->levels)}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <input type="submit" class="form-control btn btn-info btn-md" value="Ok" />
                      
                    </div>
                    <div class="col-md-2">
                      <a href="{{action('ReadController@readIndicators',['id' => $indicator->id, 'subdomain' => $subdomain->id])}}" class="btn btn-warning btn-md">Retour au tableau</a>
                      {{-- <input type="submit" class="form-control btn btn-info btn-md" value="Ok" /> --}}
                    </div>
                  </form>
                </div>
                <hr>
                <div class="body table-responsive">
                    <div id="columnchart_material" style="width: 800px; height: 500px;"></div>
                </div>
                <div class="body table-responsive">
                  <div id="linechart_material" style="width: 800px; height: 500px;"></div>
                </div>
                <div class="body table-responsive">
                  <div id="chart_div" style="width: 800px; height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar', 'line', 'corechart']});
      google.charts.setOnLoadCallback(drawChart);
      var graphData = <?php echo $chartData; ?>;
      function drawChart() {
        var data = google.visualization.arrayToDataTable(
          graphData
        );

        var options = {
          chart: {
            title: 'Graphe des données',
            subtitle: '',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
        var lineChart = new google.charts.Line(document.getElementById('linechart_material'));
        var areaChart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
        lineChart.draw(data, google.charts.Line.convertOptions(options));
        areaChart.draw(data, options);
      }
    </script>
@endpush
