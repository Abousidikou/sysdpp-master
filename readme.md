<p align="center"><img src="http://mtfp.sohagroupbenin.com/template/adminBSB/images/user-img-background.jpg"></p>
<h1> Deploiement sur serveur Apache</h1>
<p>
  La présente application est développée en PHP avec le framework LARAVEL, avec pour base de donnees MariaDB.
  Il est donc à noter que son déploiement suit les procédures de mise en place d'une application WEB Laravel sur apache
</p>

<h2>Exigences du serveur</h2>
<p>
  <ul>
    <li>PHP >= 7.0.0</li>
    <li>OpenSSL PHP Extension</li>
    <li>PDO PHP Extension</li>
    <li>Mbstring PHP Extension</li>
    <li>Tokenizer PHP Extension</li>
    <li>XML PHP Extension</li>
    <li>Mod rewrite </li>
  </ul>
</p>

<h2>Différentes étapes à suivre pour le déploiement</h2>
<p>
  <ol>
    <li>Copier le code source de l'application à la racine d'un dossier sur le serveur</li>
    <li>
      Créer un <strong>VirtualHost</strong> et prendre en compte les précisions suivantes sur les directives ci-après
      <ul>
        <li><code>DocumentRoot /var/www/html/mtfp/public</code><sup>*</sup></li>
        <li>Appliquer la directive <code>AllowOverride All</code> sur le dossier racine de l'application sur le serveur</li>
      </ul>
    </li>
    <li>Changer les permissions du dossier <strong>storage</strong> en tapant la commande <code>sudo chmod -R 775 storage</code>  tout en étant à la racine de l'application</li>
    <li>
      Editer le fichier <strong>.env</strong> en modifiant les paramètres d'accès à la base de données
      <ul>
        <li><code>DB_CONNECTION=mysql</code></li>
        <li><code>DB_HOST=adresse serveur de base de donnees</code></li>
        <li><code>DB_PORT=port du serveur</code></li>
        <li><code>DB_DATABASE=le nom de la base de donnees</code></li>
        <li><code>DB_USERNAME=utilisateur de la base de donnees</code></li>
        <li><code>DB_PASSWORD=mot de passe</code></li>
      </ul>
    </li>
    <li>Importer le fichier mtfp_.sql dans la base créée</li>
    <li>A la racine de l'application, taper la commande <code>composer dump-autoload</code></li>
  </ol>
  
  <strong>NB:<i>Il faut installer l'outil composer sur le serveur afin de pouvoir executer la commande <code>composer dump-autoload</code> </i></strong> <br>
  <strong>NB:<i>Le dossier mtfp dans <code>DocumentRoot /var/www/html/mtfp/public</code> représente le dossier de l'application sur le serveur</i></strong> <br>
  
  Ainsi s'achève la procédure de déploiement sur le serveur.
</p>





