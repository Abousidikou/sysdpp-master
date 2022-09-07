@extends('layouts/master')





@section('content')

<div class="block-header">

    <ol class="breadcrumb breadcrumb-col-orange">

        <li><a href="{{route('home')}}">Accueil</a></li>

        <li><a href="{{route('levels')}}">Gestion des niveaux de desagregation</a></li>

        <li class="active">CREER UN NIVEAU</li>

    </ol>



    <small>Afin de valider la création, remplissez les champs puis cliquez sur le bouton <strong>Valider</strong> </small>

</div>





<div class="row clearfix">

    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">

        <div class="card">

            <div class="header">

                <h2>CREER UN NIVEAU</h2>

            </div>

            <div class="body">

                @if(session('success'))

                    <div class="alert bg-green alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Niveau ajouté avec succès.

                    </div>

                @elseif(session('error'))

                    <div class="alert bg-danger alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Une erreur s'est produite au cours de l'ajout... Veuillez rééssayer.

                    </div>

                @elseif(session('validation'))

                    <div class="alert bg-danger alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Erreur de validation... Il se peut que ce niveau existe déjà. <br>

                        Veuillez rééssayer.

                    </div>

                @endif

                <form id="form_advanced_validation" method="POST" action="{{action('Level\LevelController@create')}}">

                    {{csrf_field()}}

                     



                    <div class="form-group form-float">

                        <div class="form-line">

                            <select name="domain" id="domain" class="form-control show-tick" data-live-search="true">

                                <option value="null">Veuillez choisir le domaine</option>

                                <?= $domainsOptions ?>

                            </select>

                            <label class="form-label"></label>

                        </div>

                        <div class="help-info"></div>

                    </div>



                    <div class="form-group form-float">

                        <div class="form-line">

                            <select name="subdomain" id="subdomains" class="form-control show-tick" data-live-search="true">

                                <option value="null">Veuillez choisir le sous domaine</option>

                                

                            </select>

                            <label class="form-label"></label>

                        </div>

                        <div class="help-info"></div>

                    </div>  



                    <div class="form-group form-float">

                        <div class="form-line">

                            <select name="indicators[]" id="indicators" class="form-control show-tick" multiple="multiple" data-live-search="true">

                                <option value="null">Veuillez choisir un ou des indicateurs</option>

                                

                            </select> 

                            <label class="form-label"></label>

                        </div>

                        <div class="help-info"></div>

                    </div>



                    <div class="form-group form-float">

                        <div class="form-line">

                            <select name="type" class="form-control show-tick" data-live-search="true">

                                <option value="null">Veuillez sélectionner un type</option>

                                <?= $typesOptions ?>

                            </select>

                            <label class="form-label"></label>

                        </div>

                        <div class="help-info"></div>

                    </div>



                    <div class="form-group form-float">

                        <div class="form-line">

                            <input type="text" name="wording" class="form-control" required>

                            <label class="form-label">Libellé</label>

                        </div>

                        <div class="help-info"></div>

                    </div>





                    <button class="btn btn-primary waves-effect" type="submit">VALIDER</button>

                    <a class="btn btn-warning waves-effect" href="{{route('levels')}}">Liste des niveaux</a>

                </form>

            </div>

        </div>

    </div>

    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">

        <div class="card">

            <div class="header">

                <h2>CREATION DE TYPE</h2>

            </div>

            <div class="body">

                @if(session('tsuccess'))

                    <div class="alert bg-green alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Nouveau Type ajouté avec succès.

                    </div>

                @elseif(session('terror'))

                    <div class="alert bg-danger alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Une erreur s'est produite au cours de l'ajout... Veuillez rééssayer.

                    </div>

                @elseif(session('tvalidation'))

                    <div class="alert bg-danger alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Erreur de validation... Il se peut que ce Type existe déjà. <br>

                        Veuillez rééssayer.

                    </div>

                @endif

                <form id="type-form" method="POST" action="{{action('Level\LevelController@createType')}}">

                    {{csrf_field()}}

                    <div class="form-group form-float">

                        <div class="form-line">

                            <input type="text" name="wording" class="form-control" required>

                            <label class="form-label">Libellé</label>

                        </div>

                        <div class="help-info"></div>

                    </div>



                    <button class="btn btn-primary waves-effect" type="submit">VALIDER</button>

                </form>



                <div class="body table-responsive"> 

                    <table class="table table-striped table-bordered table-condensed js-basic-example">

                        <thead>

                            <tr>

                                <th>Libellés</th>

                                <th>Actions</th>

                            </tr> 

                        </thead>

                        <tbody>

                            @foreach ($types as $type)

                            <tr>

                                <td>{{$type->wording}}</td>

                                <td>

                                    <div style="display: flex">
                                        <a href="{{route('prepareUpdate.type', ['id' => $type->id])}}" class="btn bg-green waves-effect"

                                            title="Cliquer pour modifier"

                                        >

                                            <i class="material-icons" >edit</i>

                                        </a>

                                        <a href="{{route('delete.type', ['id' => $type->id])}}" class="btn bg-red waves-effect"

                                            title="Cliquer pour supprimer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer?')"

                                        >

                                            <i class="material-icons">delete_forever</i>

                                        </a>
                                    </div>
                                </td>

                            </tr>

                            @endforeach



                        </tbody>

                        <caption style="caption-side: top; text-align:center" title="">

                            <a href="#" class="btn bg-green waves-effect">Les types</a>

                            <a href="{{route('type.export')}}" class="btn bg-green waves-effect">Exporter</a>

                            <a href="{{route('type.import')}}" class="btn bg-green waves-effect">Importer un fichier</a>

                        @if (session('path'))

                            @php

                                $path = session('path');

                            @endphp

                            <a href="{{url($path)}}" class="btn btn-secondary waves-effect" >{{url($path)}}</a>

                        @endif

                        </caption>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>



@endsection



@push('script')

    <script type="text/javascript">        

        $("#domain").off().on('change', function(){

            var domainId = $("#domain").val();

            var formData = new FormData();

            formData.append('id',domainId);

            var server_url = '{{url("/domain/subdomains")}}';

            

            $.ajax({

                url: server_url,

                method: "POST",

                data: formData,

                dataType: 'text', 

                contentType:false,

                processData: false,

    

                success: function(response)

                {

                    //alert("test");

                    $('#subdomains').html('');

                    $('#subdomains').append(response);

                    $('#subdomains').selectpicker('refresh');



                    $('#indicators').html('');

                    $('#indicators').append("<option value='null'>Veuillez choisir un ou des indicateurs</option>");

                    $('#indicators').selectpicker('refresh');

                },

                error: function(error)

                {

                    console.log(JSON.parse(error));

                }

            })

        });





        $("#subdomains").off().on('change', function(){

            var subdomainId = $("#subdomains").val();

            var formData = new FormData();

            formData.append('id',subdomainId);

            var server_url = '{{url("/subdomain/indicators")}}'; 

            

            $.ajax({

                url: server_url,

                method: "POST",

                data: formData,

                dataType: 'text', 

                contentType:false,

                processData: false,

    

                success: function(response)

                {

                    //alert("test");

                    $('#indicators').html('');

                    $('#indicators').append(response);

                    $('#indicators').selectpicker('refresh');

                },

                error: function(error)

                {

                    console.log(JSON.parse(error));

                }

            })

        });

    </script>

@endpush