@extends('layouts/master')

@section('content')
<div class="block-header">
    <h2>MISE A JOUR DE SOUS-DOMAINE</h2>
    <small>Afin de valider la mise à jour, remplissez les champs puis cliquez sur le bouton <strong>Valider</strong> </small>
</div>
   

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>METTRE A JOUR SOUS-DOMAINE</h2>
            </div>
            <div class="body">
                @if(session('success'))
                    <div class="alert bg-green alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Sous-domaine mis à jour avec succès.
                    </div>
                @elseif(session('error'))
                    <div class="alert bg-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Une erreur s'est produite au cours de la mise à jour... Veuillez rééssayer.
                    </div>
                @elseif(session('validation'))
                    <div class="alert bg-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Erreur de validation... <br>
                        Veuillez rééssayer.
                    </div>
                @endif
                <form id="form_advanced_validation" method="POST" action="{{action('SubDomain\SubDomainController@update')}}">
                    {{csrf_field()}}
                    <input type="hidden" class="form-control" name="id" value="{{$subdomain->id}}" required>
                    
                    <div class="form-group form-float">
                        <div class="form-line">
                            <select name="domain" class="form-control show-tick" data-live-search="true">
                                <?= $domainsOptions ?>
                            </select>
                            <label class="form-label"></label>
                        </div>
                        <div class="help-info"></div>
                    </div>
                    
                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="text" class="form-control" name="wording" value="{{$subdomain->wording}}" required>
                            <label class="form-label">Libellé</label>
                        </div>
                        <div class="help-info"></div>
                    </div>
                    
                    <button class="btn btn-primary waves-effect" type="submit">VALIDER</button>
                    <a class="btn btn-warning waves-effect" href="{{route('subdomains')}}">Liste des sous domaines</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection