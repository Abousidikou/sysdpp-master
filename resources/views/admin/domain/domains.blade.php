@extends('layouts/master')

@section('content')
<div class="block-header">
    <ol class="breadcrumb breadcrumb-col-orange">
        <li><a href="{{route('home')}}">Accueil</a></li>
        <li class="active">GESTION DES DOMAINES</li>
    </ol>

    <small>Pour ajouter un nouveau domaine, cliquez sur le bouton <strong>Ajouter un domaine</strong> </small>
</div>

<div class="row clearfix">
    <div class="col-sm-offset-1 col-sm-10 col-xs-10">
        <div class="card">
            <div class="header">
                <h2>
                    DOMAINES DU MTFP
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
                            <th>Domaines</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($domains as $domain)
                        <tr>
                            <td>{{$domain->wording}}</td>
                            @if(Auth::user()->role == "admin")
                                <td>
                                    <div style="display: flex">
                                        <a href="{{route('prepareUpdate.domain', ['id' => $domain->id])}}" class="btn bg-green waves-effect"
                                            title="Cliquer pour modifier le domaine"
                                        >
                                            <i class="material-icons">edit</i>
                                        </a> 
                                        <a onclick="return confirm('Êtes-vous sûre?')" href="{{route('delete.domain', ['id' => $domain->id])}}" class="btn bg-red waves-effect"
                                            title="Cliquer pour supprimer le domaine"
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
                        <caption style="caption-side: top; text-align:center" title="Cliquer pour ajouter un nouveau domaine">
                            <a href="{{route('form.domain')}}" class="btn bg-green waves-effect">Ajouter un domaine</a>
                            <a href="{{route('domain.export')}}" class="btn bg-green waves-effect">Exporter</a>
                            <a href="{{route('domain.import')}}" class="btn bg-green waves-effect">Importer un fichier</a>
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