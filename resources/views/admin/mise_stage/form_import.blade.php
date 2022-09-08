@extends('layouts/master')

@section('content')

    <div class="block-header">

        <ol class="breadcrumb breadcrumb-col-orange">

            <li><a href="{{route('home')}}">Accueil</a></li>

            <li><a href="{{ route('miseEnStage.data') }}">Gestion des Mises en stage</a></li>

            <li class="active">IMPORTER DES MISES EN STAGE</li>

        </ol>



        <small>Afin d'importer des entrées, choisissez un fichier excel puis cliquer sur le bouton
            <strong>Importer</strong></small><br>

    </div>



    <div class="row clearfix">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="card">

                <div class="header">

                    <h2>IMPORTER DES MISES EN STAGE</h2>

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
                            Veillez vérifier que l'agent existe déjà <br>
                            L'agent est peut être déjà en stage

                        </div>

                    @elseif(session('nomenclatureError'))

                        <div class="alert bg-warning alert-dismissible" role="alert">

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>

                            Des erreurs sont survenues lors du traitement du fichier<br>

                            Les données des champs  ne respectent pas la nomenclatures requise. <br><br> <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-sm">Nomenclature</button><br><br>
                            
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
                            La ville doit respecter la syntaxe des villes. <br>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label>Pays de stage</label><br>
                                    <select name="pays_stage" class="form-control show-tick" data-live-search="true" id="country-dropdown" required>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" style="background:#919c9e; color: #fff;">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    <label class="form-label"></label>
                                </div>
                                <div class="help-info"></div>
                            </div>

                            <div class="form-group form-float">
                                <div class="form-line" id="state-dropdown">
                                    <label>Region de stage</label><br>
                                </div>
                                <div class="help-info"></div>
                            </div>

                            <div class="form-group form-float">
                                <div class="form-line" id="city-dropdown">
                                    <label>Ville de stage</label><br>
                                </div>
                                <div class="help-info"></div>
                            </div>
                            <br>
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
                                                        <td><strong>Structure de mise en Stage (MS)</strong></td>
                                                        <td>3</td>
                                                        <td>Les structures doivent respecter les mêmes syntaxes que celles enrégistrées. </td>
                                                        
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Date signature de MS</strong></td>
                                                        <td>4</td>
                                                        <td>Néant</td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Date de démarrage du stage</strong></td>
                                                        <td>5</td>
                                                        <td>Néant</td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Numero de decision de MS</strong></td>
                                                        <td>6</td>
                                                        <td>Néant</td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Nature ou Source de la bourse</strong></td>
                                                        <td>7</td>
                                                        <td>Les sans bourses sont juste notés avec <strong>null</strong> à la place <strong>TRES IMPORTANT</td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Durée</strong></td>
                                                        <td>8</td>
                                                        <td>Néant</td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Année MS ou de mise en stage</strong></td>
                                                        <td>9</td>
                                                        <td>Doit être une année valide</td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Niveau</strong></td>
                                                        <td>10</td>
                                                        <td>Néant</td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Filière</strong></td>
                                                        <td>11</td>
                                                        <td>Néant</td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Specialité/Option</strong></td>
                                                        <td>12</td>
                                                        <td>Néant</td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Pays de Stage</strong></td>
                                                        <td>13</td>
                                                        <td>Obligatoire</td>
                                                    </tr>


                                                    <tr>
                                                        <td><strong>Ville de Stage</strong></td>
                                                        <td>14</td>
                                                        <td>Obligatoire et doit respecter la syntaxe</td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Ecole de stage</strong></td>
                                                        <td>15</td>
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
                        action="{{ route('miseEnStage.import') }}" enctype="multipart/form-data">

                        {{ csrf_field() }}



                        <div class="form-group form-float">

                            <div class="form-line">

                                <input type="file" class="form-control" name="importfile" required>

                                {{-- <label class="form-label">Dataset Name</label> --}}

                            </div>

                            <div class="help-info"></div>

                        </div>



                        <button class="btn btn-primary waves-effect" type="submit">IMPORTER</button>

                        <a href="{{ route('miseEnStage.data') }}" class="btn btn-warning waves-effect">Liste des mise en stages</a>

                       @if(session('examplaire'))
                            @php
                                $examplaire = session('examplaire');
                            @endphp
                            <a href="{{ url($examplaire) }}" class="btn btn-secondary waves-effect">Telecharger l'examplaire</a>
                        @else
                            <a href="{{ route('miseEnStage.genererExp') }}" class="btn btn-secondary waves-effect">Générer un examplaire</a>
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



@push('script')

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script type="text/javascript">


    function al(){
        var id_state = document.getElementById('states').value;
        console.log(id_state);
        var url = "{{route('get-cities-by-state', '')}}"+"/"+id_state;
            fetch(url)
              .then(function(res) {
                if (res.ok) {
                  return res.json();
                }
              })
              .then(function(value) {
                console.log(value);
                var myDiv = document.getElementById("city-dropdown");
                //Create and append select list
                if(!!document.getElementById("cities")){
                    document.getElementById("cities").remove();
                }
                var selectList = document.createElement("select");
                selectList.setAttribute("name", "ville_stage");
                selectList.setAttribute("id", "cities");
                selectList.setAttribute("class", "form-control");
                myDiv.appendChild(selectList);

                for (const [key, val] of Object.entries(value)) {
                    for (const [k2, v2] of Object.entries(val)) {
                        var id = "";
                        var name = "";
                        for (const [k3, v3] of Object.entries(v2)) {
                            if(k3 == 'id'){
                                id = v3;
                                continue;
                            }
                                name = v3;
                        }
                        var option = document.createElement("option");
                        option.setAttribute("value",id);
                        option.text = name;
                        selectList.appendChild(option);
                    }
                  
                }
              })
              .catch(function(err) {
                // Une erreur est survenue
              });
    }

    $(document).ready(function() {
        $('#country-dropdown').on('change', function() {
            console.log('in change');
                var id_country = document.getElementById('country-dropdown').value;
                console.log(id_country);
                var url = "{{route('get-states-by-country', '')}}"+"/"+id_country;
                fetch(url)
              .then(function(res) {
                if (res.ok) {
                  return res.json();
                }
              })
              .then(function(value) {
                console.log(value);
                var myDiv = document.getElementById("state-dropdown");
                //Create and append select list
                if(!!document.getElementById("states")){
                    document.getElementById("states").remove();
                }
                var selectList = document.createElement("select");
                selectList.setAttribute("name", "region_stage");
                selectList.setAttribute("id", "states");
                selectList.setAttribute("onchange", "al()");
                selectList.setAttribute("class", "form-control");
                myDiv.appendChild(selectList);

                for (const [key, val] of Object.entries(value)) {
                    for (const [k2, v2] of Object.entries(val)) {
                        var id = "";
                        var name = "";
                        for (const [k3, v3] of Object.entries(v2)) {
                            if(k3 == 'id'){
                                id = v3;
                                continue;
                            }
                                name = v3;
                        }
                        var option = document.createElement("option");
                        option.setAttribute("value",id);
                        option.text = name;
                        selectList.appendChild(option);
                    }
                  
                }
              })
              .catch(function(err) {
                // Une erreur est survenue
              });
        }); 
    });
</script>



@endpush