<?php
function upe_recently_registered_users()
{
    $number = 3;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $offset = ($paged - 1) * $number;
    $users = get_users();
    $total_users = count($users);
    $query = get_users("&offset=$offset&number=$number");
    $total_query = count($query);
    $total_pages = round($total_users / $number) + 1;
    $recentusers = '<ul id="users">';
    foreach ($query as $q) {

        $recentusers .= "<li class='user clearfix'><div class='user-avatar'>" .
            get_avatar($q->ID, 80) . "</div><div class='user-data'><h4 class='user-name'><a href='" .
            get_author_posts_url($q->ID) . "?user=". $q->ID ."'>" . get_the_author_meta('display_name', $q->ID) . "</a></h4></div></li>";


    }
    $recentusers .= '</ul>';
    if ($total_users > $total_query) {

        $recentusers .= '<div id="pagination" class="clearfix">';
        $recentusers .= '<span class="pages">Страницы:</span>';
        /* Получаем текущий номер страницы */
        $current_page = max(1, get_query_var('paged'));
        $recentusers .= paginate_links(array(
            'base' => get_pagenum_link(1) . "%_%",
            'format' => "/page/%#%/",
            'current' => $current_page,
            'total' => $total_pages,
            'prev_next' => false,
            'type' => 'list',
        ));
        $recentusers .= '</div>';
    }
    return $recentusers;
}

add_shortcode('upe_newusers', 'upe_recently_registered_users');