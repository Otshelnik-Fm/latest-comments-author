<?php

function lca_settings($options){
    $my_adv = '';
    if(!rcl_exist_addon('subscription-two')){
        $my_adv = '<div class="lca_info">'
                . '<i class="rcli fa-info" style="color:#ffae19;font-size:20px;vertical-align:text-bottom;margin:0 5px;" aria-hidden="true"></i>'
                . ' <strong>Пользователи могут подписываться на комментарии</strong> - я написал дополнение: "<a href="https://codeseller.ru/products/subscription-two/" title="Перейти к описанию" target="_blank">Subscription Two</a>" - Залогиненные пользователи могут подписываться на комментарии к записям и форумам.<br/>'
                . 'Предлагаю вам ознакомиться с его функционалом.<br/>'
                . 'Подписки - отличный способ вернуть пользователя на ваш сайт.'
                . '</div>';
    }
    if(!rcl_exist_addon('smart-rating-for-comments')){
        $my_adv .= '<div class="lca_info">'
                . '<i class="rcli fa-info" style="color:#ffae19;font-size:20px;vertical-align:text-bottom;margin:0 5px;" aria-hidden="true"></i>'
                . ' <strong>Хотите мотивировать пользователей комментировать чаще?</strong> Дополнение: "<a href="https://codeseller.ru/products/smart-rating-for-comments/" title="Перейти к описанию" target="_blank">Smart Rating For Comments</a>" - Умное автоначисление рейтинга за комментарий, за ответ на комментарий.<br/>'
                . 'Мотивируйте пользователей, предложив им эту награду.'
                . '</div>';
    }


    $opt = new Rcl_Options(__FILE__);
        $options .= $opt->options(
            'Настройки Latest comments author', array(
            $opt->option_block(
                array(
                    $opt->title('Установите:'),

                    $opt->label('Кому показывать содержимое вкладки комментариев?'),
                    $opt->option('select',array(
                        'name'=>'lcp_vide',
                        'options'=>array('1'=>'Всем','2'=>'Только авторизованным','3'=>'Только хозяину лк',)
                    )),
                    $opt->notice('По умолчанию вкладка показывается Всем<br/><br/>'),

                    $opt->label('Сколько комментариев на страницу выводить?'),
                    $opt->option('number',array('name'=>'lca_comm')),
                    $opt->help('Не выставляйте большие значения (20-и более) чем больше на страницу - тем больше запросов к БД.'
                            . '<br/>Другое дело - когда у вас в "Общих настройках" реколл подключено кеширование - тогда количество роли не играет - данные будут кешироваться'),
                    $opt->notice('Пагинация. Установите значение.<br/>По умолчанию <strong>10</strong> комментариев на странице<br/><br/>'),

                    $opt->label('Подкрашиваем контентный блок основным цветом WP-Recall?'),
                    $opt->option('select',array(
                        'name'=>'lca_color',
                        'options'=>array('0'=>'Нет','1'=>'Да',)
                    )),
                    $opt->help('В "Общих настройках" WP-Recall, вы выбираете цвет кнопок - пункт "Оформление". Включив эту опцию - блок комментариев и тени будут в этом же стиле'
                            . '<br/>Подробно, со скриншотами, в этой статье: '
                            . '<a href="https://codeseller.ru/post-group/ispolzuem-cvet-rekoll-kotorym-my-stilizuem-knopki-dlya-svoix-dopolnenij/" target="_blank" title="Перейти. Откроется в новом окне">'
                            . 'Используем цвет реколл, которым мы стилизуем кнопки, для своих дополнений'
                            . '</a>'),
                )
            ),
            $my_adv
        ));
    return $options;
}
add_filter('admin_options_wprecall','lca_settings');