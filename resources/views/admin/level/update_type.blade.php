@extends('layouts/master')



@section('content')

<div class="block-header">

    <ol class="breadcrumb breadcrumb-col-orange">

        <li><a href="{{route('home')}}">Accueil</a></li>

        <li><a href="{{route('levels')}}">Gestion des niveaux de desagregation</a></li>

        <li class="active">METTRE A JOUR TYPE</li>

    </ol>



    <small>Afin de valider la mise à jour, remplissez les champs puis cliquez sur le bouton <strong>Valider</strong> </small>

</div>





<div class="row clearfix">

    <div class="col-sm-4 col-sm-offset-4 col-xs-12">

        <div class="card">

            <div class="header">

                <h2>METTRE A JOUR TYPE</h2>

            </div>

            <div class="body">

                @if(session('success'))

                    <div class="alert bg-green alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        Type mis à jour avec succès.

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

                <form id="type-form" method="POST" action="{{action('Level\LevelController@updateType')}}">

                    {{csrf_field()}}

                    <input type="hidden" name="id" class="form-control" value="{{$type->id}}" required>

                    <div class="form-group form-float">

                        <div class="form-line">

                            <input type="text" name="wording" class="form-control" value="{{$type->wording}}" required>

                            <label class="form-label">Libellé</label>

                        </div>

                        <div class="help-info"></div>

                    </div>



                    <button class="btn btn-primary waves-effect" type="submit">VALIDER</button>

                </form>

{{-- 

                <div class="body table-responsive"> 

                    <table class="table table-striped table-bordered table-condensed">

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

                                    <a href="/update/tmp/type/{{$type->id}}" class="btn bg-green waves-effect"

                                        title="Cliquer pour modifier"

                                    >

                                        <i class="material-icons" >edit</i>

                                    </a>

                                    <a href="/delete/type/{{$type->id}}" class="btn bg-red waves-effect"

                                        title="Cliquer pour supprimer"

                                    >

                                        <i class="material-icons">delete_forever</i>

                                    </a>

                                </td>

                            </tr>

                            @endforeach



                        </tbody>

                        <caption style="caption-side: top; text-align:center" title="">

                            <a href="#" class="btn bg-green waves-effect">Les types</a>

                        </caption>

                    </table>

                </div> --}}

            </div>

        </div>

    </div>

</div>



@endsection