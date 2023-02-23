<?php

/*
Plugin Name: Emplacements
Description: Insère les emplacements avec les horaires et la location
Version: 1.0
Author: Sarah Schneider
*/



if(!class_exists('Location_map')) {
    class Location_map {
        function location_map_install(){
            global $wpdb;
            $table_site = $wpdb->prefix.'location-map';
            if($wpdb->get_var("SHOW TABLES LIKE '$table_site'")!=$table_site){
                $sql= "CREATE TABLE `$table_site`(
                    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, 
                    `titre` TEXT NOT NULL, 
                    `adresse` TEXT, 
                    `CP` INT, 
                    `ville` TEXT NOT NULL, 
                    `jours` TEXT, 
                    `horaires` TEXT, 
                    `coord` TEXT NOT NULL)
                    ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
                require_once(ABSPATH.'wp-admin/includes/upgrade.php');
                dbDelta( $sql );
            }
        }

        function location_map_admin_header() {
            wp_register_style('location_map_css', plugins_url('css/admin-location-map.css', __FILE__));
            wp_enqueue_style( 'location_map_css');
            wp_enqueue_script( 'location_map_js', plugins_url('js/admin-location-map.js', __FILE__), array('jquery'));
            wp_enqueue_script( 'google_map_js', 'https://maps.googleapis.com/maps/api/js?key='.get_option( 'cleApi').'&callback=initmap');
        }

        function location_map_front_header() {
            wp_register_style('location_map_front_css', plugins_url('css/front-location-map.css', __FILE__));
            wp_enqueue_style( 'location_map_front_css');
            wp_enqueue_script( 'google_map_js', 'https://maps.googleapis.com/maps/api/js?key='.get_option( 'cleApi').'&callback=initmap');
        }

        function insertloc($titre, $adresse, $cp, $ville, $jours, $horaires, $coord) {
            global $wpdb;
            $table_loc = $wpdb->prefix.'location-map';
            $sql = $wpdb->prepare(
                "INSERT INTO `".$table_loc."` (`titre`, `adresse`, `CP`, `ville`, `jours`, `horaires`, `coord`) VALUES (%s,%s,%d,%s,%s,%s,%s)",$titre, $adresse, $cp, $ville, $jours, $horaires, $coord
            );
            $req = $wpdb->query($sql);
            return $req;
        }

        function updateloc($titre, $adresse, $cp, $ville, $jours, $horaires, $coord, $id) {
            global $wpdb;
            $table_loc = $wpdb->prefix.'location-map';
            $sql = $wpdb->prepare(
                "UPDATE `".$table_loc."` SET 
                    `titre`=%s, `adresse`=%s , `CP`=%d, `ville`=%s, `jours`=%s, `horaires`=%s, `coord`=%s WHERE `id`=%d", $titre, $adresse, $cp, $ville, $jours, $horaires, $coord, $id
            );
            $req = $wpdb->query($sql);
            return $req;
        }

        function deleteloc($id) {
            global $wpdb;
            $table_loc = $wpdb->prefix.'location-map';
            $sql = $wpdb->prepare(
                "DELETE from `".$table_loc."` WHERE `id`= %d",$id
            );
            $req = $wpdb->query($sql);
            return $req;
        }

        function location_map_admin_page(){
            if(isset($_GET['p'])&&$_GET['p']=='location'){
                require_once('template-loc.php');
            } else {
                require_once('template-admin.php');
            }
            
            if(isset($_GET['action'])){
                if($_GET['action'] == 'createloc') {
                    if((trim($_POST['titre']) != '') && (trim($_POST['cp']) != '') && (trim($_POST['ville']) != '') && (trim($_POST['coordonnees']) != '')){
                        $insertloc = $this->insertloc($_POST['titre'], $_POST['adresse'], $_POST['cp'], $_POST['ville'], $_POST['jours'], $_POST['horaires'], $_POST['coordonnees']);
                        if($insertloc){
                            ?>
                            <script type="text/javascript">
                               var url = location.href;
                               url = url.split('?');
                                window.location = url[0]+"?page=location_map&loc=ok";
                            </script>
                            <?php
                        } else {
                            echo'<p class="erreur">Une erreur est survenue.</p>';
                        }
                    } else {
                        echo '<p class="erreur">Veuillez remplir tous les champs</p>';
                    }
                };
                if($_GET['action'] == 'updateloc') {
                    if((trim($_POST['titre']) != '') && (trim($_POST['cp']) != '') && (trim($_POST['ville']) != '') && (trim($_POST['coordonnees']) != '') && ($_POST['id']!='')){
                        $updateloc = $this->updateloc($_POST['titre'], $_POST['adresse'],$_POST['cp'],$_POST['ville'],$_POST['jours'],$_POST['horaires'],$_POST['coordonnees'], $_POST['id']);
                        if($updateloc){
                            ?>
                            <script type="text/javascript">
                               var url = location.href;
                               url = url.split('?');
                                window.location = url[0]+"?page=location_map&updateloc=ok";
                            </script>
                            <?php
                        } else {
                            echo'<p class="erreur">Une erreur est survenue.</p>';
                        }
                    } else {
                        echo '<p class="erreur">Veuillez remplir tous les champs</p>';
                    }
                };
                if($_GET['action'] == 'deleteloc') {
                    if($_POST['id']!=''){
                        $deleteloc = $this->deleteloc($_POST['id']);
                        if($deleteloc){
                            ?>
                            <script type="text/javascript">
                               var url = location.href;
                               url = url.split('?');
                                window.location = url[0]+"?page=location_map&deleteloc=ok";
                            </script>
                            <?php
                        } else {
                            echo'<p class="erreur">Une erreur est survenue.</p>';
                        }
                    } else {
                        echo '<p class="erreur">Une erreur est survenue</p>';
                    }
                }            
             }
            if(isset($_GET['loc'])) {
                if($_GET['loc']== 'ok'){
                    echo "<span class='succes'> L'emplacement a bien été enregistré </p>";
                }
            };
            if (isset($_GET['updateloc'])) {
                if($_GET['updateloc']== 'ok'){
                    echo "<span class='succes'> L'emplacement a bien été modifié </p>";
                }
            };
            if (isset($_GET['deleteloc'])) {
                if($_GET['deleteloc']== 'ok'){
                    echo "<span class='succes'> L'emplacement a bien été supprimé </p>";
                }
            }
        }


        function getloclist() {
            global $wpdb;
            $table_loc = $wpdb->prefix.'location-map';
            $sql ="SELECT * FROM `".$table_loc."`;";
            $loclist = $wpdb->get_results($sql);
            return $loclist;
        }

        function getlocation($id){
            global $wpdb;
            $table_loc = $wpdb->prefix.'location-map';
            $sql = $wpdb->prepare("SELECT * FROM `".$table_loc."`WHERE id=%d LIMIT 1", $id);
            $loc = $wpdb->get_results($sql);
            return $loc;
        }

        function champ_cleApi(){
            ?>
            <input type="text" name="cleApi" id="cleApi" value="<?php echo get_option('cleApi'); ?>" size="60" minlength="32">
            <?php
        }

        function location_options() {
            add_settings_section( "location-section", "", null, "Location_map");
            add_settings_field( "cleApi", "Votre cle API", array($this, "champ_cleApi"), "Location_map", "location-section");
            register_setting( "location-section", "cleApi");
        }

        function location_card_shortcode($att) {
            $loc = $this->getlocation($att['id']);
            ob_start();
            ?>
            <div class="locCard" id="loc<?php echo $loc[0]->id ?>">
                <span class="title"> <?php echo $loc[0]->ville ;?> </span>
                <span>Tous les <strong class="jours"><?php echo $loc[0]->jours ;?></strong></span>
                <span class="horaires"><?php echo $loc[0]->horaires ;?></span>
                <span class="adresse"><?php echo $loc[0]->adresse ;?></span>
                <span class="adresse"><?php echo $loc[0]->CP ;?> <?php echo $loc[0]->ville; ?></span>
                <section id="map<?php echo $loc[0]->id ?>" class="map"></section>
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
                            map = new google.maps.Map(document.getElementById("map<?php echo $loc[0]->id ?>"), options);
                            marker = new google.maps.Marker({
                                position: coord,
                                map: map,
                            });
                        }
                        initmap();
                    </script>
            </div>
            <?php return ob_get_clean(); 
        }


        function init(){
                if(function_exists('add_menu_page')){
                    $mapage = add_menu_page( 'Emplacements', 'Emplacements', 'administrator', dirname(__FILE__), array($this, 'location_map_admin_page'), 'dashicons-location');
                    add_action( 'load-'.$mapage, array($this, 'location_map_admin_header'), 'dashicons-location');
                }
            }
 }}

if(class_exists('Location_map')) {
    $inst_map = new Location_map();
}


if(isset($inst_map)){
    register_activation_hook( __FILE__, array($inst_map, 'location_map_install') );
    add_action( 'admin_menu', array($inst_map, 'init'), 99);
    add_action( "admin_init", array($inst_map, 'location_options'));
    add_action( "wp_enqueue_scripts", array($inst_map, 'location_map_front_header'));

    if(function_exists('add_shortcode')){
        add_shortcode( 'location', array($inst_map, 'location_card_shortcode'));
    }
}