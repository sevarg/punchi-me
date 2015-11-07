/**
 * Punchi.me
 * @author OnlineCreation - Raphaël
 * @licence GNU GPL v3.0
 * @link http://www.onlinecreation.pro Authors' website
 * @link http://www.punchi.me Demo
 */

if (typeof max_file_size === 'undefined') {
    var max_file_size = '1mb';
}
if (typeof max_queue_size === 'undefined') {
    var max_queue_size = 10000000;
}
if (typeof tmp_dir === 'undefined') {
    var tmp_dir = "tmp/";
}

$(function () {

    // Client-size upload manager
    $("#uploader").pluploadQueue({
        runtimes: 'html5,flash,silverlight,html4',
        url: 'upload.php',
        max_file_size: max_file_size,
        chunk_size: '1mb',
        unique_names: true,
        multi_selection: true,
        filters: [
            {title: "Image files", extensions: "jpg,gif,png,jpeg,bmp"}
        ],
        flash_swf_url: 'js/plupload.flash.swf',
        silverlight_xap_url: 'js/plupload.silverlight.xap'
    });

    // Client-side size control
    $("#uploader").pluploadQueue().bind('FilesAdded', addFile);

    // On submit process
    $('form').submit(doSubmit);

    // Hide useless size field
    $("input[name=ratio]").click(hideShowFields);

    $("#btn_clear").click(reload);

    hideShowFields();
});

var reload = function () {
    // TODO : cleaner way to clear the form
    document.location.href = "index.php";
};

var doSubmit = function (e) {
    var uploader = $('#uploader').pluploadQueue();

    // Client-side fields control
    if ($("input[name=ratio]:checked").length < 1) {
        $("#noratio").click();
    }
    if ($("#label-height:visible").length > 0 && !(parseInt($("#height").val()) > 0)) {
        alert("Please enter a valid height.");
        return false;
    } else {
        $("#height").val(parseInt($("#height").val()));
    }

    if ($("#label-width:visible").length > 0 && !(parseInt($("#width").val()) > 0)) {
        alert("Please enter a valid width.");
        return false;
    } else {
        $("#width").val(parseInt($("#width").val()));
    }

    // If some files have not been uploaded, let's do it
    if (uploader.files.length > 0) {
        // When all files are uploaded submit form
        uploader.bind('StateChanged', function () {
            if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                $("#btn_submit").val("");
                $("#btn_submit").css("background", "url('img/loader.gif') no-repeat center center #fff");

                // Let's ask action.php to do all the work'
                $.post("action.php", $("form").serializeArray(), function (data, textStatus, jqXHR) {
                    // If everything is OK, let's go find the .zip file'
                    if (data !== "KO") {
                        $("#btn_submit").val("OK");
                        $("#btn_submit").attr("disabled", false);
                        $("#btn_submit").css("color", "#000");
                        $("#btn_submit").css("cursor", "pointer");
                        $("#btn_submit").css("background", "#fff");
                        document.location.href = tmp_dir + data;
                    } else {
                        alert("I think you ask too much !");
                    }
                });
                return false;
            }
        });

        $("#btn_submit").val("Upload in progress...");
        $("#btn_submit").attr("disabled", true);
        $("#btn_submit").css("color", "#999");
        $("#btn_submit").css("cursor", "default");
        uploader.start();
    } else {
        alert('You must add at least one file.');
    }

    return false;
};

var addFile = function (uploader, files) {
    s = 0;
    for (var i in files) {
        s += files[i].size;
    }
    s += uploader.total.size;

    if (s > max_queue_size) {
        alert("Please do not upload more than " + max_queue_size + "MB.");
        while (s > max_queue_size) {
            removedfile = files.pop();
            s -= removedfile.size;
            uploader.removeFile(removedfile);
        }
    }

};

var hideShowFields = function () {

    if ($("#nochange:checked").length > 0) {
        $("#label-width").hide();
        $("#label-height").hide();
        $("#size").hide();
    } else {
        $("#label-height").show();
        $("#label-width").show();
        $("#size").show();

        if ($("#ratio-width:checked").length > 0) {
            $("#label-height").hide();
        }

        if ($("#ratio-height:checked").length > 0) {
            $("#label-width").hide();
        }
    }
};