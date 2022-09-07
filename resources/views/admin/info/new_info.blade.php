@extends('layouts/master')





@section('content')

<div class="block-header">

    <ol class="breadcrumb breadcrumb-col-orange">

        <li><a href="{{route('home')}}">Accueil</a></li>

        <li><a href="{{route('infos')}}">Gestion des donnees</a></li>

        <li class="active">CREER DONNEE</li>

    </ol>



    <small>Afin de valider la création, remplissez les champs puis cliquez sur le bouton <strong>Valider</strong> </small>

</div>



<div class="row clearfix">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="card">

            <div class="header">

                <h2>CREER DONNEES</h2>

            </div>

            <div class="body">

                @if(session('success'))

                    <div class="alert bg-green alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Info ajoutée avec succès.

                    </div> 

                @elseif(session('error'))

                    <div class="alert bg-danger alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Une erreur s'est produite au cours de l'ajout... Veuillez rééssayer.

                    </div>

                @elseif(session('validation'))

                    <div class="alert bg-danger alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Erreur de validation...Veuillez rééssayer<br>

                    </div>

                @endif

                <form id="form_advanced_validation" method="POST" action="{{action('Infos\InfosController@create')}}">

                    {{csrf_field()}}



                    

                    <div class="form-group form-float">

                        <div class="form-line">

                            <select class="form-control show-tick" name="year" data-live-search="true">

                                <option value="null">Veuillez sélectionner une annee</option>

                                @php

                                    $now = date('Y'); 

                                    $lastDate = 2000;

                                    do{

                                        echo "<option value='$now'>$now</option>";

                                        $now --;

                                    } while($now >= $lastDate);

                                @endphp

                            </select>

                            <label class="form-label"></label>

                        </div>

                        <div class="help-info"></div>

                    </div>



                    <div class="form-group form-float">

                        <div class="form-line">

                            <select name="domain" id="domain" class="form-control show-tick" data-live-search="true">

                                <option value="null">Veuillez choisir le domaine</option>

                                {!! $user->id_structure == null ? $domainsOptions: '' !!}

                            </select>

                            <label class="form-label"></label>

                        </div>

                        <div class="help-info"></div>

                    </div>



                    <div class="form-group form-float">

                        <div class="form-line">

                            <select name="subdomain" id="subdomains" class="form-control show-tick" data-live-search="true">

                                <option value="null">Veuillez choisir le sous domaine</option>

                                {!! $user->id_structure != null ? $subdomainsOptions: '' !!}

                            </select>

                            <label class="form-label"></label>

                        </div>

                        <div class="help-info"></div>

                    </div>  



                    <div class="form-group form-float">

                        <div class="form-line">

                            <select class="form-control show-tick" id="indicators" name="indicator" data-live-search="true">

                                <option value="null">Veuillez sélectionner un Indicateur</option>

                               

                            </select>

                            <label class="form-label"></label>

                        </div>

                        <div class="help-info"></div>

                    </div>



 

                    <div class="form-group form-float">

                        <div class="form-line">

                            <select class="form-control show-tick" id="levels" name="levels[]" data-live-search="true" multiple="multiple">

                                <option value="null">Veuillez sélectionner un Niveau</option>

                                

                            </select>

                            <label class="form-label"></label>

                        </div>

                        <div class="help-info"></div>

                    </div>



                    <div class="form-group form-float">

                        <div class="form-line">

                            <input type="number" class="form-control" name="number" required>

                            <label class="form-label">Valeur</label>

                        </div>

                        <div class="help-info"></div>

                    </div>



                    <div class="row clearfix demo-masked-input"> 

                        <div class="col-md-6">

                            <b>Debut(Periode)</b>

                            <div class="input-group">

                                <span class="input-group-addon">

                                    <i class="material-icons">date_range</i>

                                </span>

                                <div class="form-line">

                                    <input type="text" class="form-control date" name="date_start" placeholder="Ex: 30/07/2016">

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <b>Fin(Periode)</b>

                            <div class="input-group">

                                <span class="input-group-addon">

                                    <i class="material-icons">date_range</i>

                                </span>

                                <div class="form-line">

                                    <input type="text" class="form-control date" name="date_end" placeholder="Ex: 30/07/2016">

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="form-group form-float">

                        <div class="form-line">

                            <select class="form-control show-tick" name="periodicity" data-live-search="true">

                                <option value="null">Veuillez sélectionner la périodicité</option>

                                <option value="T1">T1</option>

                                <option value="T2">T2</option>

                                <option value="T3">T3</option>

                                <option value="T4">T4</option>

                                <option value="S1">S1</option>

                                <option value="S2">S2</option>

                                <option value="Annuel">Annuel</option>

                            </select>

                            <label class="form-label"></label>

                        </div>

                        <div class="help-info"></div>

                    </div>

 

                    <div class="form-group form-float">

                        <div class="form-line">

                            <textarea name="observation" cols="30" rows="5" class="form-control no-resize" ></textarea>

                            <label class="form-label">Observation</label>

                        </div>

                        <div class="help-info"></div>

                    </div>

                    

                    <button class="btn btn-primary waves-effect" type="submit">VALIDER</button>

                    <a href="{{route('infos')}}" class="btn btn-warning waves-effect" >Liste des informations</a>

                </form>

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

                    $('#indicators').append("<option value='null'>Veuillez choisir un indicateur</option>");

                    $('#indicators').selectpicker('refresh');

                    $('#levels').html('');

                    $('#levels').append("<option value='null'>Veuillez choisir un ou des niveaux</option>");

                    $('#levels').selectpicker('refresh');

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

                    $('#levels').html('');

                    $('#levels').append("<option value='null'>Veuillez choisir un ou des niveaux</option>");

                    $('#levels').selectpicker('refresh');

                },

                error: function(error)

                {

                    console.log(JSON.parse(error));

                }

            })

        });



        $("#indicators").off().on('change', function(){

            var indicatorId = $("#indicators").val();

            var formData = new FormData();

            formData.append('id',indicatorId);

            var server_url = '{{url("/indicator/levels")}}'; 

            

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

                    $('#levels').html('');

                    $('#levels').append(response);

                    $('#levels').selectpicker('refresh');

                },

                error: function(error)

                {

                    console.log(JSON.parse(error));

                }

            })

        });

    </script>

@endpush