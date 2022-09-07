@extends('layouts/master')





@section('content')

<div class="block-header">

        <ol class="breadcrumb breadcrumb-col-orange">

            <li><a href="{{route('home')}}">Accueil</a></li>

            <li><a href="{{route('infos')}}">Gestion des donnees</a></li>

            <li class="active">GENERER MASQUE D'IMPORTATION </li>

        </ol>



    <small>Afin d'importer des donnees, remplissez le formulaire puis cliquer sur le bouton <strong>GENERER</strong> pour generer le masque d'importation</small>

</div>



<div class="row clearfix">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="card">

            <div class="header">

                <h2>GENERER MASQUE D'IMPORTATION</h2>

            </div>

            <div class="body">



                <form id="form_advanced_validation" method="POST" action="{{action('Infos\InfosController@buildImportMask')}}">

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

                            <select class="form-control show-tick" id="indicators" name="indicators[]" data-live-search="true" multiple="multiple">

                                <option value="null">Veuillez sélectionner un ou des Indicateurs</option>

                               

                            </select>

                            <label class="form-label"></label>

                        </div> 

                        <div class="help-info"></div>

                    </div>



 

                    <div class="form-group form-float">

                        <div class="form-line">

                            <select class="form-control show-tick" id="types" name="types[]" data-live-search="true" multiple="multiple">

                                <option value="null">Veuillez sélectionner des types de Niveau</option>

                                

                            </select>

                            <label class="form-label"></label>

                        </div>

                        <div class="help-info"></div>

                    </div>



                    <div class="form-group form-float">

                        <div class="form-line">

                            <input type="text" class="form-control" name="datasetname" required>

                            <label class="form-label">Dataset Name</label>

                        </div>

                        <div class="help-info"></div>

                    </div>



                    <button class="btn btn-primary waves-effect" type="submit">GENERER</button>

                    <a href="{{route('infos')}}" class="btn btn-warning waves-effect" >Liste des informations</a>



                    @if (session('path'))

                        @php

                            $path = session('path');

                        @endphp

                        <a href="{{url($path)}}" class="btn btn-secondary waves-effect" >Telecharger le fichier</a>

                    @endif

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



        $("#indicators").off().on('change', function(){

            var indicators = $("#indicators").val();

            var formData = new FormData();

            formData.append('indicators',indicators);



            var server_url = '{{url("/indicator/types")}}'; 

            

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

                    $('#types').html('');

                    $('#types').append(response);

                    $('#types').selectpicker('refresh');

                },

                error: function(error)

                {

                    console.log(JSON.parse(error));

                }

            })

        });

    </script>

@endpush