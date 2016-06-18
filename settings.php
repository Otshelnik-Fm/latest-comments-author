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
                    $opt->notice('Установите значение. По умолчанию вкладка показывается Всем'),

                    $opt->label('Сколько комментариев на страницу выводить?'),
                    $opt->option('select',array(
                        'name'=>'lca_comm',
                        'options'=>array(5=>'5',10=>'10',15=>'15',20=>'20',25=>'25',30=>'30',40=>'40',50=>'50',60=>'60',70=>'70',80=>'80',90=>'90',100=>'100',)
                    )),
                    $opt->notice('Пагинация. Установите значение. По умолчанию 10 комментариев на странице (чем больше на страницу - тем больше запросов к БД)'),

                    $opt->label('Подкрашиваем контентный блок цветом WP-Recall?'),
                    $opt->option('select',array(
                        'name'=>'lca_color',
                        'options'=>array('0'=>'Нет','1'=>'Да',)
                    )),
                    $opt->notice('В "Общих настройках" WP-Recall вы выбираете цвет кнопок - пункт "Оформление". Включив эту опцию - блок комментариев и тени будут в этом же стиле'
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