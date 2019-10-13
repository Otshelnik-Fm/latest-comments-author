<?php

/*

  ╔═╗╔╦╗╔═╗╔╦╗
  ║ ║ ║ ╠╣ ║║║ https://otshelnik-fm.ru
  ╚═╝ ╩ ╚  ╩ ╩

 */


if ( ! defined( 'ABSPATH' ) )
    exit;


require_once 'admin/settings.php';

/**/
// подключаем стили только в лк
add_action( 'rcl_enqueue_scripts', 'lca_get_style', 10 );
function lca_get_style() {
    if ( ! rcl_is_office() )
        return;

    rcl_enqueue_style( 'lca-comments', rcl_addon_url( 'style.css', __FILE__ ) );
}

// считаем комментарии пользователя
function lca_count_user_comm( $user_lk ) {
    if ( ! rcl_is_office() )
        return;

    global $wpdb, $lca_count;

    if ( ! $lca_count ) {
        $lca_count = $wpdb->get_var( "SELECT COUNT(comment_ID) FROM " . $wpdb->prefix . "comments WHERE user_id = " . $user_lk . " AND comment_approved = 1" );
    }
    return $lca_count;
}

// вкладка в counters
add_action( 'init', 'lca_add_tab_comments' );
function lca_add_tab_comments() {
    $view = rcl_get_option( 'lcp_vide', 1 ); // настраиваем публичность вкладки
    switch ( $view ) {
        case 1:
        case 2: $public = 1;
            break;
        case 3: $public = 0;
            break;
    }

    $count = 0;
    if ( ! is_admin() ) {
        global $user_LK;
        $count = lca_count_user_comm( $user_LK );
    }

    $tab_data = array(
        'id'       => 'latestcomments',
        'name'     => 'Комментарии',
        'supports' => array( 'ajax' ),
        'public'   => $public,
        'icon'     => 'fa-comment-o',
        'output'   => rcl_get_option( 'lcp_area', 'counters' ),
        'counter'  => $count,
        'content'  => array(
            array(
                'callback' => array(
                    'name' => 'lca_out'
                )
            )
        )
    );

    rcl_tab( $tab_data );
}

// блок комментариев
function lca_out() {
    if ( rcl_is_office() ) { // если в ЛК
        global $user_ID, $user_LK;

        $tab_open = rcl_get_option( 'lcp_vide', 1 ); // получаем настройки, кому показывать содержимое вкладки
        // проверяем настройки доступа: 1 - Всем, 2 - Только авторизованным, 3 - Только хозяину лк. Все верно - стартуем
        if ( $tab_open == '1' || $tab_open == '2' && $user_ID || $tab_open == '3' && $user_ID == $user_LK ) {
            return lca_comments( $user_LK );
        } else {
            return lca_guest_mess();
        }
    }
}

// гостям инфо
function lca_guest_mess() {
    global $rcl_options;

    $out = '<span>Вкладка доступна авторизованным пользователям</span>';

    // если доп включен и форма входа всплывающая:
    if ( rcl_exist_addon( 'you-need-to-login' ) && $rcl_options['login_form_recall'] == 0 ) {
        $out .= '<br><a class="rcl-login ynl_login" href="#"><span>Войдите</span></a> на сайт';
    }

    return '<div class="lca_mess">' . $out . '</div>';
}

// Вывод комментариев
function lca_comments( $user_lk ) {
    global $wpdb;

    $inpage = rcl_get_option( 'lca_comm', 10 );  // Передаем значение комментариев на страницу из админки

    $count_comments = lca_count_user_comm( $user_lk );    // кол-во комментариев у юзера
    $rclnavi        = new Rcl_PageNavi( 'l_comments', $count_comments, array( 'in_page' => $inpage ) ); // передаем в класс навигации параметры
    $lca_start      = $rclnavi->offset; // отступ для запроса
    // формируем таблицу с данными
    $comments_user  = $wpdb->get_results( "
            SELECT comment_ID,comment_post_ID,comment_date,comment_content
            FROM " . $wpdb->prefix . "comments AS comm
            WHERE comm.user_id = " . $user_lk . " AND comm.comment_approved = 1
            ORDER BY comment_date DESC LIMIT " . $lca_start . "," . $inpage . "
        " );

    if ( $comments_user ) { // есть комментарий у пользователя
        $out = '<div class="lca_header">';
        $out .= '<div class="lca_head_text lca__background-color">Последние комментарии:</div>';
        $out .= '<div class="lca_count lca__background-color" title="Всего комментариев"><i class="rcli fa-comments-o"></i><span>' . $count_comments . '</span></div>';
        $out .= '</div>';
        $out .= '<div class="lca_blk">';

        foreach ( $comments_user as $comment_one ) {
            $comm_nmbr      = ($count_comments - $lca_start);
            $count_comments --;
            $commen_zagolov = get_the_title( $comment_one->comment_post_ID );

            $out .= '<div class="lca_single">';
            $out .= '<div class="lca-single__head">';
            $out .= '<div class="lca-single__left">';
            $out .= '<div class="lca_num lca__background-color">' . $comm_nmbr . '</div>';
            $out .= '<div class="lca_title"><i class="rcli fa-volume-up"></i>'
                . '<a href="' . get_permalink( $comment_one->comment_post_ID ) . '#comment-' . $comment_one->comment_ID . '" title="Перейти">' . $commen_zagolov . '</a>'
                . '</div>';
            $out .= '</div>';
            $out .= '<div class="lca_date">' . mysql2date( 'd.m.Yг. H:i:s', $comment_one->comment_date ) . '</div>';
            $out .= '</div>';
            $out .= '<div class="lca_content">' . $comment_one->comment_content . '</div>';
            if ( function_exists( 'rcl_get_html_post_rating' ) ) {
                $out .= rcl_get_html_post_rating( $comment_one->comment_ID, 'comment' );   // выводим рейтинг и детализацию голосования
            }
            $out .= '</div>';
        }

        $out .= '</div>';

        $out_sm  = convert_smilies( $out );
        $out_fin = nl2br( $out_sm );
        $out_fin .= $rclnavi->pagenavi();

        return $out_fin;
    } else {
        return '<div class="lca_mess">Комментариев пока нет</div>';
    }
}

// перевод hex в rgb и применяем стили
add_action( 'wp_footer', 'lca_hex_to_rgb' );
function lca_hex_to_rgb() {
    if ( ! rcl_is_office() )
        return false;

    if ( rcl_get_option( 'lca_color' ) != 1 )
        return;              // если разрешено запускаем

    $lca_hex = rcl_get_option( 'primary-color' );                // достаем оттуда наш цвет
    list($r, $g, $b) = sscanf( $lca_hex, "#%02x%02x%02x" );      // разбиваем строку на нужный нам формат

    $style     = '
    #tab-latestcomments .rcl-pager{box-shadow:0 0 2px rgba(' . $r . ',' . $g . ',' . $b . ',0.5);}
    #tab-latestcomments .lca_single{background-color:rgba(' . $r . ',' . $g . ',' . $b . ',0.06);border-color:rgba(' . $r . ',' . $g . ',' . $b . ',0.16);}
    #tab-latestcomments .lca__background-color{background-color:rgba(' . $r . ',' . $g . ',' . $b . ',0.16);}
';
    $style_min = lca_inline_packer( $style );

    echo "\r\n<style>" . $style_min . "</style>\r\n";
}

// стили для админки настроек
add_action( 'admin_footer', 'lca_admin_styles' );
function lca_admin_styles() {
    $chr_page = get_current_screen();

    if ( $chr_page->base != 'wp-recall_page_rcl-options' )
        return;

    $style = '
    .lca_info {
        background-color: #dff5d4;
        border: 1px solid #c1eab7;
        margin: 5px 0 15px;
        padding: 6px 12px 8px;
        font-size: 14px;
    }
';

    $style_min = lca_inline_packer( $style );

    echo "\r\n<style>" . $style_min . "</style>\r\n";
}

add_action( 'admin_footer', 'lca_add_settings' );
function lca_add_settings() {
    $chr_page = get_current_screen();

    if ( $chr_page->base != 'wp-recall_page_rcl-options' )
        return;
    if ( isset( $_COOKIE['otfmi_1'] ) && isset( $_COOKIE['otfmi_2'] ) && isset( $_COOKIE['otfmi_3'] ) )
        return;

    require_once 'admin/for-settings.php';
}

function lca_inline_packer( $src ) {
    $src_cleared   = preg_replace( '/ {2,}/', '', str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $src ) );
    $src_non_space = str_replace( ': ', ':', $src_cleared );
    $src_sanity    = str_replace( ' {', '{', $src_non_space );

    return $src_sanity;
}

// в правое меню добавляю пункт
add_action( 'rcl_bar_setup', 'lca_add_recallbar_r_menu', 10 );
function lca_add_recallbar_r_menu() {
    if ( ! is_user_logged_in() )
        return;

    global $user_ID;
    rcl_bar_add_menu_item( 'profile-link', array(
        'url'   => rcl_format_url( get_author_posts_url( $user_ID ), 'latestcomments' ),
        'icon'  => 'fa-comment-o',
        'label' => 'Комментарии'
        )
    );
}
