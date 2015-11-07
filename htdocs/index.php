<?php
/**
 * Punchi.me
 * Main UI
 * @author OnlineCreation - Raphaël
 * @licence GNU GPL v3.0
 * @link http://www.onlinecreation.pro Authors' website
 * @link http://www.punchi.me Demo
 */
session_start();
require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo SITE_TITLE; ?></title>
        <meta name="description" content="<?php echo SITE_DESCRIPTION; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="http://www.onlinecreation.pro">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/plupload/2.1.7/jquery.plupload.queue/css/jquery.plupload.queue.css" />
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="css/default.min.css" />
        <link rel="shortcut icon" href="favicon.ico">
        <link rel="apple-touch-icon" href="img/icon_128.png" />
        <meta property="og:image" content="img/icon_128.png"/>
    </head>
    <body>
        <div class="container">
            <a href="<?php echo SITE_URL ?>" ><h1><?php echo SITE_TITLE ?></h1></a>
            <p><?php echo SITE_DESCRIPTION; ?></p>
            <form action="#" method="post">
                <div id="uploader">
                    <p>Loading...</p>
                </div>
                <input type="button" value="Clear" id="btn_clear"/>
                <p id="ratio">
                    <span>Change size:</span>
                    <label><input type="radio" name="ratio" value="crop" id="crop-height" /> 
                        Resize and crop
                    </label>
                    <label><input type="radio" name="ratio" value="noratio" id="noratio" /> 
                        Resize and deform
                    </label>
                    <label><input type="radio" name="ratio" value="ratio-width" id="ratio-width"/> 
                        Define width, auto height
                    </label>
                    <label><input type="radio" name="ratio" value="ratio-height" id="ratio-height" /> 
                        Define height, auto width
                    </label><br />
                    <label><input type="radio" name="ratio" value="nochange" id="nochange" checked /> 
                        Don't change size
                    </label>
                </p>
                <p id="size">
                    <span>New size:</span>
                    <label id="label-width"><input type="number" name="width" id="width" min="1" max="<?php echo RESIZE_MAX_WIDTH; ?>" placeholder="0"/>px width</label>
                    <label id="label-height"><input type="number" name="height" id="height" min="1" max="<?php echo RESIZE_MAX_HEIGHT; ?>" placeholder="0"/>px height</label>
                </p>
                <p>
                    <span class="lbl">Image rotation:</span><br>
                    <label><input type="radio" name="rotation" value="0" checked/> 
                        Don't rotate 
                        <i class="fa fa-photo"></i>
                    </label>
                    <label><input type="radio" name="rotation" value="90" /> 
                        90° rotation 
                        <i class="fa fa-photo"></i>
                        <i class="fa fa-long-arrow-right"></i>
                        <i class="fa fa-photo fa-rotate-90"></i>
                    </label>
                    <label><input type="radio" name="rotation" value="180" /> 
                        180° rotation 
                        <i class="fa fa-photo"></i>
                        <i class="fa fa-long-arrow-right"></i>
                        <i class="fa fa-photo fa-rotate-180"></i>
                    </label>
                    <label><input type="radio" name="rotation" value="270" /> 
                        270° rotation 
                        <i class="fa fa-photo"></i>
                        <i class="fa fa-long-arrow-right"></i>
                        <i class="fa fa-photo fa-rotate-270"></i>
                    </label>
                </p>
                <p>
                    <span class="lbl">Image flip:</span><br>
                    <label><input type="radio" name="flip" value="0" checked/> 
                        Don't flip 
                        <i class="fa fa-photo"></i>
                    </label>
                    <label><input type="radio" name="flip" value="h" /> 
                        Horizontal flip 
                        <i class="fa fa-photo"></i>
                        <i class="fa fa-long-arrow-right"></i>
                        <i class="fa fa-photo fa-flip-vertical"></i>
                    </label>
                    <label><input type="radio" name="flip" value="v" /> 
                        Vertical flip 
                        <i class="fa fa-photo"></i>
                        <i class="fa fa-long-arrow-right"></i>
                        <i class="fa fa-photo fa-flip-horizontal"></i>
                    </label>
                </p>
                <p>
                    <span class="lbl">Change file format:</span>
                    <label>
                        <select name="format" id="format">
                            <option value="nochange">Don't change</option>
                            <option value="JPEG100">JPEG (high quality)</option>
                            <option value="JPEG85">JPEG (high quality)</option>
                            <option value="JPEG50">JPEG (low quality)</option>
                            <option value="PNG">PNG (best quality, heavy file)</option>
                        </select>
                    </label>
                </p>
                <input type="submit" value="OK" id="btn_submit"/>
            </form>
        </div>
        <div class="socials">
            <div class="addthis_toolbox addthis_default_style addthis_32x32_style"
                 addthis:url="<?php echo SITE_URL ?>"
                 addthis:title="<?php echo SITE_SOCIAL_NETWORKS_TITLE ?>"
                 addthis:description="<?php echo SITE_SOCIAL_NETWORKS_DESCRIPTION; ?>">
                <a class="addthis_button_facebook"></a>
                <a class="addthis_button_twitter"></a>
                <a class="addthis_button_google_plusone_share"></a>
                <a class="addthis_button_linkedin"></a>
                <a class="addthis_button_digg"></a>
                <a class="addthis_button_reddit"></a>
                <a class="addthis_button_email"></a>
                <a class="addthis_button_compact"></a>
                <a class="addthis_counter addthis_bubble_style"></a>
            </div>
        </div>
        <div class="footer">
            Powered by <a href="https://github.com/onlinecreation/punchi-me" target="_blank">Punchi Me</a>
            - Hosted and made by <a href="http://www.onlinecreation.pro" target="_blank" title="Création de site internet à Bordeaux">OnLineCreation SARL</a>
        </div>
        
        <script type="text/javascript">
            var max_file_size = '<?php echo MAX_QUEUE_SIZE_MB; ?>mb';
            var max_queue_size = <?php echo (MAX_QUEUE_SIZE_MB * 1024 * 1024); ?>;
            var tmp_dir = "<?php echo TMP_DIR_URL . DIRECTORY_SEPARATOR; ?>";
            var addthis_config = addthis_config || {};
            addthis_config.data_track_clickback = false;
            addthis_config.data_track_addressbar = false;
            <?php
                if (isset($_GET["error"])) {
                    echo 'alert("Error while converting files. Please try again with other files.");';
                }
            ?>
        </script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/plupload/2.1.7/plupload.full.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/plupload/2.1.7/jquery.plupload.queue/jquery.plupload.queue.min.js"></script>
        <script type="text/javascript" src="https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-519bd6235ebd3bf1"></script>
        <script type="text/javascript" src="js/main.min.js"></script>
    </body>
</html>