<?php

/**
 * you can add img urls here
 * example:

$imgUrls = [
'link to image 1',
'link to image 2',
]

 * the img urls are inserted before the local images (from the /images folder)
 */
$imgUrls = [
]
?>

<html>
<head>
    <link rel="stylesheet" href="css/fixes.css">
    <style>
        body {
            cursor: none;
            /* this is the visible color 'behind' the images*/
            background-color: rgb(83, 87, 96);
            overflow: hidden;
        }

    </style>
</head>
<body style="margin: 0;padding: 0">


<div id="maximage" style="width: 100%;height: 100%">
    <?php

    //display img urls
    foreach ($imgUrls as $entry) { ?>
        <div class="mc-image" title="" style="background-image: url('<?php echo $entry ?>'); height: 100%; width: 100%;"
             data-href=""></div>
    <?php }

    //local /images folder
    if ($handle = opendir('./images')) {
        while (false !== ($entry = readdir($handle))) {
            if (strpos($entry, '.') === 0) { //we ignore all files starting with . e.g. . (current dir) or .. or hidden files
                //ignore
            } else {
                ?>
                <div class="mc-image" title=""
                     style="background-image: url('images/<?php echo $entry ?>'); height: 100%; width: 100%;"
                     data-href=""></div>

                <?php
            }
        }
    }
    ?>
</div>


<script src="js/jquery-3.3.1.min.js"></script>
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>-->
<script src="js/jquery.cycle.all.js"></script>
<script>
    $(function () {
        setTimeout(function () {
            $('#maximage').cycle({
                fx: 'uncover', //or all
                speed: 2000,
                timeout: 10000, //10000
                random: 0 //set to 1 for random order
            });
            //give kiosk mode some time to become full screen
        }, 5000)
    });
</script>
</body>
</html>

