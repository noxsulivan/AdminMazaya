<?php
/**
 * @package WordPress
 * @subpackage QualiFire
 */
?>
<?php	global $qualifire_options, $style; ?>


</div><!-- end page-content -->

<div class="clear"></div>

<?php

	$bottom_1_is_active = sidebar_exist_and_active('Bottom 1');
	$bottom_2_is_active = sidebar_exist_and_active('Bottom 2');
	$bottom_3_is_active = sidebar_exist_and_active('Bottom 3');
	$bottom_4_is_active = sidebar_exist_and_active('Bottom 4');

	if ( $bottom_1_is_active || $bottom_2_is_active || $bottom_3_is_active || $bottom_4_is_active ) : // hide this area if no widgets are active...
?>
	    <div id="bottom-bg">
		<div id="bottom" class="container_24">
<?php
		    // all 4 active: 1 case
		    if ( $bottom_1_is_active && $bottom_2_is_active && $bottom_3_is_active && $bottom_4_is_active ) {
			eval( '?>' . get_column( 'bottom_1', 'column_1_of_4', 'Bottom 1' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_2', 'column_1_of_4', 'Bottom 2' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_3', 'column_1_of_4', 'Bottom 3' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_4', 'column_1_of_4', 'Bottom 4' ) . '<?php ' );
		    }
		    // 3 active: 4 cases
		    if ( $bottom_1_is_active && $bottom_2_is_active && $bottom_3_is_active && !$bottom_4_is_active ) {
			eval( '?>' . get_column( 'bottom_1', 'column_1_of_3', 'Bottom 1' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_2', 'column_1_of_3', 'Bottom 2' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_3', 'column_1_of_3', 'Bottom 3' ) . '<?php ' );
		    }
		    if ( $bottom_1_is_active && $bottom_2_is_active && !$bottom_3_is_active && $bottom_4_is_active ) {
			eval( '?>' . get_column( 'bottom_1', 'column_1_of_3', 'Bottom 1' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_2', 'column_1_of_3', 'Bottom 2' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_4', 'column_1_of_3', 'Bottom 4' ) . '<?php ' );
		    }
		    if ( $bottom_1_is_active && !$bottom_2_is_active && $bottom_3_is_active && $bottom_4_is_active ) {
			eval( '?>' . get_column( 'bottom_1', 'column_1_of_3', 'Bottom 1' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_3', 'column_1_of_3', 'Bottom 3' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_4', 'column_1_of_3', 'Bottom 4' ) . '<?php ' );
		    }
		    if ( !$bottom_1_is_active && $bottom_2_is_active && $bottom_3_is_active && $bottom_4_is_active ) {
			eval( '?>' . get_column( 'bottom_2', 'column_1_of_3', 'Bottom 2' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_3', 'column_1_of_3', 'Bottom 3' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_4', 'column_1_of_3', 'Bottom 4' ) . '<?php ' );
		    }
		    // 2 active: 6 cases
		    if ( $bottom_1_is_active && $bottom_2_is_active && !$bottom_3_is_active && !$bottom_4_is_active ) {
			eval( '?>' . get_column( 'bottom_1', 'column_1_of_2', 'Bottom 1' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_2', 'column_1_of_2', 'Bottom 2' ) . '<?php ' );
		    }
		    if ( $bottom_1_is_active && !$bottom_2_is_active && $bottom_3_is_active && !$bottom_4_is_active ) {
			eval( '?>' . get_column( 'bottom_1', 'column_1_of_2', 'Bottom 1' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_3', 'column_1_of_2', 'Bottom 3' ) . '<?php ' );
		    }
		    if ( !$bottom_1_is_active && $bottom_2_is_active && $bottom_3_is_active && !$bottom_4_is_active ) {
			eval( '?>' . get_column( 'bottom_2', 'column_1_of_2', 'Bottom 2' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_3', 'column_1_of_2', 'Bottom 3' ) . '<?php ' );
		    }
		    if ( !$bottom_1_is_active && $bottom_2_is_active && !$bottom_3_is_active && $bottom_4_is_active ) {
			eval( '?>' . get_column( 'bottom_2', 'column_1_of_2', 'Bottom 2' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_4', 'column_1_of_2', 'Bottom 4' ) . '<?php ' );
		    }
		    if ( !$bottom_1_is_active && !$bottom_2_is_active && $bottom_3_is_active && $bottom_4_is_active ) {
			eval( '?>' . get_column( 'bottom_3', 'column_1_of_2', 'Bottom 3' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_4', 'column_1_of_2', 'Bottom 4' ) . '<?php ' );
		    }
		    if ( $bottom_1_is_active && !$bottom_2_is_active && !$bottom_3_is_active && $bottom_4_is_active ) {
			eval( '?>' . get_column( 'bottom_1', 'column_1_of_2', 'Bottom 1' ) . '<?php ' );
			eval( '?>' . get_column( 'bottom_4', 'column_1_of_2', 'Bottom 4' ) . '<?php ' );
		    }
		    // 1 active: 4 cases
		    if ( $bottom_1_is_active && !$bottom_2_is_active && !$bottom_3_is_active && !$bottom_4_is_active ) {
			eval( '?>' . get_column( 'bottom_1', 'column_1_of_1', 'Bottom 1' ) . '<?php ' );
		    }
		    if ( !$bottom_1_is_active && $bottom_2_is_active && !$bottom_3_is_active && !$bottom_4_is_active ) {
			eval( '?>' . get_column( 'bottom_2', 'column_1_of_1', 'Bottom 2' ) . '<?php ' );
		    }
		    if ( !$bottom_1_is_active && !$bottom_2_is_active && $bottom_3_is_active && !$bottom_4_is_active ) {
			eval( '?>' . get_column( 'bottom_3', 'column_1_of_1', 'Bottom 3' ) . '<?php ' );
		    }
		    if ( !$bottom_1_is_active && !$bottom_2_is_active && !$bottom_3_is_active && $bottom_4_is_active ) {
			eval( '?>' . get_column( 'bottom_4', 'column_1_of_1', 'Bottom 4' ) . '<?php ' );
		    }
?>
		</div>
		<!-- end bottom -->
	    </div>
	    <!-- end bottom-bg -->

	    <div class="clear"></div>


<?php	endif; ?>


	<div id="footer-bg">
		<div id="footer" class="container_24 footer-top">
		    <div id="footer_text" class="grid_21">
			<p>
<?php			    echo $qualifire_options['copyright_message'];
			    if( $qualifire_options['show_wp_link_in_footer'] ) : 
				_e(' foi desenvolvido por <a href="mailto:jason@energiabalneario.com.br"><strong>Cumprido</strong></a>', 'qualifire');
			    endif;
			    if( $qualifire_options['show_entries_rss_in_footer'] ) : ?>
				| <a href="<?php bloginfo('rss2_url'); ?>"><?php esc_html_e('Entries (RSS)', 'qualifire'); ?></a>
<?php			    endif;
			    if( $qualifire_options['show_comments_rss_in_footer'] ) : ?>
				| <a href="<?php bloginfo('comments_rss2_url'); ?>"><?php esc_html_e('Comments (RSS)', 'qualifire'); ?></a>
<?php			    endif; ?>
			</p>
		    </div>
		    <div class="back-to-top">
			<a href="#top"><?php esc_html_e('Topo', 'qualifire'); ?></a>
		    </div>
		</div>
	</div>

	<div class="clear"></div>

	<?php wp_footer(); ?>
    </div><!-- end wrapper-1 -->
  <script type="text/javascript"> Cufon.now(); </script>
  <?php echo $qualifire_options['google_analaytics']; ?>
</body>
</html>