<?php 
/*
Template Name: Contact
*/

include 'lib/Validator.php';

$validator = new Validator;

if($_POST['submit']):	
	$validator->add_rule('contactname', 'Name', 'required');
	$validator->add_rule('email', 'Email', 'required|email');
	$validator->add_rule('message', 'Message', 'required|min(5)');
	$validator->custom_message('email', 'required', "Please enter your email, this will only be used to respond to your message");
	$validator->custom_message('email', 'email', "Please enter a valid email, this will only be used to respond to your message");
	if($validator->run()==true):
		$to = (get_option('contact_email') == '') ? get_option('admin_email') : get_option('contact_email');
		$subject = $validator->get_value('subject');
		if(empty($subject)){
			$subject = "Response from the ".get_option('blogname')." site";
		}
		$message = $validator->get_value('contactname')." sent you a message\n\n".$validator->get_value('message')."\n\n".$validator->get_value('email');
		$headers = "From: Aurer Contact Form <mail@".str_replace('http://', '', get_option('siteurl')).">"."\r\n";
		$headers .= 'Reply-To:'.$validator->get_value('email')."\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion()."\r\n";
		$email_sent = mail($to, $subject, $message, $headers);
		if($email_sent){
			header('Location: '.get_permalink( $post->ID ).'?sent=true');
		}
	endif;
endif;
?>
<?php get_header() ?>
	
	<div id="content">
		<section id="main">
			<?php the_post() ?>
			<h1><?php wp_title('') ?></h1>
			
			<?php the_content() ?>

			<?php if(empty($_GET['sent'])) : ?>
				<form action="" method="post" id="contact-form" >
					<p class="field <?php echo $validator->error_class('contactname'); ?>">
						<label>Name</label>
						<span class="input">
							<input type="text" name="contactname" value="<?php echo $validator->get_value('contactname'); ?>" />
							<?php $validator->field_error('contactname', '<span class="error">', '</span>') ?>
						</span>
					</p>
					<p class="field <?php echo $validator->error_class('email'); ?>">
						<label>Email</label>
						<span class="input">
							<input type="email" name="email" value="<?php echo $validator->get_value('email'); ?>" />
							<?php $validator->field_error('email', '<span class="error">', '</span>') ?>
						</span>
					</p>
					<p class="field <?php echo $validator->error_class('subject'); ?>">
						<label>Subject</label>
						<span class="input">
							<input type="text" name="subject" value="<?php echo $validator->get_value('subject'); ?>" />
							<?php $validator->field_error('subject', '<span class="error">', '</span>') ?>
						</span>
					</p>
					<p class="field <?php echo $validator->error_class('message'); ?>">
						<label>Message</label>
						<span class="input">					
							<textarea name="message" id="in-message" cols="30" rows="6"><?php echo $validator->get_value('message'); ?></textarea>
							<?php $validator->field_error('message', '<span class="error">', '</span>') ?>
						</span>
					</p>
					<p class="field submit">
						<input type="submit" name="submit" value="Send" />
					</p>
				</form>
				<script type="text/javascript">
					(function(){
						var i = document.createElement('input');
		    			placeholder = 'placeholder' in i;
		    			if(placeholder){
							$.each($('#contact-form').find('p.field'), function(){
								var label = $(this).find('label').hide().text();
								$(this).find('span.input').addClass('fullwidth').find('input, textarea').attr('placeholder', label);
							});
						}
					})();
				</script>
			<?php else: ?>
				<div class="sent">
					<h2>Thanks for getting in touch.</h2>
					<p>I will try to get back you you as soon as possible.</p>
					<p><b>Phil.</b></p>
				</div>
			<?php endif ?>

		</section>
		
	</div>
	
<?php get_footer() ?>