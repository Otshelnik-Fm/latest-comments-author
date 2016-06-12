<?php
rcl_enqueue_style('latestcomments',__FILE__); // подключаем стили


function set_mytab_in_arr($array_tabs){ // вклиниваемся в массив табов
    $array_tabs['latestcomments']='latest_usr_comments';
    return $array_tabs;
}
add_filter('ajax_tabs_rcl','set_mytab_in_arr');

add_action('init','add_tab_comments');
function add_tab_comments(){
        global $user_ID,$user_LK,$rcl_options;
        $view = (isset($rcl_options['tab_vide']))?$rcl_options['tab_vide']:1;
       
        switch($view){
                case 1:
				case 2: $public = 1;break;
                case 3: $public = 0;break;
        }
 
        rcl_tab('latestcomments','latest_usr_comments','Комментарии',array('order'=>55,'class'=>'fa-comment-o','public'=>$public)); // добавляем вкладку
}


// добавляем настройки в админке
add_filter('admin_options_wprecall','admin_settings_allcomm');
function admin_settings_allcomm($options){
	
	global $active_addons;

	$opt = new Rcl_Options(__FILE__);
		if($active_addons['rating-system']){ // если дополнение рейтинга активировано то выполняем
			
			$options .= $opt->options(
				'Настройки Latest comments author',
				$opt->option_block(
					array(
						$opt->title('Установите:'),
					
						$opt->label('Кому показывать содержимое вкладки комментариев?'),
						$opt->option('select',array(
							'name'=>'tab_vide',
							'options'=>array('1'=>'Всем','2'=>'Только авторизованным','3'=>'Только хозяину лк',)
						)),
						$opt->notice('Установите значение. По умолчанию вкладка показывается Всем'),
						
						
						$opt->label('Показывать детально, кто из пользователей оценил комментарий?'),
						$opt->option('select',array(
							'name'=>'det_comm_stat',
							'options'=>array('yes'=>'Да','no'=>'Нет',)
						)),
						$opt->notice('Статистика- кто оценил комментарий, будет показана только авторизованным пользователям. Выберите Да, чтобы выводить ее. Нет - чтобы не показывать. По умолчанию: Да'),
						
						
						$opt->label('Сколько комментариев на страницу выводить?'),
						$opt->option('select',array(
							'name'=>'comm_nmbr_pp',
							'options'=>array(5=>'5',10=>'10',15=>'15',20=>'20',25=>'25',30=>'30',40=>'40',50=>'50',60=>'60',70=>'70',80=>'80',90=>'90',100=>'100',)
						)),
						$opt->notice('Пагинация. Установите значение. По умолчанию 10 комментариев на странице (чем больше на страницу - тем больше запросов к БД)'),
					   )
				)
			);
			
		}
		 else { // если дополнение рейтинга не активировано то выполняем это
			$options .= $opt->options(
						'Настройки Latest comments author',
						$opt->option_block(
							array(
								$opt->title('<span style="color: #b22222;">У вас не активирована система рейтинга!<br/></span>'),
								$opt->label('Перейдите на страницу <a href="' .home_url('/wp-admin/admin.php?page=manage-addon-recall', 'http'). '" title="Перейти к странице управления аддонами"> управления аддонами</a> и активируйте <a href="http://wppost.ru/products/rayting-recall-organizaciya-rejtingovoj-sistemy-wp-recall/" title="Перейти на страницу дополнения">Rating System (Система рейтинга)</a>'),
							)
						)
			);
}
	return $options;
}


// наша функция
function latest_usr_comments($user_LK){ // если мы в личном кабинете - начинаем

	global $wpdb, $user_ID, $rcl_options, $active_addons;
	
	if($active_addons['rating-system']){ // если дополнение рейтинга активировано то выполняем

		if(!$inpage) $inpage = $rcl_options['comm_nmbr_pp'];		// Передаем значение комментариев на страницу из админки
		if(!$inpage) $inpage = '10';	// если в админке пустое значение
		
		if(!$det_vote_stat) $det_vote_stat = $rcl_options['det_comm_stat'];		// получаем настройки отображения детальной оценки комментария по пользователям
		if(!$det_vote_stat) $det_vote_stat = 'yes';	// если в админке пустое значение
		
		if(!$tab_vide_view) $tab_vide_view = $rcl_options['tab_vide'];		// получаем настройки, кому показывать содержимое вкладки
		if(!$tab_vide_view) $tab_vide_view = '1';	// если в админке пустое значение

		if($_GET['comm-page']) { // получаем текущую страницу
			$navi = $_GET['comm-page'];
		}
			else $navi=1; // если не определена - значит первая
	 
		$start = ($navi-1)*$inpage; // отступ для запроса

		// формируем таблицу с данными
		$comments_user = $wpdb->get_results("
									SELECT comment_ID,comment_post_ID,comment_date,comment_content,rating_total
									FROM ".$wpdb->prefix ."comments AS big
									LEFT JOIN ".RCL_PREF."rating_totals  AS rtng
									ON(big.comment_ID=rtng.object_id) 
									WHERE big.user_id = '$user_LK' AND big.comment_approved = 1  
									ORDER BY comment_date DESC LIMIT $start,$inpage
								");
		
		// считаем комментарии пользователя
		$count_comments = $wpdb->get_var("SELECT COUNT(comment_ID) FROM ".$wpdb->prefix ."comments WHERE user_id = '$user_LK' AND comment_approved = 1");
	 
		$num_page = ceil($count_comments/$inpage);
	 
		
			if($tab_vide_view == '1' || $tab_vide_view == '2'&&$user_ID || $tab_vide_view == '3'&&$user_ID==$user_LK) { // проверяем настройки доступа: 1 - Всем, 2 - Только авторизованным, 3 - Только хозяину лк. Все верно - стартуем
				if($comments_user){ // и если есть комментарий у пользователя
				
				$commentlist = '<div class="lastcomm-count-total" title="Всего комментариев"><i class="fa fa-comments-o"></i>' .$count_comments. '</div>';
				$commentlist .= '<div class="lastcomm-h">Последние комментарии:</div><div class="commreport"></div>';
				$commentlist .= '<div class="lastcomm">';
				
					foreach($comments_user as $comment){
						$commen_tot_rait = $comment->rating_total; // общий рейтинг комментария
						$commen_zagolov = get_the_title($comment->comment_post_ID);
						$commentlist .= '<div class="lastcomm-feed">';   
							if ($navi==1) { // отсчитываем комментарии
								$commentlist .= '<div class="lastcomm-num">'.$count_comments.'</div>'; $count_comments--;
							}
							else {
								$commentlist .= '<div class="lastcomm-num">'.($count_comments-(($navi-1)*$inpage)).'</div>'; $count_comments--;
							}
						$commentlist .= '<div class="lastcomm-title"><i class="fa fa-volume-up"></i> <a href="'.get_permalink( $comment->comment_post_ID ).'#comment-'.$comment->comment_ID.'" title="Перейти к комментарию на запись: '.$commen_zagolov.'">'.$commen_zagolov.'</a></div><div class="lastcomm-date">'.mysql2date('d.m.Yг. H:i:s', $comment->comment_date).'</div>';     

						$commentlist .= '<div class="lastcomm-content">'.$comment->comment_content.'</div>';
						
							if($rcl_options['rating_comment']==1) { // если в настройках рейтинговой системы разрешены оценки к комментариям
								if($commen_tot_rait > 0) { // положительный рейтинг
									if($user_ID&&$det_vote_stat == 'yes') { // если выводить детализацию - кто проголосовал
										$commentlist .= rcl_get_html_post_rating($comment->comment_ID,'comment'); // выводим рейтинг и детализацию голосования
									} else { // если не выводить детализацию по проголосовавшим
										$commentlist .= '<div class="rayt-res rating-plus">Рейтинг: '.$commen_tot_rait.'</div>';
									}
									
								}
								elseif($commen_tot_rait < 0) { // отрицательный рейтинг
									if($user_ID&&$det_vote_stat == 'yes') {
										$commentlist .= rcl_get_html_post_rating($comment->comment_ID,'comment'); // выводим рейтинг и детализацию голосования
									} else {
										$commentlist .= '<div class="rayt-res rating-minus">Рейтинг: '.$commen_tot_rait.'</div>';
									}
								}
								elseif(!isset($commen_tot_rait)) { // 0 рейтинг
									if($user_ID&&$det_vote_stat == 'yes') {
										$commentlist .= rcl_get_html_post_rating($comment->comment_ID,'comment'); // выводим рейтинг и детализацию голосования
									} else {
										$commentlist .= '<div class="rayt-res">Рейтинг: 0</div>';
									}
								}
							//	else { // нет рейтинга
							//		$commentlist .= '<div class="rayt-res">Рейтинг: 0</div>';
							//	}
							}
						$commentlist .= '</div>';
					}
				
				$commentlist .= '</div>';
				$commentlist = convert_smilies($commentlist);
				
				
				if($inpage + $count_comments > $inpage){ // количество комментариев на страницу + оставшиеся > количества комментариев на страницу = выводим пагинацию
					$url = get_author_posts_url($user_LK);
					$url = explode('?',$url);
					if($url[1]){
						$redirect_url = get_author_posts_url($user_LK).'&';
					}else{
						$redirect_url = get_author_posts_url($user_LK).'?';
					}
					$page_navi .= '<div class="rcl-navi">';
					$page_navi .= '<span class="lastcomm_pages">'.$navi.' из '.$num_page.' </span>';
					$next = $navi + 3;
					$prev = $navi - 3;
					if($prev==1) $page_navi .= '<a href="'.$redirect_url.'comm-page=1&tab=latestcomments" title="Первая">1</a>';
					for($a=1;$a<=$num_page;$a++){
					if($a==1&&$a<=$prev&&$prev!=1) $page_navi .= '<a href="'.$redirect_url.'comm-page=1&tab=latestcomments" title="Первая">1</a><span class="extend">...</span>';        
						if($prev<$a&&$a<=$next){
						if($navi==$a) $page_navi .= '<span class="current">'.$a.'</span>';
							else $page_navi .= '<a href="'.$redirect_url.'comm-page='.$a.'&tab=latestcomments" title="Перейти">'.$a.'</a>';
						}              
					}
					if($next<$num_page&&$num_page!=$next+1) $page_navi .= '<span class="extend">...</span><a href="'.$redirect_url.'comm-page='.$num_page.'&tab=latestcomments" title="Последняя">'.$num_page.'</a>';
					if($num_page==$next+1) $page_navi .= '<a href="'.$redirect_url.'comm-page='.$num_page.'&tab=latestcomments" title="Последняя">'.$num_page.'</a>';
					$page_navi .= '</div>';
				}
		 
				$commentlist .= $page_navi;

				return nl2br($commentlist);
			} // если проверка не удалась - ниже:
			else {return '<span class="lastcomm_mess">Комментариев пока нет</span>';}
			
		} else {
			return '<span class="lastcomm_mess">Вкладка доступна авторизованным пользователям</span>';
		}
	}  else { // если дополнение рейтинга не активировано то выполняем это
			return '<div class="lastcomm_warn">У вас не активирована система рейтинга.<br/> Включите "Rating System (Система рейтинга)" на странице управления аддонами</div>';
		}
}