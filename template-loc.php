<div id="menuLocations">
    <ul>
        <li><a href="?page=location_map">Créer une carte</a></li>
        <?php
        $loclist = $this->getloclist();
        foreach($loclist as $getloc){
            if($_GET['id']== $getloc->id){
                $active = 'id="active"';
            } else {
                $active="";
            }
            echo "<li ".$active."><a href='?page=location_map&p=location&id=".$getloc->id."'>".$getloc->titre."</a></li>";
        }
        ?>
    </ul>
</div>
<div class="emplacement">
    <?php $loc=$this->getlocation($_GET['id']); ?>
    <h3 class="titre">Emplacement : <?php echo $loc[0]->titre; ?></h3>
    <div id="placecode">
        <p>Copiez (ctrl+C) le code ci-dessus et collez-le (ctrl+V) là où vous le souhaitez</p>
        <p type="text" id="shortcode"> [location id=&quot;<?php echo $loc[0]->id; ?>&quot;]</p>
    </div>
    <div id="infosContainer">
        <div id="infosLocation">
            <div id="locPreview">
                <h2 class="title"> <?php echo $loc[0]->ville ;?> </h2>
                <p>Tous les <?php echo $loc[0]->jours ;?></p>
                <p><?php echo $loc[0]->horaires ;?></p>
                <p><?php echo $loc[0]->adresse ;?></p>
                <p><?php echo $loc[0]->CP ;?> <?php echo $loc[0]->ville; ?></p>
                <section id="map"></section>
                <script>
                    var coord;
                    var marker;
                    var options;
                    var map;
                    function initmap() {
                        coord = new google.maps.LatLng( <?php echo $loc[0]->coord?>);
                        options = {
                            center: coord,
                            zoom: 12,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
                        map = new google.maps.Map(document.getElementById("map"), options);
                        marker = new google.maps.Marker({
                            position: coord,
                            map: map,
                        });
                    }
                    initmap();
                </script>
            </div>
            <form action="?page=location_map&action=updateloc" method="POST" id="location-form">
                <small>* Champs obligatoires</small>
                <input type="hidden" name="id" value="<?php echo $loc[0]->id ?>">
                <div class="input-group">
                    <span class="error" id="titre-error">Veuillez renseigner un nom</span>
                    <label for="titre">Titre* :</label>
                    <input type="text" name="titre" id="titre" class="required" value="<?php echo $loc[0]->titre ?>">
                </div>
                <div class="input-group">
                    <label for="adresse">Adresse :</label>
                    <input type="text" name="adresse" id="adresse" value="<?php echo $loc[0]->adresse ?>">
                </div>
                <div class="input-group">
                    <span class="error" id="cp-error">Veuillez renseigner un code postal</span>
                    <label for="cp">CP* :</label>
                    <input type="text" name="cp" id="cp" class="required" value="<?php echo $loc[0]->CP ?>">
                </div>
                <div class="input-group">
                    <span class="error" id="ville-error">Veuillez renseigner une commune</span>
                    <label for="ville">Ville* :</label>
                    <input type="text" name="ville" id="ville" class="required" value="<?php echo $loc[0]->ville ?>"> 
                </div>
                <div class="input-group">
                    <label for="jours">Jours :</label>
                    <input type="text" name="jours" id="jours" value="<?php echo $loc[0]->jours ?>">
                </div>
                <div class="input-group">
                    <label for="horaires">Horaires :</label>
                    <input type="text" name="horaires" id="horaires" value="<?php echo $loc[0]->horaires ?>">
                </div>
                <div class="input-group">
                <span class="error" id="coordonnees-error">Veuillez renseigner des coordonnées</span>
                    <label for="coordonnees">Coordonnées* :</label>
                    <input type="text" name="coordonnees" id="coordonnees" class="required" value="<?php echo $loc[0]->coord ?>">
                </div>
                <input type="submit" id="updateLoc" class="button-primary envoi" value="Mettre à jour" class="envoi">
            </form>  
            <form action="?page=location_map&action=deleteloc" method="post" id="deleteform">
                <input type="hidden" name="id" value="<?php echo $loc[0]->id; ?>"> 
                <input type="submit" id="deleteLoc" value="Supprimer l'emplacement">
            </form>   
         </div>
    </div>
</div>