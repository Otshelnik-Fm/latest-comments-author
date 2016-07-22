<?php

function lca_settings($options){
    $opt = new Rcl_Options(__FILE__);
        $options .= $opt->options(
            'Настройки Latest comments author',
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
            )
        );
    return $options;
}
add_filter('admin_options_wprecall','lca_settings');