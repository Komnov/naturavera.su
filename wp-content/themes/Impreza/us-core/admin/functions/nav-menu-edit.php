<?php

// Custom "Menus" admin screen, added "Exclude Rows and Columns" checkbox for Page Blocks
add_action( 'wp_update_nav_menu_item', 'us_update_menu_custom_field', 10, 3 );
function us_update_menu_custom_field( $menu_id, $menu_item_db_id, $args ) {

	if ( $args['menu-item-object'] == 'us_page_block' ) {
		if ( isset( $_POST['menu-item-remove-rows'] ) ) {
			$custom_fields = $_POST['menu-item-remove-rows'];
			$value = isset( $custom_fields[ $menu_item_db_id ] ) ? '1' : '0';
		} else {
			// Enabled by default
			$value = empty( $_POST['menu-item-db-id'] ) ? '1' : '0';
		}

		update_post_meta( $menu_item_db_id, '_menu_item_remove_rows', $value );
	}
}

// Custom "Menus" admin screen, added "Exclude Rows and Columns" checkbox for Page Blocks
add_filter( 'wp_edit_nav_menu_walker', 'us_edit_nav_menu_walker' );
function us_edit_nav_menu_walker() {
	if ( ! class_exists( 'Upsolution_Custom_Walker_Nav_Menu_Edit' ) ) {

		// This is a MODIFIED copy of Walker_Nav_Menu_Edit class in WordPress core
		class Upsolution_Custom_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {
			public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
				global $_wp_nav_menu_max_depth;
				$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

				ob_start();
				$item_id = esc_attr( $item->ID );
				$removed_args = array(
					'action',
					'customlink-tab',
					'edit-menu-item',
					'menu-item',
					'page-tab',
					'_wpnonce',
				);

				$original_title = FALSE;
				if ( 'taxonomy' == $item->type ) {
					$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
					if ( is_wp_error( $original_title ) ) {
						$original_title = FALSE;
					}
				} elseif ( 'post_type' == $item->type ) {
					$original_object = get_post( $item->object_id );
					$original_title = get_the_title( $original_object->ID );
				} elseif ( 'post_type_archive' == $item->type ) {
					$original_object = get_post_type_object( $item->object );
					if ( $original_object ) {
						$original_title = $original_object->labels->archives;
					}
				}

				$classes = array(
					'menu-item menu-item-depth-' . $depth,
					'menu-item-' . esc_attr( $item->object ),
					'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive' ),
				);

				$title = $item->title;

				if ( ! empty( $item->_invalid ) ) {
					$classes[] = 'menu-item-invalid';
					/* translators: %s: title of menu item which is invalid */
					$title = sprintf( us_translate( '%s (Invalid)' ), $item->title );
				} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
					$classes[] = 'pending';
					/* translators: %s: title of menu item in draft status */
					$title = sprintf( us_translate( '%s (Pending)' ), $item->title );
				}

				$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

				$submenu_text = '';
				if ( 0 == $depth ) {
					$submenu_text = 'style="display: none;"';
				}

				?>
			<li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode( ' ', $classes ); ?>">
				<div class="menu-item-bar">
					<div class="menu-item-handle">
						<span class="item-title"><span
								class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span
								class="is-submenu" <?php echo $submenu_text; ?>><?php echo us_translate( 'sub item' ); ?></span></span>
						<span class="item-controls">
							<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
							<span class="item-order hide-if-js">
								<a href="
								<?php
								echo wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-up-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								);
								?>
								" class="item-move-up" aria-label="<?php echo us_translate( 'Move up' ); ?>">&#8593;</a>
								|
								<a href="
								<?php
								echo wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-down-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								);
								?>
								" class="item-move-down" aria-label="<?php echo us_translate( 'Move down' ); ?>">&#8595;</a>
							</span>
							<a class="item-edit" id="edit-<?php echo $item_id; ?>" href="
																		<?php
							echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
							?>
							" aria-label="<?php echo us_translate( 'Edit menu item' ); ?>"><span
									class="screen-reader-text"><?php echo us_translate( 'Edit' ); ?></span></a>
						</span>
					</div>
				</div>

				<div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo $item_id; ?>">
					<?php if ( 'custom' == $item->type ) : ?>
						<p class="field-url description description-wide">
							<label for="edit-menu-item-url-<?php echo $item_id; ?>">
								<?php echo us_translate( 'URL' ); ?><br />
								<input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>"
									   class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]"
									   value="<?php echo esc_attr( $item->url ); ?>" />
							</label>
						</p>
					<?php endif; ?>
					<p class="description description-wide">
						<label for="edit-menu-item-title-<?php echo $item_id; ?>">
							<?php echo us_translate( 'Navigation Label' ); ?><br />
							<input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>"
								   class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]"
								   value="<?php echo esc_attr( $item->title ); ?>" />
						</label>
					</p>
					<p class="field-title-attribute field-attr-title description description-wide">
						<label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
							<?php echo us_translate( 'Title Attribute' ); ?><br />
							<input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>"
								   class="widefat edit-menu-item-attr-title"
								   name="menu-item-attr-title[<?php echo $item_id; ?>]"
								   value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
						</label>
					</p>
					<p class="field-link-target description">
						<label for="edit-menu-item-target-<?php echo $item_id; ?>">
							<input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank"
								   name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
							<?php echo us_translate( 'Open link in a new tab' ); ?>
						</label>
					</p>
					<p class="field-css-classes description description-thin">
						<label for="edit-menu-item-classes-<?php echo $item_id; ?>">
							<?php echo us_translate( 'CSS Classes (optional)' ); ?><br />
							<input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>"
								   class="widefat code edit-menu-item-classes"
								   name="menu-item-classes[<?php echo $item_id; ?>]"
								   value="<?php echo esc_attr( implode( ' ', $item->classes ) ); ?>" />
						</label>
					</p>
					<p class="field-xfn description description-thin">
						<label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
							<?php echo us_translate( 'Link Relationship (XFN)' ); ?><br />
							<input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>"
								   class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]"
								   value="<?php echo esc_attr( $item->xfn ); ?>" />
						</label>
					</p>
					<p class="field-description description description-wide">
						<label for="edit-menu-item-description-<?php echo $item_id; ?>">
							<?php echo us_translate( 'Description' ); ?><br />
							<textarea id="edit-menu-item-description-<?php echo $item_id; ?>"
									  class="widefat edit-menu-item-description" rows="3" cols="20"
									  name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
							<span
								class="description"><?php echo us_translate( 'The description will be displayed in the menu if the current theme supports it.' ); ?></span>
						</label>
					</p>
					<?php

					// Custom UpSolution options
					if ( $item->object == 'us_page_block' ) { ?>
						<p class="field-us-custom">
							<label for="edit-menu-item-remove-rows-<?php echo $item_id; ?>">
								<input type="checkbox" id="edit-menu-item-remove-rows-<?php echo $item_id; ?>"
									   name="menu-item-remove-rows[<?php echo $item_id; ?>]"<?php checked( get_post_meta( $item_id, '_menu_item_remove_rows', TRUE ) ) ?> />
								<?php echo esc_attr( __( 'Exclude Rows and Columns', 'us' ) ); ?>
							</label>
						</p>
					<?php } ?>

					<fieldset class="field-move hide-if-no-js description description-wide">
						<span class="field-move-visual-label"
							  aria-hidden="true"><?php echo us_translate( 'Move' ); ?></span>
						<button type="button" class="button-link menus-move menus-move-up"
								data-dir="up"><?php echo us_translate( 'Up one' ); ?></button>
						<button type="button" class="button-link menus-move menus-move-down"
								data-dir="down"><?php echo us_translate( 'Down one' ); ?></button>
						<button type="button" class="button-link menus-move menus-move-left" data-dir="left"></button>
						<button type="button" class="button-link menus-move menus-move-right" data-dir="right"></button>
						<button type="button" class="button-link menus-move menus-move-top"
								data-dir="top"><?php echo us_translate( 'To the top' ); ?></button>
					</fieldset>

					<div class="menu-item-actions description-wide submitbox">
						<?php if ( 'custom' != $item->type && $original_title !== FALSE ) : ?>
							<p class="link-to-original">
								<?php
								/* translators: %s: original title */
								printf( us_translate( 'Original: %s' ), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' );
								?>
							</p>
						<?php endif; ?>
						<a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="
																							<?php
						echo wp_nonce_url(
							add_query_arg(
								array(
									'action' => 'delete-menu-item',
									'menu-item' => $item_id,
								),
								admin_url( 'nav-menus.php' )
							),
							'delete-menu_item_' . $item_id
						);
						?>
						"><?php echo us_translate( 'Remove' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a
							class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="
						<?php
						echo esc_url(
							add_query_arg(
								array(
									'edit-menu-item' => $item_id,
									'cancel' => time(),
								),
								admin_url( 'nav-menus.php' )
							)
						);
						?>
							#menu-item-settings-<?php echo $item_id; ?>"><?php echo us_translate( 'Cancel' ); ?></a>
					</div>

					<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]"
						   value="<?php echo $item_id; ?>" />
					<input class="menu-item-data-object-id" type="hidden"
						   name="menu-item-object-id[<?php echo $item_id; ?>]"
						   value="<?php echo esc_attr( $item->object_id ); ?>" />
					<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]"
						   value="<?php echo esc_attr( $item->object ); ?>" />
					<input class="menu-item-data-parent-id" type="hidden"
						   name="menu-item-parent-id[<?php echo $item_id; ?>]"
						   value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
					<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]"
						   value="<?php echo esc_attr( $item->menu_order ); ?>" />
					<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]"
						   value="<?php echo esc_attr( $item->type ); ?>" />
				</div><!-- .menu-item-settings-->
				<ul class="menu-item-transport"></ul>
				<?php
				$output .= ob_get_clean();
			}
		}
	}

	return 'Upsolution_Custom_Walker_Nav_Menu_Edit';
}
