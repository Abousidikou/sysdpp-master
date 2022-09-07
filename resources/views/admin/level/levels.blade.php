@extends('layouts/master')

 



@section('content')

<div class="block-header">

    <ol class="breadcrumb breadcrumb-col-orange">

        <li><a href="{{route('home')}}">Accueil</a></li>

        <li class="active">GESTION DES NIVEAUX DE DESAGREGATION</li>

    </ol>



    <small>Pour ajouter un niveau de désagrégation, cliquez sur le bouton <strong>Ajouter un niveau</strong> </small>

</div>



<div class="row clearfix">

        <div class="col-sm-offset-1 col-sm-10 col-xs-10">

            <div class="card">

                <div class="header">

                    <h2>

                        NIVEAUX DE DESAGREGATION

                    </h2>

                </div> 

                <div class="body table-responsive"> 

                    @if(session('success'))

                        <div class="alert bg-green alert-dismissible" role="alert">

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                            Supprimé avec succès.

                        </div>

                    @elseif(session('error'))

                        <div class="alert bg-danger alert-dismissible" role="alert">

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                            Impossible de supprimer... D'autres informations dependent de cet enregistrement.

                        </div>

                    @endif

                    <table class="table table-striped table-bordered table-condensed js-basic-example">

                        <thead>

                            <tr>

                                <th>Indicateurs</th>

                                <th>Types</th>

                                <th>Libellés</th>

                                <th>Actions</th>

                            </tr> 

                        </thead>

                        <tbody>

                            @foreach ($levels as $level)

                            <tr>

                                <td>{{$level->indicator}}

                                    @if ($level->multiindi == true)

                                        <a onclick="showMoreIndicator({{$level->id}})" href="javascript:void(0);" style="font-size:large" title="cliquez pour voir les autres sous-domaines">+</a>                                    

                                    @endif 

                                </td>



                                <td>{{$level->type}}</td>

                                <td>{{$level->wording}}</td>

                                @if(Auth::user()->role == "admin")
                                    <td>
                                        <div style="display: flex">
                                            <a href="{{route('prepareUpdate.level', ['id' => $level->id])}}" class="btn bg-green waves-effect"
                                                title="Cliquer pour modifier"
                                            >
                                                <i class="material-icons" >edit</i>
                                            </a>

                                            <a onclick="return confirm('Êtes-vous sûre?')" href="{{route('delete.level', ['id' => $level->id])}}" class="btn bg-red waves-effect"
                                                title="Cliquer pour supprimer"
                                            >
                                                <i class="material-icons">delete_forever</i>
                                            </a>
                                        </div>
                                    </td>
                                @else
                                    <td>No actions</td>
                                @endif
                            </tr>

                            @endforeach

                        </tbody>

                        @if(Auth::user()->role == "admin")

                            <caption style="caption-side: top; text-align:center" title="Cliquer pour ajouter un niveau de désagrégation">

                                <a href="{{route('form.level')}}" class="btn bg-green waves-effect">Ajouter un niveau</a>

                                <a href="{{route('level.export')}}" class="btn bg-green waves-effect">Exporter</a>

                                <a href="{{route('level.import')}}" class="btn bg-green waves-effect">Importer un fichier</a>

                            @if (session('path'))

                                @php

                                    $path = session('path');

                                @endphp

                                <a href="{{url($path)}}" class="btn btn-secondary waves-effect" >{{url($path)}}</a>

                            @endif

                            </caption>
                        @endif
                    </table>

                </div>

            </div>

        </div>

    </div>

    

@endsection



@push('script')

    <script>

        function showMoreIndicator(levelId)

        {

            var formData = new FormData();

            formData.append('levelId',levelId);

            var server_url = '{{url("/level/indicators")}}';

            

            $.ajax({

                url: server_url,

                method: "POST",

                data: formData,

                dataType: 'text', 

                contentType:false,

                processData: false,

    

                success: function(response)

                {

                    response = JSON.parse(response);

                    var title = response.title;

                    var builtList = response.builtList;



                    $("#modal-title").html("Liste des indicateurs associes au niveau " + title);

                    $("#modal-body").html(builtList);

                    $("#modal").modal("show");

                },

                error: function(error)

                {

                    console.log(JSON.parse(error));

                }

            });

        }

    </script>

@endpush