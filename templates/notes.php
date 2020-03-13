<?php
// Main template for EasyNotes
if ( is_page() ) {

	if ( ! is_user_logged_in() ) {
		echo 'You\'re not logged in!';
		exit;
	}
?>
<div id="easynotes-outer-wrapper">
	<div class="create-note">
		<h2>Create A New EasyNote</h2>
		<input class="new-note-title" placeholder="Enter a title..">
		<textarea class="new-note-body" placeholder="Your note here.."></textarea>
		<span class="submit-note">Create Note</span>
		
	</div>
	<ul id="easynotes-wrapper">
		<?php

		$userNotes = new WP_Query(array(
			'post_type' => 'easynotes',
			'post_per_page' => -1,
			'author' => get_current_user_id(),
		));

		while($userNotes->have_posts()) {
			$userNotes->the_post(); ?>
			<li class="easynotes-item" data-id="<?php the_ID(); ?>">
				<input readonly class="note-title-field" value="<?php echo esc_attr(get_the_title()); ?>">
				<textarea readonly class="note-body-field" ><?php echo esc_attr(get_the_content()); ?></textarea>
				<span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
                <span class="update-note"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>

			</li>
		<?php }
		?>
	</ul>
</div>
<?php
}
?>