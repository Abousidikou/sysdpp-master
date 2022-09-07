@extends('layouts/master')





@section('content')

<div class="block-header">

    <ol class="breadcrumb breadcrumb-col-orange">

        <li><a href="{{route('home')}}">Accueil</a></li>

        <li><a href="{{route('levels')}}">Gestion des niveaux de desagregation</a></li>

        <li class="active">IMPORTER DES NIVEAUX</li>

    </ol>



    <small>Afin d'importer des niveaux, choisissez un fichier excel puis cliquer sur le bouton <strong>Importer</strong></small>

</div>



<div class="row clearfix">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="card">

            <div class="header">

                <h2>IMPORTER DES NIVEAUX</h2>

            </div>

            <div class="body">

                @if(session('success'))

                <div class="alert bg-green alert-dismissible" role="alert">

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    Importation terminee avec succes

                </div> 

            @elseif(session('unit'))

                <div class="alert bg-warning alert-dismissible" role="alert">

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    Le format du fichier excel est incorrecte, <strong>il manque la colonne Unit</strong>

                </div>

            @elseif(session('validation'))

                <div class="alert bg-danger alert-dismissible" role="alert">

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    Erreur de validation...Veuillez rééssayer avec un fichier Xlsx ou Xls<br>

                </div>

            @elseif(session('warning'))

                <div class="alert bg-warning alert-dismissible" role="alert">

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    Des erreurs sont survenues lors du traitement du fichier<br>

                    Toutes les donnees n'ont pu etre sauvegarde<br>

                    Veuillez voir ci-joint le fichier contenant les lignes non enregistrees<br>

                </div>

            @endif

                <form id="form_advanced_validation" method="POST" action="{{action('Level\LevelController@import')}}" enctype="multipart/form-data">

                    {{csrf_field()}}



                    <div class="form-group form-float">

                        <div class="form-line">

                            <input type="file" class="form-control" name="importfile" required>

                            {{-- <label class="form-label">Dataset Name</label> --}}

                        </div>

                        <div class="help-info"></div>

                    </div>



                    <button class="btn btn-primary waves-effect" type="submit">IMPORTER</button>

                    <a href="{{route('levels')}}" class="btn btn-warning waves-effect" >Liste des niveaux</a>

                    

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

