<?php

add_filter( 'admin_options_wprecall', 'lca_admin_settings_addon' );
function lca_admin_settings_addon( $content ) {
    // кастомный блок саморекламы
    $my_adv = '';
    if ( ! rcl_exist_addon( 'subscription-two' ) ) {
        $my_adv = '<div class="lca_info">'
            . '<i class="rcli fa-info" style="color:#ffae19;font-size:20px;vertical-align:text-bottom;margin:0 5px;" aria-hidden="true"></i>'
            . ' <strong>Пользователи могут подписываться на комментарии</strong> - я написал дополнение: "<a href="https://codeseller.ru/products/subscription-two/" title="Перейти к описанию" target="_blank">Subscription Two</a>" - Залогиненные пользователи могут подписываться на комментарии к записям и форумам.<br/>'
            . 'Предлагаю вам ознакомиться с его функционалом.<br/>'
            . 'Подписки - отличный способ вернуть пользователя на ваш сайт.'
            . '</div>';
    }
    if ( ! rcl_exist_addon( 'smart-rating-for-comments' ) ) {
        $my_adv .= '<div class="lca_info">'
            . '<i class="rcli fa-info" style="color:#ffae19;font-size:20px;vertical-align:text-bottom;margin:0 5px;" aria-hidden="true"></i>'
            . ' <strong>Хотите мотивировать пользователей комментировать чаще?</strong> Дополнение: "<a href="https://codeseller.ru/products/smart-rating-for-comments/" title="Перейти к описанию" target="_blank">Smart Rating For Comments</a>" - Умное автоначисление рейтинга за комментарий, за ответ на комментарий.<br/>'
            . 'Мотивируйте пользователей, предложив им эту награду.'
            . '</div>';
    }
// END

    $opt = new Rcl_Options( __FILE__ );




    $content .= $opt->options( 'Настройки Latest comments author', array(
        $opt->options_box( 'Установите:', array(
            [
                'title'   => 'В какой области выводим кнопку вкладки?',
                'type'    => 'radio',
                'slug'    => 'lcp_area',
                'values'  => [ 'counters' => 'Область счётчиков', 'menu' => 'Область Menu' ],
                'default' => 'counters',
                'notice'  => 'По умолчанию: "Область счётчиков"<hr>',
            ],
            [
                'title'  => 'Кол-во комментариев на страницу:',
                'type'   => 'number',
                'slug'   => 'lca_comm',
                'help'   => 'Постраничная навигация. Установите значение.<br/><br/>'
                . 'Не выставляйте большие значения (20-и более) - чем больше на страницу, тем больше запросов к БД.',
                'notice' => 'По умолчанию: "10"<hr>',
            ],
            [
                'title'   => 'Контент вкладки комментариев покажем:',
                'type'    => 'radio',
                'slug'    => 'lcp_vide',
                'values'  => [ '1' => 'Всем', '2' => 'Только авторизованным', '3' => 'Только хозяину ЛК' ],
                'default' => '1',
                'notice'  => 'По умолчанию вкладка показывается: "Всем"<hr>',
            ],
            [
                'title'   => 'Используем цвета WP-Recall?',
                'type'    => 'radio',
                'slug'    => 'lca_color',
                'values'  => [ '0' => 'Нет', '1' => 'Да' ],
                'default' => '0',
                'help'    => 'В "Общих настройках" WP-Recall, вы выбираете цвет кнопок - пункт "Оформление". Включив эту опцию - блок комментариев будет в этом же стиле.'
                . '<br/><br/>Подробно, со скриншотами, в этой статье: '
                . '<a href="https://codeseller.ru/post-group/ispolzuem-cvet-rekoll-kotorym-my-stilizuem-knopki-dlya-svoix-dopolnenij/" target="_blank" title="Перейти. Откроется в новом окне">'
                . 'Используем цвет реколл, которым мы стилизуем кнопки, для своих дополнений'
                . '</a>',
            ],
            )
        ),
        $opt->options_box( '', array(
            [
                'type'    => 'custom',
                'content' => $my_adv
            ]
            )
        ),
        ) );

    return $content;
}
