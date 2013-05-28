<?php
/**
 * Main UI
 * @author Raphaël http://www.onlinecreation.pro
 */
session_start();
require "config.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo SITE_TITLE; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?php echo SITE_DESCRIPTION; ?>">
        <meta name="author" content="http://www.onlinecreation.pro">
        <link rel="stylesheet" type="text/css" href="js/jquery.plupload.queue/css/jquery.plupload.queue.css" />
        <link rel="stylesheet" type="text/css" href="/css/default.css" />
        <link rel="shortcut icon" href="favicon.ico">
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
                <p id="size">
                    <span>New size:</span>
                    <label id="label-width"><input type="number" name="width" id="width" min="1" max="<?php echo RESIZE_MAX_WIDTH; ?>" placeholder="0"/>px width</label>
                    <label id="label-height"><input type="number" name="height" id="height" min="1" max="<?php echo RESIZE_MAX_HEIGHT; ?>" placeholder="0"/>px height</label>
                </p>
                <p id="ratio">
                    <span>Ratio aspect:</span>
                    <label><input type="radio" name="ratio" value="ratio-width" id="ratio-width"/> Keep ratio aspect (width)</label>
                    <label><input type="radio" name="ratio" value="ratio-height" id="ratio-height" /> Keep ratio aspect (height)</label><br />
                    <label><input type="radio" name="ratio" value="crop" id="crop-height" checked /> Resize and crop</label>
                    <label><input type="radio" name="ratio" value="noratio" id="noratio" /> Ignore ratio aspect</label>
                    <label><input type="radio" name="ratio" value="nochange" id="nochange" /> Don't change size</label>
                </p>
                <p>
                    <span class="lbl">Change file format:</span>
                    <label>
                        <select name="format" id="format">
                            <option value="nochange">Don't change</option>
                            <option value="JPEG100">JPEG (best quality)</option>
                            <option value="JPEG85">JPEG (high quality)</option>
                            <option value="JPEG50">JPEG (low quality)</option>
                            <option value="PNG">PNG</option>
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
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
        <script type="text/javascript" src="js/plupload.full.js"></script>
        <script type="text/javascript" src="js/jquery.plupload.queue/jquery.plupload.queue.js"></script>

        <script type="text/javascript">
            
            $(function() {
                
                // Client-size upload manager
                $("#uploader").pluploadQueue({
                    runtimes : 'html5,flash,silverlight,html4',
                    url : 'upload.php',
                    max_file_size : '<?php echo MAX_QUEUE_SIZE_MB; ?>mb',
                    chunk_size : '1mb',
                    unique_names : true,
                    multi_selection : true,
                    filters : [
                        {title : "Image files", extensions : "jpg,gif,png,jpeg,bmp"}
                    ],
                    flash_swf_url : 'js/plupload.flash.swf',
                    silverlight_xap_url : 'js/plupload.silverlight.xap'
                });
                
                // Client-side size control
                $("#uploader").pluploadQueue().bind('FilesAdded', function(uploader,files) {
                    s = 0;
                    for(var i in files){
                        s += files[i].size;
                    }
                    s += uploader.total.size;
                    
                    if(s > <?php echo (MAX_QUEUE_SIZE_MB * 1024 * 1024); ?> ){
                        alert("Please do not upload more than 10MB.")
                        while(s > <?php echo (MAX_QUEUE_SIZE_MB * 1024 * 1024); ?> ){
                            removedfile = files.pop();
                            s -= removedfile.size;
                            uploader.removeFile(removedfile);
                        }
                    }
                    
                });

                // On submit process
                $('form').submit(function(e) {
                    var uploader = $('#uploader').pluploadQueue();
                    
                    // Client-side fields control
                    if($("input[name=ratio]:checked").length < 1){
                        $("#noratio").click();
                    }
                    if($("#label-height:visible").length>0 && !(parseInt($("#height").val()) > 0) ){
                        alert("Please enter a valid height.")
                        return false;
                    }else{
                        $("#height").val(parseInt($("#height").val()));
                    }
                    
                    if($("#label-width:visible").length>0 && !(parseInt($("#width").val()) > 0) ){
                        alert("Please enter a valid width.")
                        return false;
                    }else{
                        $("#width").val(parseInt($("#width").val()));
                    }
                    
                    // If some files have not been uploaded, let's do it
                    if (uploader.files.length > 0) {
                        // When all files are uploaded submit form
                        uploader.bind('StateChanged', function() {
                            if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                                $("#btn_submit").val("");
                                $("#btn_submit").css("background","url('img/loader.gif') no-repeat center center #fff");
                                
                                // Let's ask action.php to do all the work'
                                $.post("action.php",$("form").serializeArray(),function(data, textStatus, jqXHR){
                                    // If everything is OK, let's go find the .zip file'
                                    if(data != "KO"){
                                        $("#btn_submit").val("OK");
                                        $("#btn_submit").attr("disabled",false);
                                        $("#btn_submit").css("color","#000");
                                        $("#btn_submit").css("cursor","pointer");
                                        $("#btn_submit").css("background","#fff");
                                        document.location.href = "<?php echo TMP_DIR_URL . DIRECTORY_SEPARATOR; ?>" + data;
                                    }else{
                                        alert("I think you ask too much !");
                                    }
                                });
                                return false;
                            }
                        });
                
                        $("#btn_submit").val("Upload in progress...");
                        $("#btn_submit").attr("disabled",true);
                        $("#btn_submit").css("color","#999");
                        $("#btn_submit").css("cursor","default");
                        uploader.start();
                    } else {
                        alert('You must add at least one file.');
                    }

                    return false;
                });
                
                // Hide useless size field
                $("input[name=ratio]").click(function(){
                 
                    if($("#nochange:checked").length>0){
                        $("#label-width").hide();
                        $("#label-height").hide();
                        $("#size").hide();
                    }else{
                        $("#label-height").show();
                        $("#label-width").show();
                        $("#size").show();
                    
                        if($("#ratio-width:checked").length>0){
                            $("#label-height").hide();
                        }

                        if($("#ratio-height:checked").length>0){
                            $("#label-width").hide();
                        }
                    }
                });
                
                // TODO : cleaner way to clear the form
                $("#btn_clear").click(function(){
                    document.location.href="index.php";
                });
                
            });
        </script>
        <script type="text/javascript">
            var addthis_config = addthis_config||{};
            addthis_config.data_track_clickback = false;
            addthis_config.data_track_addressbar = false;
        </script>
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-519bd6235ebd3bf1"></script>
        <?php
        if (isset($_GET["error"])) {
            echo '<script type="text/javascript">alert("Error while converting files. Please try again with other files.");</script>';
        }
        ?>
    </body>
</html>