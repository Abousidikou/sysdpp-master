@extends('layouts/master')

@section('content')

    <div class="block-header">

        <ol class="breadcrumb breadcrumb-col-orange">

            <li><a href="{{route('home')}}">Accueil</a></li>

            <li><a href="{{ route('domains') }}">Gestion des Agents</a></li>

            <li class="active">IMPORTER DES AGENTS</li>

        </ol>



        <small>Afin d'importer des domaines, choisissez un fichier excel puis cliquer sur le bouton
            <strong>Importer</strong></small>

    </div>



    <div class="row clearfix">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="card">

                <div class="header">

                    <h2>IMPORTER DES AGENTS</h2>

                </div>

                <div class="body">

                    @if (session('success'))

                        <div class="alert bg-green alert-dismissible" role="alert">

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>

                            Importation terminee avec succes

                        </div>

                    @elseif(session('unit'))

                        <div class="alert bg-warning alert-dismissible" role="alert">

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>

                            Le format du fichier excel est incorrecte, <strong>il manque la colonne Unit</strong>

                        </div>

                    @elseif(session('validation'))

                        <div class="alert bg-danger alert-dismissible" role="alert">

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>

                            Erreur de validation...Veuillez rééssayer avec un fichier Xlsx ou Xls<br>

                        </div>

                    @elseif(session('warning'))

                        <div class="alert bg-warning alert-dismissible" role="alert">

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>

                            Des erreurs sont survenues lors du traitement du fichier<br>

                            Toutes les donnees n'ont pu etre sauvegarde<br>

                            Veuillez voir ci-joint le fichier contenant les lignes non enregistrees<br>

                        </div>
                    @elseif(session('nomenclatureError'))

                        <div class="alert bg-warning alert-dismissible" role="alert">

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>

                            Des erreurs sont survenues lors du traitement du fichier<br>

                            Les données des champs <strong>Matricule, Nom & prénoms, Sexe, Statut, Catégorie, Corps et Structures</strong> ne respectent pas la nomenclatures requise. <br><br> <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-sm">Nomenclature</button><br><br>
                            
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
                                Voir la Liste des structures
                            </button> <br>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <strong>Structures</strong><br>
                                    <ul class="list-group">
                                        @if (isset($structures))
                                            @foreach($structures as $strut)
                                            <li class="list-group-item">{{ $strut->wording }}</li>
                                            @endforeach
                                        @endif
                                        </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                </div>
                                </div>
                            </div>
                            </div>
                            
                            <br>
                            Les champs doivent être dans le même ordre que dans la nomenclature. <em>Générer un exemplaire pour plus de précision.</em> <br>
                            Veuillez les corriger puis réessayer.  <br>

                            <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="form-group form-float">
                                            <strong>Nomenclature</strong><br><br>
                                            <table class="table table-striped table-bordered table-condensed">
                                                <thead>
                                                    <th>Nom de la colonne</th>
                                                    <th>Numero de la  colonne</th>
                                                    <th>Exigences</th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><strong>Matricule</strong></td>
                                                        <td>1</td>
                                                        <td>Doit être unique par agent</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Nom & prenom</strong></td>
                                                        <td>2</td>
                                                        <td>Obligatoire</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Diplome de Base</strong></td>
                                                        <td>3</td>
                                                        <td>Néant</td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Sexe</strong></td>
                                                        <td>4</td>
                                                        <td>Comprise entre [ 'Féminin', 'Masculin', 'Total sexe', 'null' ]. Ces valeurs doivent être écrite telles.</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Statuts</strong></td>
                                                        <td>5</td>
                                                        <td>Comprise entre [ 'ACE', 'ACE', 'Total_statut', 'null' ]. Ces valeurs doivent être écrite telles.</td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Catégorie</strong></td>
                                                        <td>6</td>
                                                        <td>Comprise entre [ 'A', 'B', 'C' , 'D' , 'E' , 'S' , 'Total_catégorie' , 'null' ]. Ces valeurs doivent être écrite telles.</td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Corps de la Fonction</strong></td>
                                                        <td>7</td>
                                                        <td>Les coprs sont en eciture camelCase (Ex: Assistant du Président). Les articles et les pronoms sont tous en minuscule. </td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Structure de mise en Stage (MS)</strong></td>
                                                        <td>8</td>
                                                        <td>Les structures doivent respecter les mêmes syntaxes que celles enrégistrées. </td>
                                                        
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Plan de formation</strong></td>
                                                        <td>9</td>
                                                        <td>Néant</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn  btn-secondary" data-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                            </div>

                            

                        </div>

                    @endif

                    <form id="form_advanced_validation" method="POST"
                        action="{{ route('agentFormation.import') }}" enctype="multipart/form-data">

                        {{ csrf_field() }}



                        <div class="form-group form-float">

                            <div class="form-line">

                                <input type="file" class="form-control" name="importfile" required>

                                {{-- <label class="form-label">Dataset Name</label> --}}

                            </div>

                            <div class="help-info"></div>

                        </div>



                        <button class="btn btn-primary waves-effect" type="submit">IMPORTER</button>

                        <a href="{{ route('agentFormation.data') }}" class="btn btn-warning waves-effect">Liste des Agents</a>



                        @if(session('examplaire'))
                            @php
                                $examplaire = session('examplaire');
                            @endphp
                            <a href="{{ url($examplaire) }}" class="btn btn-secondary waves-effect">Télécharger l'examplaire</a>
                        @else
                            <a href="{{ route('agentFormation.genererExp') }}" class="btn btn-secondary waves-effect">Générer un examplaire</a>
                        @endif

                        @if (session('path'))

                            @php
                                
                                $path = session('path');
                                
                            @endphp

                            <a href="{{ url($path) }}" class="btn btn-secondary waves-effect">Telecharger le fichier</a>

                        @endif

                    </form>

                </div>

            </div>

        </div>

    </div>



@endsection
