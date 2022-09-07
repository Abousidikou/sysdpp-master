@extends('layouts/master')



@section('content')

<div class="block-header">

    <ol class="breadcrumb breadcrumb-col-orange">

        <li><a href="{{route('home')}}">Accueil</a></li>

        <li><a href="{{route('levels')}}">Gestion des niveaux de desagregation</a></li>

        <li class="active">METTRE A JOUR UN NIVEAU</li>

    </ol>



    <small>Afin de valider la mise à jour, remplissez les champs puis cliquez sur le bouton <strong>Valider</strong> </small>

</div>





<div class="row clearfix">

    <div class="col-sm-8 col-sm-offset-2 col-xs-12">

        <div class="card">

            <div class="header">

                <h2>METTRE A JOUR UN NIVEAU</h2>

            </div>

            <div class="body">

                @if(session('success'))

                    <div class="alert bg-green alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Niveau mis à jour avec succès.

                    </div>

                @elseif(session('error'))

                    <div class="alert bg-danger alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Une erreur s'est produite au cours de la mise à jour... Veuillez rééssayer.

                    </div>

                @elseif(session('validation'))

                    <div class="alert bg-danger alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Erreur de validation...<br>

                        Veuillez rééssayer.

                    </div>

                @endif

                <form id="form_advanced_validation" method="POST" action="{{action('Level\LevelController@update')}}">

                    {{csrf_field()}}

                    <input type="hidden" name="id" class="form-control" value="{{$level->id}}">

                    

                    
{{-- 
                    <div class="form-group form-float">

                        <div class="form-line">

                            <select name="domain" id="domain" class="form-control show-tick" data-live-search="true">

                                <option value="null">Veuillez choisir le domaine</option>

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

                    </div>   --}}



                    <div class="form-group form-float">

                        <div class="form-line">

                            <select name="indicators[]" class="form-control show-tick" multiple="multiple" data-live-search="true">

                                <option value="null">Veuillez choisir les indicateurs</option>

                                <?= $indicatorsOptions ?>

                            </select> 

                            <label class="form-label"></label>

                        </div>

                        <div class="help-info"></div>

                    </div>


                    <div class="form-group form-float">

                        <div class="form-line">

                            <select name="type" class="form-control show-tick" data-live-search="true">
                                <option value="null">Veuillez choisir le type</option>
                                <?= $typesOptions ?>

                            </select>

                            <label class="form-label"></label>

                        </div>

                        <div class="help-info"></div>

                    </div>



                    <div class="form-group form-float">

                        <div class="form-line">

                            <input type="text" name="wording" class="form-control" value="{{$level->wording}}" required>

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