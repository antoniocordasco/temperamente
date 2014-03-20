<form action="./" method="post">
	

		<div class="text">
			<label for="email">Email <span class="mand">*</span></label>
       		<input type="text" name="email" id="email" size="20" <?php if(isset($_POST['email'])){ echo ' value="'.strip_tags($post_unescaped['email']).'"'; } ?>/>
		</div>		

		<div class="submit"><input class="btn" type="submit" value="Invia"/></div>
	
</form>