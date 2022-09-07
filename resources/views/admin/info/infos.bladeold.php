@extends('layouts/master')





@section('content')

<div class="block-header">

    <ol class="breadcrumb breadcrumb-col-orange">

        <li><a href="{{route('home')}}">Accueil</a></li>

        <li class="active">Gestion des donnees</li>

    </ol>



    <small>Pour ajouter une Donnée, cliquez sur le bouton <strong>Ajouter une Donnée</strong> </small>

</div>



<div class="row clearfix">

        <div class="col-sm-12 col-xs-10">

            <div class="card">

                <div class="header">

                    <h2>

                        DONNEES

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

                            Une erreur s'est produite au cours de la suppression... Veuillez rééssayer.

                        </div>

                    @endif

                    <table class="table table-striped table-bordered table-condensed js-basic-example">

                        <thead>

                            <tr>

                                <th>Annee</th> 

                                <th>Sous domaines</th>

                                <th>Indicateurs</th>

                                <th>Niveaux</th>  

                                <th>Valeurs</th>

                                <th>Actions</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($infos as $info)

                            <tr>

                                <td>{{$info->year}}</td>

                                <td>{{$info->subd}}</td>

                                <td>{{$info->indicat}}</td>

                                <td>
                                    @foreach ($info->levels as $item)
                                        {{$item->wording}}; &nbsp;
                                    @endforeach
                                </td>

                                <td>{{$info->value}}</td>

                                <td>
                                    <div style="display: flex">
                                        <a href="{{route('prepareUpdate.info', ['id' => $info->id])}}" class="btn btn-xs bg-green waves-effect"
                                            title="Cliquer pour modifier"
                                        >
                                            <i class="material-icons" >edit</i>
                                        </a>

                                        <a onclick="return confirm('Êtes-vous sûre?')" href="{{route('delete.info', ['id' => $info->id])}}" class="btn btn-xs bg-red waves-effect"
                                            title="Cliquer pour supprimer"
                                        >
                                            <i class="material-icons">delete_forever</i>
                                        </a>

                                        <a onclick="showMoreDetails({{$info->id}})" href="javascript:void(0);" class="btn btn-xs btn-warning waves-effect"
                                            title="Cliquer pour plus de details" style=""
                                        >
                                            <i class="material-icons">info</i>
                                        </a>
                                    </div>
                                </td>

                            </tr>

                            @endforeach

                            

                        </tbody>

                        <caption style="caption-side: top; text-align:center" title="Cliquer pour ajouter une nouvelle information">

                            <a href="{{route('form.info')}}" class="btn bg-green waves-effect">Ajouter une Donnée</a>

                            <a href="{{route('form.export')}}" class="btn bg-green waves-effect">Exporter des Données</a>

                            <a href="{{route('form.import')}}" class="btn bg-green waves-effect">Generer un masque d'importation</a>

                            <a href="{{route('form.import_file')}}" class="btn bg-green waves-effect">Importer un fichier</a>

                        </caption>

                    </table>

                </div>

            </div>

        </div>

    </div>

@endsection





@push('script')

    <script>

        function showMoreDetails(infoId)

        {

            var formData = new FormData();

            formData.append('infoId',infoId);

            var server_url = '{{url("/info/details")}}';

            

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



                    $("#modal-title").html("Plus de detail pour l'information statistique" + title);

                    $("#modal-body").html(builtList);

                    $("#modal").modal("show");

                },

                error: function(error)

                {

                    console.log(JSON.parse(error));

                }

            });

        }



        function showMoreLevel(infoId)

        {

            var formData = new FormData();

            formData.append('infoId',infoId);

            var server_url = '{{url("/info/levels")}}';

            

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



                    $("#modal-title").html("Liste des Niveaux associes");

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