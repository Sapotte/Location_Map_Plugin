<div id="apikey">
    <p class="important">La clef API est indispensable pour l'affichage de a carte</p>
    <a href="https://console.developers?google.com/" target="_blank" class="button-primary">Obtenir une clé API gratuitement</a>
    <form action="options.php" method="post">
        <?php 
            settings_fields("location-section");
            do_settings_sections( "Location_map" );
            submit_button( "Enregistrer la clé");
        ?>
    </form>
</div>
<div id="menuLocations">
    <ul>
        <li id="active">Créer un emplacement</li>
    <?php
        $loclist = $this->getloclist();
            foreach($loclist as $getloc){
                echo "<li><a href='?page=location_map&p=location&id=".$getloc->id."'>".$getloc->titre."</a></li>";
            }
    ?>
    </ul>
</div>
<div class="emplacement">
    <h2>Emplacement</h2>
    <h3>Créer un emplacement :</h3>
    <small>*champs obligatoires</small>
    <form action="?page=location_map&action=createloc" method="POST" id="location-form">
        
        <div class="input-group">
            <span class="error" id="titre-error">Veuillez renseigner un nom</span>
            <label for="titre">Titre* :</label>
            <input type="text" name="titre" id="titre" class="required">
        </div>
        <div class="input-group">
            <label for="adresse">Adresse :</label>
            <input type="text" name="adresse" id="adresse">
        </div>
        <div class="input-group">
            <span class="error" id="cp-error">Veuillez renseigner un code postal</span>
            <label for="cp">CP* :</label>
            <input type="text" name="cp" id="cp" class="required">
        </div>
        <div class="input-group">
            <span class="error" id="ville-error">Veuillez renseigner une commune</span>
            <label for="ville">Ville* :</label>
            <input type="text" name="ville" id="ville" class="required">
        </div>
        <div class="input-group">
            <label for="jours">Jours :</label>
            <input type="text" name="jours" id="jours">
        </div>
        <div class="input-group">
            <label for="horaires">Horaires :</label>
            <input type="text" name="horaires" id="horaires" >
        </div>
        <div class="input-group">
        <span class="error" id="coordonnees-error">Veuillez renseigner des coordonnées</span>
            <label for="coordonnees">Coordonnées* :</label>
            <input type="text" name="coordonnees" id="coordonnees" class="required">
            <small> Copiez/Collez directement depuis GoogleMap</small>
        </div>
        <input type="submit" class="button-primary" value="Créer l'emplacement" id="submit_location" class="envoi">
        
    </form>
</div>