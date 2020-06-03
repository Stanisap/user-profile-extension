<?php
/*
 * Plugin Name: User Profile Extension
 * Description: This plugin extends the meta fields of a user profile
 * Plugin URI:  https://github.com/Stanisap
 * Author URI:  Сhttps://github.com/Stanisap
 * Author:      Stanisap
 * Version:     1.0
 *
 */
include( WP_PLUGIN_DIR.'/user-profile-extension/my-debag.php' );

function upe_special_plugin_styles() {
    wp_register_style('special', plugins_url('assets/style.css', __FILE__));
    wp_enqueue_style('special');
}
add_action( 'wp_enqueue_scripts', 'upe_special_plugin_styles' );



// Create the query var so that WP catches your custom /user/username url
// I assume you define these somewhere, this is just to make the example work
$upe_author_levels = array( 'user', 'admin' );

add_action( 'init', 'upe_rewrite_init' );
function upe_rewrite_init()
{
    global $wp_rewrite;
    $author_levels = $GLOBALS['upe_author_levels'];

    // Define the tag and use it in the rewrite rule
    add_rewrite_tag( '%author_level%', '(' . implode( '|', $author_levels ) . ')' );
    $wp_rewrite->author_base = '%author_level%';
}

add_filter( 'author_rewrite_rules', 'upe_author_rewrite_rules' );
function upe_author_rewrite_rules( $author_rewrite_rules )
{
    foreach ( $author_rewrite_rules as $pattern => $substitution ) {
        if ( FALSE === strpos( $substitution, 'author_name' ) ) {
            unset( $author_rewrite_rules[$pattern] );
        }
    }
    return $author_rewrite_rules;
}

add_filter( 'author_link', 'upe_author_link', 10, 2 );
function upe_author_link( $link, $author_id )
{
    if ( 1 == $author_id ) {
        $author_level = 'admin';
    } else {
        $author_level = 'user';
    }
    $link = str_replace( '%author_level%', $author_level, $link );
    return $link;
}

include( WP_PLUGIN_DIR.'/user-profile-extension/template-parts/form-table.php' );


register_activation_hook( __FILE__, 'upe_install' );
function upe_install(){
    add_action( 'show_user_profile', 'show_profile_fields' );
    add_action( 'edit_user_profile', 'show_profile_fields' );

    flush_rewrite_rules();
}

function upe_save_profile_fields( $user_id ) {
    include( WP_PLUGIN_DIR.'/user-profile-extension/lib/upe_update_usermeta.php' );
    global $rsa;
    update_user_meta( $user_id, 'address', $rsa->encode($_POST['address']) );
    update_user_meta( $user_id, 'phone', $rsa->encode($_POST['phone']) );
    update_user_meta( $user_id, 'gender', $rsa->encode($_POST['gender']) );
    update_user_meta( $user_id, 'fstatus', $rsa->encode($_POST['fstatus']) );

}

add_action( 'personal_options_update', 'upe_save_profile_fields' );
add_action( 'edit_user_profile_update', 'upe_save_profile_fields' );

include( WP_PLUGIN_DIR.'/user-profile-extension/template-parts/show-users.php' );

add_filter( 'query_vars', 'upe_wpleet_rewrite_add_var' );
function upe_wpleet_rewrite_add_var( $vars )
{
    $vars[] = 'user';
    return $vars;
}

add_action('init', 'upe_add_rewrite_rule_my');
function upe_add_rewrite_rule_my() {
    add_rewrite_tag( '%user%', '([^&]+)' );
    add_rewrite_rule(
        '^user/([^/]*)/?',
        'index.php?user=$matches[1]',
        'top'
    );
}

add_action( 'template_redirect', 'upe_wpleet_rewrite_catch' );

function upe_wpleet_rewrite_catch()
{
    global $wp_query;

    //my_dg($wp_query);

    if ( array_key_exists( 'user', $wp_query->query_vars ) ) {
        include (WP_PLUGIN_DIR . '/user-profile-extension/profile.php');
        exit;
    }
}
register_deactivation_hook( __FILE__, 'myplugin_deactivation' );
function myplugin_deactivation() {
    // Тип записи не регистрируется, а значит он автоматически удаляется - его не нужно удалять как-то еще.

    // Сбрасываем настройки ЧПУ, чтобы они пересоздались с новыми данными
    flush_rewrite_rules();
}
