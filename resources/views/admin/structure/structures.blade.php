@extends('layouts/master')







@section('content')

<div class="block-header">

    <ol class="breadcrumb breadcrumb-col-orange">

        <li><a href="{{route('home')}}">Accueil</a></li>

        <li class="active">GESTION DES STRUCTURES</li>

    </ol>



    <small>Pour ajouter une nouvelle structure, cliquez sur le bouton <strong>Ajouter une structure</strong> </small>

</div>



<div class="row clearfix">

    <div class="col-sm-offset-1 col-sm-10 col-xs-10">

        <div class="card"> 

            <div class="header">

                <h2>

                    STRUCTURES DU MTFP

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

                            <th>Sous Domaines</th>

                            <th>Structures</th>

                            <th>Abréviations</th>

                            <th>Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($structures as $structure)

                        <tr> 

                            <td>{{$structure->subdomain}}

                                @if ($structure->multisubd == true)

                                    <a onclick="showMoreSubD({{$structure->id}})" href="javascript:void(0);" style="font-size:large" title="cliquez pour voir les autres sous-domaines">+</a>                                    

                                @endif 

                            </td>

                            <td>{{$structure->wording}}</td>

                            <td>{{$structure->abr}}</td>

                            @if(Auth::user()->role == "admin")
                                <td>
                                    <div style="display: flex">
                                        <a href="{{route('prepareUpdate.structure', ['id' => $structure->id])}}" class="btn bg-green waves-effect"
                                            title="Cliquer pour modifier la strucutre"
                                        >
                                            <i class="material-icons" >edit</i>
                                        </a>

                                        <a onclick="return confirm('Êtes-vous sûre?')" href="{{route('delete.structure', ['id' => $structure->id])}}" class="btn bg-red waves-effect"
                                            title="Cliquer pour supprimer la strucutre"
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

                        <caption style="caption-side: top; text-align:center" title="Cliquer pour ajouter une nouvelle structure">

                            <a href="{{route('form.structure')}}" class="btn bg-green waves-effect">Ajouter une structure</a>

                            <a href="{{route('structure.export')}}" class="btn bg-green waves-effect">Exporter</a>

                            <a href="{{route('structure.import')}}" class="btn bg-green waves-effect">Importer un fichier</a>

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

        function showMoreSubD(structId)

        {

            var formData = new FormData();

            formData.append('structId',structId);

            var server_url = '{{url("/struct/subdomains")}}';

            

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



                    $("#modal-title").html("Liste des sous domaines associes a la structure " + title);

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