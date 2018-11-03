
<?php
#$fxs = 'blindX,blindY,blindZ,cover,curtainX,curtainY,fade,fadeZoom,growX,growY,scrollUp,scrollDown,scrollLeft,scrollRight,scrollHorz,scrollVert,shuffle,slideX,slideY,toss,turnUp,turnDown,turnLeft,turnRight,uncover,wipe,zoom';
$fxs = 'blindX,blindY,blindZ,cover,curtainX,curtainY,fade,fadeZoom,growX,growY,scrollUp,scrollDown,scrollLeft,scrollRight,scrollHorz,scrollVert,shuffle,slideX,slideY,toss,turnUp,turnDown,turnLeft,turnRight,uncover,wipe,zoom';
$fxsArray = explode(',', $fxs);
$exampleWidth = '400px';
$exampleHeight = '300px';
?>

<html>
<head>
    <link rel="stylesheet" href="css/fixes.css">
    <style>
        body {
            /*cursor: none;*/
            background-color: rgb(83, 87, 96);
        }

        .effect-title {
            color: whitesmoke;
            margin-top: 0;
        }
        .padded {
            margin: 1em;
            overflow: hidden;
        }

    </style>
</head>
<body style="margin: 0;padding: 0">

<div style="display: flex;flex-wrap: wrap">
<?php
for ($i = 0; $i < count($fxsArray); $i++) { ?>
    <div class="padded" style="width:<?php echo $exampleWidth ?>;height: <?php echo $exampleHeight ?>">
        <p class="effect-title">Transitions: <?php echo $fxsArray[$i] ?></p>
        <div id="maximage-<?php echo $i ?>" style="width:<?php echo $exampleWidth ?>;height: <?php echo $exampleHeight ?>">
            <img src="example/pier-569314_1920.jpg" style="width:<?php echo $exampleWidth ?>;height: <?php echo $exampleHeight ?>">
            <img src="example/winter-2080070_1920.jpg" style="width:<?php echo $exampleWidth ?>;height: <?php echo $exampleHeight ?>">
        </div>
    </div>
<?php } ?>
</div>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/jquery.cycle.all.js"></script>

<?php
for ($i = 0; $i < count($fxsArray); $i++) { ?>

    <script>
        $('#maximage-<?php echo $i ?>').cycle({
            fx:      '<?php echo $fxsArray[$i] ?>',
            speed:    1000,
            timeout:  2000,
        });
    </script>

<?php } ?>

</body>
</html>
