@extends('layouts/master')



@section('content')

    <div class="block-header">

        <h2>GESTION DE PROFIL</h2>

    </div>

    <div class="row clearfix">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="card">

                <div class="header">

                    <h2>

                        MON PROFIL

                    </h2>

                </div>

                <div class="body">

                    <h2 class="card-inside-title">Mes informations</h2>

                    @if (session('success'))

                        <div class="alert bg-green alert-dismissible" role="alert">

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>

                            Modification réussie

                        </div>

                    @endif

                    @if (session('error'))

                        <div class="alert bg-danger alert-dismissible" role="alert">

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>

                            Erreur au cours de la modification... Rééssayer

                        </div>

                    @endif

                    <div class="row clearfix">

                        <form id="update-profile" action="{{ route('update.profile') }}" method="POST"
                            style="display: none;">

                            {{ csrf_field() }}

                            <input type="text" name="val" id="val">

                            <input type="text" name="type" id="type">

                        </form>

                        <div class="col-md-4">

                            <div class="input-group">

                                <span class="input-group-addon">

                                    <i class="material-icons">person</i>

                                </span>

                                <div class="form-line">

                                    <input id="name" type="text" class="form-control date"
                                        placeholder="Votre Nom et prénoms" value="{{ Auth::user()->name }}">

                                </div>

                                <span class="input-group-addon" title="Cliquer pour modifier le nom">

                                    <a href="#" onclick="updateUserInfos('name')"><i class="material-icons">done_all</i></a>

                                </span>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="input-group">

                                <span class="input-group-addon">

                                    <i class="material-icons">email</i>

                                </span>

                                <div class="form-line">

                                    <input id="email" type="email" class="form-control date" placeholder="Votre email"
                                        value="{{ Auth::user()->email }}">

                                </div>

                                <span class="input-group-addon" title="Cliquer pour modifier email">

                                    <a href="#" onclick="updateUserInfos('email')"><i
                                            class="material-icons">done_all</i></a>

                                </span>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="input-group">

                                <span class="input-group-addon">

                                    <i class="material-icons">lock</i>

                                </span>

                                <div class="form-line">

                                    <input id="passwd" type="password" class="form-control date"
                                        placeholder="Modifier mot de passe">

                                </div>

                                <span class="input-group-addon" title="Cliquer pour modifier le mot de passe">

                                    <a href="#" onclick="updateUserInfos('passwd')"><i
                                            class="material-icons">done_all</i></a>

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    </div>

@endsection

@push('script')

    <script>
        function updateUserInfos(id) {

            var val = document.getElementById(id).value

            valInput = document.getElementById('val')

            typeInput = document.getElementById('type')

            updateProfile = document.getElementById('update-profile');

            switch (id) {



                case 'name':

                    if (isNull(val) == true) {

                        alert("Vous devez remplir le champ afin de pouvoir modifier");

                    } else {

                        valInput.value = val;

                        typeInput.value = 'name';

                        updateProfile.submit();

                    }

                    break;



                case 'email':

                    if (isNull(val) == true) {

                        alert("Vous devez remplir le champ afin de pouvoir modifier");

                    } else {

                        valInput.value = val;

                        typeInput.value = 'email';

                        updateProfile.submit();

                    }

                    break;



                case 'passwd':

                    if (isNull(val) == true) {

                        alert("Vous devez remplir le champ afin de pouvoir modifier");

                    } else {

                        var confirmPassword = prompt("Confirmation du nouveau mot de passe");



                        if (confirmPassword == val)

                        {

                            valInput.value = val;

                            valInput.type = "password";

                            typeInput.value = 'passwd';

                            updateProfile.submit();

                        } else {

                            alert("Erreur de confirmation de mot de passe");

                            return;

                        }



                    }

                    break;



                default:



                    break;

            }

        }



        function isNull(val) {

            if (val == null)

            {

                return true;

            }

            val = val.trim();

            if (val == "" || val == undefined || typeof val == "undefined") {

                return true;

            } else {

                return false;

            }

        }
    </script>

@endpush
