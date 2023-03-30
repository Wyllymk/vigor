<!DOCTYPE html>
<html <?php language_attributes();?>>
    <head>
        <!-- Required Meta Tags -->
        <meta charset="<?php echo bloginfo('charset');?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Wilson">
        <!-- Favicon and Touch Icons -->
        <link rel="icon" type="image/png" sizes="512x512" href=" <?php echo esc_url(get_template_directory_uri() .'/assets/img/icons/favicon-32x32.png')?>">
        <title><?php echo bloginfo('title');?></title>
        <?php wp_head();?>
    </head>
    <body <?php body_class();?> >
    
        <nav class="navbar navbar-expand-md navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="<?php echo esc_url(get_template_directory_uri().'/assets/img/logos/White.png')?>" alt="Vigor" width="30" height="24">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="main-menu">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container' => false,
                        'menu_class' => '',
                        'fallback_cb' => '__return_false',
                        'items_wrap' => '<ul id="%1$s" class="navbar-nav me-auto mb-2 mb-md-0 %2$s">%3$s</ul>',
                        'depth' => 2,
                        'walker' => new bootstrap_5_wp_nav_menu_walker()
                    ));
                    ?>
                </div>
                
            </div>
        </nav>

    
