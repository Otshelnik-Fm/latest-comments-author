<?php

require_once 'settings.php';


// подключаем стили только в лк
function lca_get_style(){
    if(rcl_is_office()){
        rcl_enqueue_style('latestcomments',rcl_addon_url('style.css', __FILE__));
    }
}
add_action('rcl_enqueue_scripts','lca_get_style',10);



// считаем комментарии пользователя
function lca_count_user_comm($user_lk){
    if(rcl_is_office()){       // если в ЛК
        global $wpdb;
        $lca_count = $wpdb->get_var("SELECT COUNT(comment_ID) FROM ".$wpdb->prefix ."comments WHERE user_id = ".$user_lk." AND comment_approved = 1");

        return $lca_count;
    }
}


// вкладка в counters
function lca_add_tab_comments(){
    global $rcl_options, $user_LK;
    $view = (isset($rcl_options['lcp_vide'])) ? $rcl_options['lcp_vide'] : 1; // настраиваем публичность вкладки
    switch($view){
        case 1:
        case 2: $public = 1;
            break;
        case 3: $public = 0;
            break;
    }

    $count = 0;
    if(!is_admin()){
        $count = lca_count_user_comm($user_LK);
    }

    $tab_data =	array(
        'id'=>'latestcomments',
        'name'=>'Комментарии',
        'supports'=>array('ajax','cache'),
        'public'=>$public,
        'icon'=>'fa-comment-o',
        'output' =>'counters',
        'counter'=>$count,
        'content'=>array(
            array(
                'callback' => array(
                    'name'=>'lca_out'
                )
            )
        )
    );

    rcl_tab($tab_data);
}
add_action('init','lca_add_tab_comments');


// блок комментариев
function lca_out(){
    if(rcl_is_office()){ // если в ЛК
        global $rcl_options, $user_ID, $user_LK;
        $tab_open = $rcl_options['lcp_vide']; // получаем настройки, кому показывать содержимое вкладки
        if(!$tab_open) $tab_open = '1';
         // проверяем настройки доступа: 1 - Всем, 2 - Только авторизованным, 3 - Только хозяину лк. Все верно - стартуем
        if($tab_open == '1' || $tab_open == '2' && $user_ID || $tab_open == '3' && $user_ID == $user_LK) {
            return lca_comments();
        } else {
            return '<span class="lca_mess">Вкладка доступна авторизованным пользователям</span>';
        }
    }
}


// Вывод комментариев
function lca_comments(){

    global $wpdb, $rcl_options, $user_LK;

    $inpage = $rcl_options['lca_comm'];		// Передаем значение комментариев на страницу из админки
    if(!$inpage) $inpage = '10';

    $count_comments = lca_count_user_comm($user_LK);    // кол-во комментариев у юзера
    $rclnavi = new Rcl_PageNavi('l_comments',$count_comments,array('in_page'=>$inpage)); // передаем в класс навигации параметры
    $lca_start = $rclnavi->offset; // отступ для запроса

    // формируем таблицу с данными
    $comments_user = $wpdb->get_results("
            SELECT comment_ID,comment_post_ID,comment_date,comment_content
            FROM ".$wpdb->prefix ."comments AS comm
            WHERE comm.user_id = ".$user_LK." AND comm.comment_approved = 1
            ORDER BY comment_date DESC LIMIT ".$lca_start.",".$inpage."
        ");

    if($comments_user){ // есть комментарий у пользователя

        $out  = '<div class="lca_count" title="Всего комментариев"><i class="fa fa-comments-o"></i>' .$count_comments. '</div>';
        $out .= '<div class="lca_head">Последние комментарии:</div>';
        $out .= '<div class="lca_blk">';

        foreach($comments_user as $comment_one){
            $comm_nmbr = ($count_comments-$lca_start);
            $count_comments--;
            $commen_zagolov = get_the_title($comment_one->comment_post_ID);
            $out .= '<div class="lca_single">';
                $out .= '<div class="lca_num">'.$comm_nmbr.'</div>';
                $out .= '<div class="lca_title"><i class="fa fa-volume-up"></i>'
                            .'<a href="'.get_permalink( $comment_one->comment_post_ID ).'#comment-'.$comment_one->comment_ID.'" title="Перейти">'.$commen_zagolov.'</a>'
                         .'</div>';
                $out .= '<div class="lca_date">'.mysql2date('d.m.Yг. H:i:s', $comment_one->comment_date).'</div>';
                $out .= '<div class="lca_content">'.$comment_one->comment_content.'</div>';
                if (function_exists('rcl_get_html_post_rating')) {
                    $out .= rcl_get_html_post_rating($comment_one->comment_ID,'comment');   // выводим рейтинг и детализацию голосования
                }
            $out .= '</div>';
        }

        $out .= '</div>';
        $out = convert_smilies($out);
        $out_fin = nl2br($out);
        $out_fin .= $rclnavi->pagenavi();

        return $out_fin;
    } else {
        return '<span class="lca_mess">Комментариев пока нет</span>';
    }
}


// перевод hex в rgb и применяем стили
function lca_hex_to_rgb(){
    if(!rcl_is_office()) return false;

    global $rcl_options;
    if($rcl_options['lca_color'] == 1){                         // если разрешено запускаем
        $lca_hex = $rcl_options['primary-color'];               // достаем оттуда наш цвет
        list($r, $g, $b) = sscanf($lca_hex, "#%02x%02x%02x");   // разбиваем строку на нужный нам формат
        echo '<style>
#tab-latestcomments .rcl-navi{background:rgba('.$r.','.$g.','.$b.',0.04);box-shadow:0 0 2px rgba('.$r.','.$g.','.$b.',0.4);}
#tab-latestcomments .lca_single{background:rgba('.$r.','.$g.','.$b.',0.07);box-shadow:0 0 1px 1px rgba('.$r.','.$g.','.$b.',0.3);}
#tab-latestcomments .lca_num{background:rgba('.$r.','.$g.','.$b.',0.12);}
#tab-latestcomments .lca_head,#tab-latestcomments .lca_count{background:rgba('.$r.','.$g.','.$b.',0.17);}
</style>';
    }
}
add_action('wp_footer','lca_hex_to_rgb');


// в правое меню добавляю пункт
function lca_add_recallbar_r_menu(){

    if(!is_user_logged_in()) return false;
    global $user_ID;
    rcl_bar_add_menu_item('profile-link',
        array(
            'url'=>rcl_format_url(get_author_posts_url($user_ID),'latestcomments'),
            'icon'=>'fa-comment-o',
            'label'=>'Ваши комментарии'
        )
    );
}
add_action('rcl_bar_setup','lca_add_recallbar_r_menu',10);