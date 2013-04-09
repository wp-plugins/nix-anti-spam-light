	<?php
	foreach ( $messages as $type => $contents ) {?>
		<?php if( $type == 'error' ) : ?>

			<div class="<?php echo $type ?> fade">
				<?php foreach ( $contents as $content ) {?>
					<p>
						<strong><?php _e( $nfasl->plugin_name , 'nix' ); ?>: </strong>
						<?php _e( $content , 'nix' ); ?>
					</p>
				<?php } ?>
			</div>

		 <?php elseif ( $type != 'error' && $nfasl->admin_help_notice() ) : ?>

			<div class="updated fade">
				<?php foreach ( $contents as $content ) {?>
					<p>
						<strong><?php echo ( $type == 'info' ) ? '' : ucfirst($type).': '; ?></strong>
						<?php _e( $content , 'nix' ); ?>
					</p>
				<?php } ?>
			</div>

		<?php endif ?>
	<?php }