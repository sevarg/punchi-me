<?php

/*
 * Website settings
 */
// Main url of the website
define('SITE_URL', 'http://www.punchi.me');
// <title>
define('SITE_TITLE', 'Punchi.me');
// meta name="description"
define('SITE_DESCRIPTION', 'Easy image manipulation');
// Title of the website used by the social networks (AddThis)
define('SITE_SOCIAL_NETWORKS_TITLE', '');
// Description of the website used by the social networks (AddThis)
define('SITE_SOCIAL_NETWORKS_DESCRIPTION', '');
// Temporary folder
define('TMP_DIR', __DIR__.'/tmp');
// URL of the temporary folder
define('TMP_DIR_URL', './tmp');
// Max queue size in MB
define('MAX_QUEUE_SIZE_MB', '10');
// Max width
define('RESIZE_MAX_WIDTH', '3000');
// Max height
define('RESIZE_MAX_HEIGHT', '3000');
// Allowed file extensions 
$GLOBALS["EXT_ALLOWED"] = array("jpeg", "png", "gif", "jpg", "bmp");
