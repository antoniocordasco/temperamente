	</div><!-- /content -->

	<div id="sidebar">
		<div id="search-box">		
			<form  action="<?php echo get_option('home') ?>/" method="get">
				<fieldset>
					<input type="text"  name="s" value="Cerca su temperamente ... " class="input-text"/>
					<input type="submit" value="" class="input-submit"/>
				</fieldset>
			</form>
		</div>
		<ul>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : ?>
		<li class="widget">sssssssssssssssssssssssssss
			<h4 class="widget-title">Archives</h4>
			<ul>
			<?php wp_get_archives('type=monthly'); ?>
			</ul>
		</li>
		<?php endif ?>
		</ul>
	</div><!-- /sidebar -->
