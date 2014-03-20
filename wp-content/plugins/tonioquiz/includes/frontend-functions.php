<?php

	function save_answer($form_data)
	{
		$sql = "DELETE FROM wp_tq_answer WHERE choice_id='".intval($form_data['choice'])."' AND answers_set_id='".intval($form_data['answers_set_id'])."'; ";
		$GLOBALS['wpdb']->query($sql);
		
		$sql = "INSERT INTO wp_tq_answer (created,choice_id,answers_set_id) 
		VALUES ('".date('Y-m-d H:i:s')."','".intval($form_data['choice'])."','".intval($form_data['answers_set_id'])."'); ";
		
		$GLOBALS['wpdb']->get_results($sql);
	}
	
	function get_random_questions($post_id,$num_questions)
	{
		$sql = "SELECT * FROM wp_tq_question WHERE quiz_post_id='".intval($post_id)."' ORDER BY RAND() LIMIT ".$num_questions;
		$rows = $GLOBALS['wpdb']->get_results($sql);
		return $rows;
	}


	function create_answers_set($question_ids,$quiz_id)
	{ 
		$data = array('quiz_id'=>$quiz_id,'ip'=>$_SERVER['REMOTE_ADDR'],'created'=>date('Y-m-d H:i:s'),'updated'=>date('Y-m-d H:i:s'),'question_ids'=>$question_ids);		
		$GLOBALS['wpdb']->insert('wp_tq_answers_set',$data);
		return $GLOBALS['wpdb']->insert_id;
	}
	
	function save_registration($form_data)
	{ 	
		$start_time = $GLOBALS['wpdb']->get_results('SELECT created FROM wp_tq_answers_set WHERE id = '.intval($form_data['answers_set_id']));
		$start_time = $start_time[0]->created;
		
		$sql = 'SELECT wp_tq_answer.*,wp_tq_choice.right FROM wp_tq_answer JOIN wp_tq_choice ON wp_tq_answer.choice_id=wp_tq_choice.id WHERE answers_set_id='.intval($form_data['answers_set_id']).' ORDER BY wp_tq_answer.id ASC';	
		$rows = $GLOBALS['wpdb']->get_results($sql);
		$total_time = strtotime($rows[count($rows)-1]->created) - strtotime($start_time);
		$right_answers=0;
		foreach($rows as $row)
		{ 
			if($row->right==1){ $right_answers++; }
		}
		
	
		
		$data = array('points'=>tq_get_points($right_answers,$total_time), 'updated'=>date('Y-m-d H:i:s'), 'name'=>strip_tags($form_data['nickname']), 'email'=>strip_tags($form_data['email']) );
		if(isset($form_data['newsletter']) && $form_data['newsletter']=='true'){ 
			$data['newsletter']='true'; 
			owni_tn2_subscribe($data['email']);
		}else{ 
			$data['newsletter']='false'; 
		}
		$GLOBALS['wpdb']->update('wp_tq_answers_set',$data,array('id'=>$form_data['answers_set_id']));
	}


	
	
	function tq_get_points($correct_answers,$total_time)
	{
		$time_bonus = 0;
		if($total_time<250){ $time_bonus = intval(pow((250-$total_time) , 3)/7000); }	
		if($time_bonus>800){ $time_bonus = 800; }		
		return $time_bonus + ($correct_answers * 100);		
	}
	
	
	
	
	
	
	
	
	
	





	add_action('the_content','tq_include_quiz');	
	function tq_include_quiz($content)
	{	
		if(is_single())
		{ 
		
			$errori_form_registrazione = array();
			if(isset($_POST['nickname']))
			{
				if($_POST['nickname']==''){ $errori_form_registrazione[] = 'Inserisci un nickname'; }
				if($_POST['email']==''){ $errori_form_registrazione[] = 'Inserisci la tua email'; }
				elseif(!is_email($_POST['email'])){ $errori_form_registrazione[] = 'L\'email inserita non è valida'; }
			}
			if(isset($_POST['current_question']) && !isset($_POST['choice']))
			{
				$_POST['next_question'] = $_POST['current_question'];
				unset($_POST['current_question']);				
			}			
		// var_dump($_POST); echo '<br><br>';		
		
			if((isset($_GET['tqid']) && intval($_GET['tqid'])>0 && isset($_GET['idcheck']) && $_GET['idcheck']!='')
			|| (isset($_GET['show_ranking'])&&$_GET['show_ranking']=='true')   ){
				
				$quiz_html = get_ranking_html($GLOBALS['post']->ID,intval($_GET['tqid']),$_GET['idcheck']);
				$content = $quiz_html;
				
			}elseif(count($_POST)<=0){
				$quiz_html = get_quiz_intro_html($GLOBALS['post']->ID);
				$content = str_replace('<!---quiz--->',$quiz_html,$content);
				
			}elseif( count($errori_form_registrazione)==0 && isset($_POST['nickname'])){
				
				save_registration($_POST);
				wp_redirect('./?tqid='.intval($_POST['answers_set_id']).'&idcheck='.md5($_POST['nickname']).'&share_button=true');
				
			}elseif((isset($_POST['next_question']) && $_POST['next_question']>=count(split(',',$_POST['question_ids'])))
			|| count($errori_form_registrazione)>0			
			){
				$quiz_html = get_results_html($_POST,$errori_form_registrazione);
				$content = $quiz_html;
			}elseif(isset($_POST['next_question']) && isset($_POST['question_ids'])){
				if(!isset($_POST['answers_set_id'])){ $_POST['answers_set_id'] = create_answers_set($_POST['question_ids'],$GLOBALS['post']->ID); }
				$quiz_html = get_question_html($_POST);
				$content = $quiz_html;
			}elseif(isset($_POST['current_question']) && isset($_POST['question_ids'])){
				$quiz_html = get_right_answer_html($_POST);
				save_answer($_POST);
				$content = $quiz_html;
			}
		}
		return $content;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	function get_ranking_html($quiz_id,$tq_ans_id,$idcheck)
	{	
		$sql_common = 'FROM wp_tq_answers_set JOIN wp_tq_answer ON wp_tq_answers_set.id=wp_tq_answer.answers_set_id    
		JOIN wp_tq_choice ON wp_tq_answer.choice_id=wp_tq_choice.id   
		JOIN wp_tq_question ON wp_tq_choice.question_id=wp_tq_question.id
		JOIN wp_posts ON wp_tq_question.quiz_post_id=wp_posts.ID ';
		
		$html1 = '';
		$html2 = '';
		
		if(intval($tq_ans_id)>0)
		{ 	
			$sql = 'SELECT wp_tq_answers_set.*,wp_posts.post_title,wp_tq_question.quiz_post_id '.$sql_common.' WHERE wp_tq_answers_set.id = '.$tq_ans_id.' LIMIT 0,1';				
			$rows = $GLOBALS['wpdb']->get_results($sql);
		//	echo $sql; die;
			
			if(isset($rows[0]) && md5($rows[0]->name)==$idcheck)
			{	
				$questions = $GLOBALS['wpdb']->get_results('SELECT wp_tq_answer.*,wp_tq_choice.right FROM wp_tq_answer JOIN wp_tq_choice ON wp_tq_answer.choice_id=wp_tq_choice.id  WHERE answers_set_id='.$tq_ans_id.' ORDER BY wp_tq_answer.id ASC');
			
				$total_time = strtotime($questions[count($questions)-1]->created) - strtotime($rows[0]->created);
			
				$correct_answers = 0;
				foreach($questions as $q)
				{ 
					if($q->right==1){ $correct_answers++; }
				}
				$html1 = "<p><strong>{$rows[0]->name} ha totalizzato {$rows[0]->points} punti, rispondendo correttamente a $correct_answers domande su ".count($questions).", in $total_time secondi</strong></p>";
												
				
				if(isset($_GET['share_button']) && $_GET['share_button']=='true')
				{ 
					$url = str_replace('&share_button=true','',$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
					$html2 = '<p><strong><a href="./">Ripeti il quiz</a>, oppure condividi questo risultato su facebook.</strong><br/>Riusciranno i tuoi amici a battere il tuo punteggio? </p><p><a name="fb_share" type="box_count" share_url="'.$url.'" href="http://www.facebook.com/sharer.php">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script></p>';
				}else{
					$html2 = '<p><strong><a href="./">Mettiti alla prova</a> con il quiz: '.$GLOBALS['post']->post_title.'</p>';
				}
				
			}
		}
		
		$sql = 'SELECT * FROM wp_tq_answers_set WHERE id
				IN (SELECT DISTINCT(wp_tq_answers_set.id)'.$sql_common." WHERE name<>'' AND wp_tq_question.quiz_post_id=".$quiz_id.' )
				ORDER BY points DESC LIMIT 0,100';
			//	echo $sql;
		$rows2 = $GLOBALS['wpdb']->get_results($sql);
		$ranking_html = "<p>Classifica:</p><table class=\"tq_ranking\" ><thead><tr><th>Nome</th><th>Punti</th><th>Data</th></tr></thead><tbody>";			
		foreach($rows2 as $row)
		{
			if($row->id==$tq_ans_id){ $class_row=' class="tq_current_user" '; }else{ $class_row=''; }
			$ranking_html .=  "<tr $class_row ><td>{$row->name}</td><td>{$row->points}</td><td>".date('m:H d/m/Y',strtotime($row->created))."</td></tr>";
		}
		$ranking_html .= "</tbody></table> ";	
		
		
		
		
		
		
		
		
		return $html1.$html2.$ranking_html.$html2;
	}
	
	
	
	function get_results_html($form_data,$errors)
	{
		$start_time = $GLOBALS['wpdb']->get_results('SELECT created FROM wp_tq_answers_set WHERE id = '.intval($form_data['answers_set_id']));
		$start_time = $start_time[0]->created;
		
		$sql = 'SELECT wp_tq_answer.*,wp_tq_choice.right FROM wp_tq_answer JOIN wp_tq_choice ON wp_tq_answer.choice_id=wp_tq_choice.id WHERE answers_set_id='.intval($form_data['answers_set_id']).' ORDER BY wp_tq_answer.id ASC';	
		$rows = $GLOBALS['wpdb']->get_results($sql);
		$total_time = strtotime($rows[count($rows)-1]->created) - strtotime($start_time);
		
		$form_data_reg = array();
		if(isset($form_data['nickname'])){ $form_data_reg['nickname'] = strip_tags(str_replace('"',' ',$form_data['nickname'])); }else{ $form_data_reg['nickname'] = ''; }
		if(isset($form_data['email'])){ $form_data_reg['email'] = strip_tags(str_replace('"',' ',$form_data['email'])); }else{ $form_data_reg['email'] = ''; }
		if(isset($form_data['newsletter'])){ $form_data_reg['newsletter'] = ' checked="checked" '; }else{ $form_data_reg['newsletter'] = ''; }
		$right_answers=0;
		foreach($rows as $row)
		{ 
			if($row->right==1){ $right_answers++; }
		}
		
		$return = '<div class="tq_wrapper">
		<p>Hai risposto correttamente a '.$right_answers.' domande su '.count($rows).' in '.$total_time.' secondi.<br/>
		Inserisci il tuo nome e la tua email per avere un resoconto del test, con punteggio e classifica finale.
		L\'email non sar&agrave; pubblicata sul sito.</p>
		<form action="./" method="post">';
		
		foreach($errors as $err){ $return .= '<div ><p >'.$err.'</p></div>	'; }
		
		$return .= '<div ><p ><input type="hidden" name="question_ids" value="'.$form_data['question_ids'].'" />
		<input type="hidden" name="answers_set_id" value="'.intval($form_data['answers_set_id']).'" />	</p></div>	
		<div ><p ><label for="nickname">Nome: </label><input type="text" id="nickname" name="nickname" value="'.$form_data_reg['nickname'].'" maxlength="100" /></p></div>
		<div ><p ><label for="email">Email: </label><input type="text" id="email" name="email" value="'.$form_data_reg['email'].'" maxlength="100" /></p></div>
		<div ><p ><label for="newsletter">Registrami alla newsletter</label><input type="checkbox" id="newsletter" name="newsletter" value="true" '.$form_data_reg['newsletter'].'/></p></div>
		';
				
		$return .= '<p class="submit_row" ><input type="submit"  value="Submit" /></p>
		</form>
		</div>';
		return $return;	
	}
	
	
	
	
	function get_quiz_intro_html($post_id)
	{
		$questions = get_random_questions($post_id,10);
		$question_ids = array();
		foreach($questions as $q){ $question_ids[]=$q->id; }
	//	var_dump($questions);
		$return = '<div class="tq_wrapper">
		<form action="./" method="post"><input type="hidden" name="question_ids" value="'.implode(',',$question_ids).'" />
		<input type="hidden" name="next_question" value="0" />
		<input type="submit" name="start_quiz" value="Inizia il quiz" />
		</form>
		</div>';
		return $return;
	
	}
	
	function get_question_html($form_data)
	{
		$question_letters=array('A','B','C','D');
		$question_ids = explode(',',$form_data['question_ids']);
		$current_question = tq_get_question($question_ids[intval($form_data['next_question'])]);
		
		$return = '<div class="tq_wrapper"><form action="./#tq_form" method="post"><a name="tq_form"></a>
		<div class="question"><p ><input type="hidden" name="question_ids" value="'.$form_data['question_ids'].'" />
		<input type="hidden" name="answers_set_id" value="'.intval($form_data['answers_set_id']).'" />';
		if(isset($current_question->image_url) && $current_question->image_url!='')
		{
			$return .= '<img class="magnify" src="'.$current_question->image_url.'" />'."\n";
		}
		$return .= $current_question->question_text.'</p></div>'."\n";
		
		for($i=0;$i<4;$i++)
		{
			if(strlen($current_question->{'choice_'.$i}->choice_text)>35){ $label_class='two_lines'; }else{ $label_class='single_line'; }
			$return .= '<div class="choice" ><input type="radio" name="choice" id="choice_'.$i.'" value="'.$current_question->{'choice_'.$i}->id.'" /><label for="choice_'.$i.'" class="'.$label_class.'" ><span>'.$question_letters[$i].'</span>';
			$return .= '<strong>'.$current_question->{'choice_'.$i}->choice_text.'</strong></label></div>'."\n";
		}
		
		$return .= '<p class="submit_row" ><input name="current_question" value="'.intval($form_data['next_question']).'"  type="hidden" />
		<input type="submit"  value="Invia" /></p>
		</form>
		</div>
	
		
		
		';
		return $return;	
	}
	
	function get_right_answer_html($form_data)
	{ 
		$question_letters=array('A','B','C','D');
		$question_ids = explode(',',$form_data['question_ids']);
		$current_question = tq_get_question($question_ids[intval($form_data['current_question'])]);
		
		$return = '<div class="tq_wrapper"><form action="./#tq_form" method="post"><a name="tq_form"></a>
		<div class="question"><p ><input type="hidden" name="question_ids" value="'.$form_data['question_ids'].'" /><input type="hidden" name="answers_set_id" value="'.intval($form_data['answers_set_id']).'" />';
		if(isset($current_question->image_url) && $current_question->image_url!='')
		{
			$return .= '<img src="'.$current_question->image_url.'" />'."\n";
		}
		$return .= $current_question->question_text.'</p></div>';
		
		for($i=0;$i<4;$i++)
		{			
			if(strlen($current_question->{'choice_'.$i}->choice_text)>35){ $label_class='two_lines'; }else{ $label_class='single_line'; }
			if($current_question->{'choice_'.$i}->right==1){ $class_tmp=' correct '; }else{ $class_tmp=''; }
			if($current_question->{'choice_'.$i}->id==$form_data['choice']){ $checked=' checked="checked" '; }else{ $checked=''; }
			$return .= '<div class="choice '.$class_tmp.'"><input '.$checked.'type="radio"  id="choice_'.$i.'" /><label class="'.$label_class.'" for="choice_'.$i.'"><span>'.$question_letters[$i].'</span><strong>'.$current_question->{'choice_'.$i}->choice_text.'</strong></label></div>';
		}
		
		$return .= '<p class="submit_row" ><input name="next_question" value="'. (intval($form_data['current_question'])+1) .'"  type="hidden" /><input type="submit"  value="Prossima domanda" /></p>
		</form>
		</div>';
		return $return;	
	}
	
	
	
	