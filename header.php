<!DOCTYPE html>
<html <?php language_attributes(); ?> class="pc">
<head>
    <meta name="viewport" content="initial-scale=1.0, width=device-width">
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <link rel="profile" href="http://gmgp.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php wp_head(); ?>
    <script>
        var url ='<?= get_template_directory_uri() ?>';
    </script>

    <link rel="stylesheet" type="text/css" href="<?= get_template_directory_uri() ?>/assets/css/app.css?v=1" />

</head>
<body>
    <?php include_once THEME_URL.'/templates/header.php' ?>

