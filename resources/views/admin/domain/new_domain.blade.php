@extends('layouts/master')

@section('content')
<div class="block-header">
    <ol class="breadcrumb breadcrumb-col-orange">
        <li><a href="{{route('home')}}">Accueil</a></li>
        <li><a href="{{route('domains')}}">Gestion des domaines</a></li>
        <li class="active">CREER DOMAINE</li>
    </ol>

    <small>Afin de valider la création du nouveau domaine, remplissez les champs puis cliquez sur le bouton <strong>Valider</strong> </small>
</div>


<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>CREER DOMAINE</h2>
            </div>
            <div class="body">
                @if(session('success'))
                    <div class="alert bg-green alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Nouveau domaine ajouté avec succès.
                    </div>
                @elseif(session('error'))
                    <div class="alert bg-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Une erreur s'est produite au cours de l'ajout... Veuillez rééssayer.
                    </div>
                @elseif(session('validation'))
                    <div class="alert bg-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Erreur de validation... Il se peut que le domaine existe déjà. <br>
                        Les champs ne doivent pas contenir des chiffres
                    </div>
                @endif
                <form id="form_advanced_validation" method="POST" action="{{action('Domain\DomainController@create')}}">
                    {{csrf_field()}}
                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="text" class="form-control" name="wording" required>
                            <label class="form-label">Domaine</label>
                        </div>
                        <div class="help-info"></div>
                    </div>
                    <button class="btn btn-primary waves-effect" type="submit">VALIDER</button>
                    <a href="{{route('domains')}}" class="btn btn-warning waves-effect" >Liste des domaines</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection