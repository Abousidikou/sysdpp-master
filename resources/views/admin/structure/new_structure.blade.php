@extends('layouts/master')



@section('content')

<div class="block-header">

    <ol class="breadcrumb breadcrumb-col-orange">

        <li><a href="{{route('home')}}">Accueil</a></li>

        <li><a href="{{route('structures')}}">Gestion des structures</a></li>

        <li class="active">CREER STRUCTURE</li>

    </ol>

    <small>Afin de valider la création de la nouvelle structure, remplissez les champs puis cliquez sur le bouton <strong>Valider</strong> </small>

</div>





<div class="row clearfix">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="card">

            <div class="header">

                <h2>CREER STRUCTURE</h2>

            </div>

            <div class="body">

                @if(session('success'))

                    <div class="alert bg-green alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Nouvelle structure ajoutée avec succès.

                    </div>

                @elseif(session('error'))

                    <div class="alert bg-danger alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Une erreur s'est produite au cours de l'ajout... Veuillez rééssayer.

                    </div>

                @elseif(session('validation'))

                    <div class="alert bg-danger alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Erreur de validation... Il se peut que la structure existe déjà. <br>

                        Les champs ne doivent pas contenir des chiffres

                    </div>

                @endif

                <form id="" method="POST" action="{{action('Structure\StructureController@create')}}">

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

                            <select name="subdomains[]" id="subdomains" class="form-control showtick" data-live-search="true" multiple="multiple">

                                <option value="null">Veuillez choisir le(s) sous domaine(s)</option>

                            </select>

                            <label class="form-label"></label>

                        </div>

                        <div class="help-info"></div> 

                    </div>

 

                    <div class="form-group form-float">

                        <div class="form-line">

                            <input type="text" class="form-control" name="wording"  required>

                            <label class="form-label">Structure</label>

                        </div>

                        <div class="help-info"></div>

                    </div>



                    <div class="form-group form-float">

                        <div class="form-line">

                            <input type="text" class="form-control" name="abr" required>

                            <label class="form-label">Abréviation</label>

                        </div>

                        <div class="help-info"></div>

                    </div>

                    <button class="btn btn-primary waves-effect" type="submit">VALIDER</button>

                    <a href="{{route('structures')}}" class="btn btn-warning waves-effect" >Liste des structures</a>

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

                    var sele = document.getElementById("subdomains");

                    $('#subdomains').html('');

                    $('#subdomains').append(response);

                    $('#subdomains').selectpicker('refresh');

                },

                error: function(error)

                {

                    console.log(JSON.parse(error));

                }

            })

        });

    </script>

@endpush