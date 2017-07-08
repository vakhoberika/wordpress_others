<?php
/* 
This website template was front-page template for events website. one-page web application was for advertising clubing events in Georgia.
*/
?>
<?php get_header(); ?>

	<main role="main">
		<!-- section -->
		<section id="events">
			
			<script>
            $(function() {
              $( "#eventslist" ).accordion({
                collapsible: true,
				active: false,
				header: "> div > h3",
				//refresh: true,
              });
            });
            </script>

            <script>
		    var tag = document.createElement('script');
		    tag.src = "//www.youtube.com/iframe_api";
		    var firstScriptTag = document.getElementsByTagName('script')[0];
		    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
			</script>
			<script src="http://www.youtube.com/player_api"></script>
		    <script>
            function onYouTubeIframeAPIReady() {
		        var $ = jQuery;
		        var players = [];
		        $('iframe').filter(function(){return this.src.indexOf('http://www.youtube.com/') == 0}).each( function (k, v) {
		            if (!this.id) { this.id='embeddedvideoiframe' + k }
		            players.push(new YT.Player(this.id, {
		                events: {
		                    'onStateChange': function(event) {
		                        if (event.data == YT.PlayerState.PLAYING) {
		                            $.each(players, function(k, v) {
		                                if (this.getIframe().id != event.target.getIframe().id) {
		                                    this.pauseVideo();
		                                }
		                            });
		                        }
		                    }
		                }
		            }))
		        });
		    }
			</script>
		  
			<?php
			// The Query
			$args = array(
			   'post_status' => 'publish',
			   'post_type' => 'event',
			   
			   
			   'meta_key' => 'date',
			   'orderby' => 'meta_value_num',
			   'order' => 'DESC',
			   
			   'meta_query' => array(
				  array(
					'key' => 'featured_post',
					'value' => 1,
					'compare' => '='
				  )
				),
			);
			$the_query = new WP_Query( $args );
			
			// The Loop
			if ( $the_query->have_posts() ) {
				?>
                <div id="eventslist">
                <?php
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					?>
                    <div class="group"> 
                        <h3>
                        	<span class="date">
                                <?php
                                	$date = get_field( 'date' );
									echo date( "d F, Y", strtotime( $date ) );
								?>
                            </span>
                            <span class="title">
								<?php echo get_the_title(); ?>
                            </span>
                            <span class="person">
                            	<?php if( get_field('person') ): ?> 
								 - <?php echo get_field('person'); ?>
                                <?php endif; ?>
                            </span>
						</h3>
                        <div class="content">
                        	<div class="poster">
                            	<?php if( get_field( 'image' ) ): ?>
									<?php $image = get_field( 'image' ); ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/thumb.php?src=<?php echo $image; ?>&h=400&q=100&zc=1" />
                                <?php endif; ?>
                            </div>
                            <div class="video">
                            	<?php
									$youtube = get_field( 'youtube_link' );
									if( $youtube ) {
										preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $youtube, $matches);
									?>
                                    	<iframe width="600" height="400" src="//www.youtube.com/embed/<?php echo $matches[0]; ?>?enablejsapi=1" frameborder="0" allowfullscreen></iframe>
                                    <?php
									} elseif( get_field( 'embed_video' ) ) {
										$embed_video = get_field( 'embed_video' );
										echo html_entity_decode( $embed_video );
									} else {
									  //no video
									}
								?>
                            </div>
                            <div class="clear"></div>
                            <?php if( get_field( 'extra_info' ) ): ?>
                            <div class="extra">
                            	<?php echo get_field( 'extra_info' ); ?>
                                <br /><br />
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
				}
				?>
                </div>
                <?php
			} else {
				// no posts found
			}
			/* Restore original Post Data */
			wp_reset_postdata();
			?>

		</section>
		<!-- /section -->
	</main>

<?php get_footer(); ?>
