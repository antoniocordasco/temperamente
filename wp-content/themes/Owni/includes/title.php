		<title><?php			if (is_home()) {				bloginfo('name');			} elseif (is_404()) {				echo '404 Pagina non trovata'; echo ' | '; bloginfo('name');			} elseif (is_category()) {				echo 'Categoria:'; wp_title(''); echo ' | '; bloginfo('name');			} elseif (is_search()) {				echo 'Risultati ricerca'; echo ' | '; bloginfo('name');			} elseif ( is_day() || is_month() || is_year() ) {				echo 'Archivi:'; wp_title(''); echo ' | '; bloginfo('name');			} elseif ( is_single() AND owni_is_review($post->ID)) {				echo wp_title(''); echo ' | Recensione di '; bloginfo('name');			} else {				echo wp_title(''); echo ' | '; bloginfo('name');			}			global $codename,$$codename,$paged; 			if($paged && $paged > 1) { echo ' | Pagina ' . $paged; }		?></title>