<?php
/*-------------------------------------------------------------------------*/
/*                        REGISTER CUSTOM NAVIGATION WALKER                */
/*-------------------------------------------------------------------------*/

if ( ! file_exists( get_template_directory() . '/inc/class-bootstrap-5-navwalker.php' ) ) {
    // File does not exist... return an error.
    return new WP_Error( 'class-bootstrap-5-navwalker-missing', __( 'It appears the class-bootstrap-5-navwalker.php file may be missing.', 'wp-bootstrap-navwalker' ) );
} else {
    // File exists... require it.
    require_once get_template_directory() . '/inc/class-bootstrap-5-navwalker.php';
}

/*-------------------------------------------------------------------------*/
/*                        ENQUEUE ALL THE THINGS                           */
/*-------------------------------------------------------------------------*/

function wp_custom_styles(){
    wp_register_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', false ); 
    wp_enqueue_style('bootstrap' );
    wp_register_style('custom', get_template_directory_uri().'/assets/css/custom.css', array(), '1.0.0', 'all');
    wp_enqueue_style('custom');
}
add_action('wp_enqueue_scripts', 'wp_custom_styles');

function wp_custom_scripts(){
    wp_register_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', array(), '1.0.0', true);
    wp_enqueue_script('bootstrap-js');
    wp_register_script('custom-js', get_template_directory_uri(). '/assets/js/custom.js', array(), '1.0.0', true);
    wp_enqueue_script('custom-js');
}
add_action('wp_enqueue_scripts', 'wp_custom_scripts');

//enqueue google maps
function add_google_maps_api() {
    wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDrgGXICRk_yPMowMpFJHWZLVlhXb7GCnQ', array(), null, true);
  }
  add_action('wp_enqueue_scripts', 'add_google_maps_api');
/*-------------------------------------------------------------------------*/
/*                              REGISTER MENUS                             */
/*-------------------------------------------------------------------------*/

function wp_custom_menus(){
    add_theme_support('menus');

    register_nav_menus(array(
        'primary' => 'Main Menu',
        'secondary' => 'Footer Menu'
    ));
}
add_action('init', 'wp_custom_menus');


/*-------------------------------------------------------------------------*/
/*                        ADD THEME SUPPORT                                */
/*-------------------------------------------------------------------------*/
function custom_theme_setup() {
    add_theme_support( 'html5', array( 'comment-list' ) );
	
	add_theme_support('post-thumbnails');

	add_theme_support( 'title-tag' );

	add_theme_support('custom-header');

	add_theme_support('custom-background');

    add_theme_support( 'post-formats', array( 'aside', 'gallery', 'quote', 'image', 'video' ) );
}
add_action( 'after_setup_theme', 'custom_theme_setup' );

/*-------------------------------------------------------------------------*/
/*                        LIMIT LOGIN ATTEMPTS                             */
/*-------------------------------------------------------------------------*/
function check_attempted_login( $user, $username, $password ) {
    if ( get_transient( 'attempted_login' ) ) {
        $datas = get_transient( 'attempted_login' );

        if ( $datas['tried'] >= 3 ) {
            $until = get_option( '_transient_timeout_' . 'attempted_login' );
            $time = time_to_go( $until );

            return new WP_Error( 'too_many_tried',  sprintf( __( '<strong>ERROR</strong>: You have reached authentication limit, you will be able to try again in %1$s.' ) , $time ) );
        }
    }

    return $user;
}

add_filter( 'authenticate', 'check_attempted_login', 30, 3 ); 

function login_failed( $username ) {
    if ( get_transient( 'attempted_login' ) ) {
        $datas = get_transient( 'attempted_login' );
        $datas['tried']++;

        if ( $datas['tried'] <= 3 )
            set_transient( 'attempted_login', $datas , 300 );
    } else {
        $datas = array(
            'tried'     => 1
        );
        set_transient( 'attempted_login', $datas , 300 );
    }
}

add_action( 'wp_login_failed', 'login_failed', 10, 1 ); 

function time_to_go($timestamp){

    // converting the mysql timestamp to php time
    $periods = array(
        "second",
        "minute",
        "hour",
        "day",
        "week",
        "month",
        "year"
    );
    $lengths = array(
        "60",
        "60",
        "24",
        "7",
        "4.35",
        "12"
    );
    $current_timestamp = time();
    $difference = abs($current_timestamp - $timestamp);
    for ($i = 0; $difference >= $lengths[$i] && $i < count($lengths) - 1; $i ++) {
        $difference /= $lengths[$i];
    }
    $difference = round($difference);
    if (isset($difference)) {
        if ($difference != 1)
            $periods[$i] .= "s";
            $output = "$difference $periods[$i]";
            return $output;
    }
}