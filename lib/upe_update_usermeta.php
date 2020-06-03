<?php
/**
 * This function overloaded, beacаse standart fanction of wordpress the update_usermeta($user_id, $meta_key, $meta_value)
 * uses php function stripslashes(string $st) which no get uses the RSA encryption
 * @param $user_id
 * @param $meta_key
 * @param $meta_value
 * @return bool
 */
function upe_update_usermeta($user_id, $meta_key, $meta_value)
{
    _deprecated_function(__FUNCTION__, '3.0.0', 'upe_update_user_meta()');
    global $wpdb;
    if (!is_numeric($user_id))
        return false;
    $meta_key = preg_replace('|[^a-z0-9_]|i', '', $meta_key);


    if (empty($meta_value)) {
        return delete_usermeta($user_id, $meta_key);
    }
    $cur = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->usermeta WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key));
    if ($cur)
        do_action('upe_update_usermeta', $cur->umeta_id, $user_id, $meta_key, $meta_value);

    if (!$cur)
        $wpdb->insert($wpdb->usermeta, compact('user_id', 'meta_key', 'meta_value'));
    elseif ($cur->meta_value != $meta_value)
        $wpdb->update($wpdb->usermeta, compact('meta_value'), compact('user_id', 'meta_key'));
    else
        return false;

    clean_user_cache($user_id);
    wp_cache_delete($user_id, 'user_meta');

    if (!$cur)
        do_action('added_usermeta', $wpdb->insert_id, $user_id, $meta_key, $meta_value);
    else
        do_action('updated_usermeta', $cur->umeta_id, $user_id, $meta_key, $meta_value);

    return true;
}