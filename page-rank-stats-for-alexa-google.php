<?php
/*  Copyright 2015 ITslum SOLUTIONS, Inc. 

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/*
Plugin Name: Page Rank Stats for Alexa and Google
Plugin URI: http://blog.itslum.com/2015/07/17/alexa-rank-plugin-for-wordpress/
Description: Show real time Alexa and or Google Page Rank of any webpage. Install then click activate and then go to Appearance > Widgets and look for 'Alexa Google Page Rank' widget. Then, drag the widget to area where you want to show rank statistics.
Version: 1.0
Author: ITslum SOLUTIONS
Author URI: http://solutions.itslum.com
*/

/**
 * Adds Alexa Google Page Rank widget.
 */
require_once 'gpagerank.php';
class agpr_ITslum extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'itsuniq_widget', // Base ID
			__('Alexa Google PageRank', 'text_domain'), // Name
			array( 'description' => __( 'Display Alexa and or Google Page Rank', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$alexatype = apply_filters( 'widget_title', $instance['alexatype'] );
		$googletype = apply_filters( 'widget_title', $instance['googletype'] );
		$itslumwd2title = apply_filters( 'widget_title', $instance['itslumwd2title'] );
		$alexastyle = apply_filters( 'widget_title', $instance['alexastyle'] );
		$googlestyle = apply_filters( 'widget_title', $instance['googlestyle'] );

		echo $args['before_widget'];
		if ( ! empty( $itslumwd2title) )
			echo $args['before_title'] . $itslumwd2title. $args['after_title'];

		?>
		<style>
		.itslum-pr{margin-bottom: 4px; padding: 5px;border: 1px solid #999999;box-sizing: border-box;}.prboxxix{width: 100%;height: 32px;box-sizing: border-box;border: 1px solid #999999;position: relative;}.prprogxix{background:green;width: 0%;height: 30px;box-sizing: border-box;}.prankxix{position: absolute;top: 2px;right: 4px;font-size: 26px;font-family: sans-serif;}
		</style>
		<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		    if ( empty( $alexatype) ){	
		    	$alexatype = $_SERVER['HTTP_HOST'];
			}
			else{
				$resultroy = stripos($alexatype,"http");
				if($resultroy === false){
				 $alexatype = "http://".$alexatype;
				}
				$parse = parse_url($alexatype);
				$alexatype = $parse['host'];
			}
			if ( empty( $googletype) ){
				$googletype = "http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
			}
			

		    if($alexastyle == 'standard'){
		    $xmlalx = simplexml_load_file('http://data.alexa.com/data?cli=10&dat=snbamz&url='.$alexatype);
			$rankalx=isset($xmlalx->SD[1]->POPULARITY)?$xmlalx->SD[1]->POPULARITY->attributes()->TEXT:0;
?>
<div class="itslum-pr">
<h4>Alexa Rank</h4>
<div class="prboxxix">
<div class="prprogxix" style="width: 100%; background: #007DED;"></div>
<span class="prankxix" style="right: auto; left: 5px; color: white;"><?php echo $rankalx;?></span>
</div>
</div>
<?php
		    }

		    else if($alexastyle == 'small'){
		    	?>
		    	<a href="http://www.alexa.com/siteinfo/<?php echo $alexatype; ?>"><script type="text/javascript" src="http://xslt.alexa.com/site_stats/js/t/a?url=<?php echo $alexatype; ?>"></script></a>
		    	<?php
		    }

		    else if($alexastyle == 'medium'){
		    	?>
		    	<a href="http://www.alexa.com/siteinfo/<?php echo $alexatype; ?>"><script type="text/javascript" src="http://xslt.alexa.com/site_stats/js/s/a?url=yoursite.com"></script></a>
		    	<?php
		    }

		    else if($alexastyle == 'vertical'){
		    	?>
		    	<a href="http://www.alexa.com/siteinfo/<?php echo $alexatype; ?>"><script type="text/javascript" src="http://xslt.alexa.com/site_stats/js/s/b?url=<?php echo $alexatype; ?>"></script></a>
		    	<?php
		    }		    
		    else if($alexastyle == 'disabled'){
		    }


		    if($googlestyle == 'standard'){
			$pr = new PR();
			$rankxixlast = $pr->get_google_pagerank($googletype);
			?>
<div class="itslum-pr">
<h4>Google Page Rank</h4>
<div class="prboxxix">
<div class="prprogxix" style="width: <?php echo $rankxixlast*10;?>%;"></div>
<span class="prankxix"><?php echo $rankxixlast; ?>/10</span>
</div>
</div>
			<?php
		    }
		    else if($googlestyle == 'disabled'){
		    	if($alexastyle == 'disabled'){echo "Enable atleast one of Alexa or Google page rank style.";}
		    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'alexatype' ] ) ) {
			$alexatype = $instance[ 'alexatype' ];
		}else{
			$alexatype = "example.com";
		}
		if ( isset( $instance[ 'googletype' ] ) ) {
			$googletype = $instance[ 'googletype' ];
		}else{
			$googletype = "http://example.com";
		}
		if ( isset( $instance[ 'alexastyle' ] ) ) {
			$alexastyle = $instance[ 'alexastyle' ];
		}else{
			$alexastyle = "standard";
		}
		if ( isset( $instance[ 'googlestyle' ] ) ) {
			$googlestyle = $instance[ 'googlestyle' ];
		}else{
			$googlestyle = "standard";
		}
		if ( isset( $instance[ 'itslumwd2title' ] ) ) {
			$mytitle = $instance[ 'itslumwd2title' ];
		}else{
			$mytitle = "Website Stats";
		}
		?>
	         <p>
			<label for="<?php echo $this->get_field_id( 'itslumwd2title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'itslumwd2title' ); ?>" name="<?php echo $this->get_field_name( 'itslumwd2title' ); ?>" class="widefat" style="width:100%;" type="text" value="<?php echo $mytitle; ?>"/>
			<br/><br/>
			<label for="<?php echo $this->get_field_id( 'alexatype' ); ?>">Alexa Rank For:</label>
			<input id="<?php echo $this->get_field_id( 'alexatype' ); ?>" name="<?php echo $this->get_field_name( 'alexatype' ); ?>" class="widefat" style="width:100%;" type="text" value="<?php echo $alexatype; ?>" placeholder="http://example.com"/>

			<label for="<?php echo $this->get_field_id( 'alexastyle' ); ?>">Alexa Display Style:</label> 
			<select id="<?php echo $this->get_field_id( 'alexastyle' ); ?>" name="<?php echo $this->get_field_name( 'alexastyle' ); ?>" class="widefat" style="width:100%;">

				<option value="standard" <?php if ( 'standard' == $alexastyle ) echo 'selected="selected"'; ?>>Standard</option>
				<option value="small" <?php if ( 'small' == $alexastyle ) echo 'selected="selected"'; ?>>Small</option>
				<option value="medium" <?php if ( 'medium' == $alexastyle ) echo 'selected="selected"'; ?>>Medium</option>
				<option value="vertical" <?php if ( 'vertical' == $alexastyle ) echo 'selected="selected"'; ?>>Vertical</option>
				<option value="disabled" <?php if ( 'disabled' == $alexastyle ) echo 'selected="selected"'; ?>>Disabled</option>
			</select>
			<br/><br/>
			<label for="<?php echo $this->get_field_id( 'googletype' ); ?>">Google PageRank For:</label>
			<input id="<?php echo $this->get_field_id( 'googletype' ); ?>" name="<?php echo $this->get_field_name( 'googletype' ); ?>" class="widefat" style="width:100%;" type="text" value="<?php echo $googletype; ?>" placeholder="http://example.com"/>

			<label for="<?php echo $this->get_field_id( 'googlestyle' ); ?>">Google PageRank Style:</label> 
			<select id="<?php echo $this->get_field_id( 'googlestyle' ); ?>" name="<?php echo $this->get_field_name( 'googlestyle' ); ?>" class="widefat" style="width:100%;">
				<option value="standard" <?php if ( 'standard' == $googlestyle ) echo 'selected="selected"'; ?>>Standard</option>
				<option value="disabled" <?php if ( 'disabled' == $googlestyle ) echo 'selected="selected"'; ?>>Disabled</option>
			</select>

			</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['alexatype'] = ( ! empty( $new_instance['alexatype'] ) ) ? strip_tags( $new_instance['alexatype'] ) : '';
		$instance['googletype'] = ( ! empty( $new_instance['googletype'] ) ) ? strip_tags( $new_instance['googletype'] ) : '';
		$instance['alexastyle'] = ( ! empty( $new_instance['alexastyle'] ) ) ? strip_tags( $new_instance['alexastyle'] ) : '';
		$instance['googlestyle'] = ( ! empty( $new_instance['googlestyle'] ) ) ? strip_tags( $new_instance['googlestyle'] ) : '';
        
        $instance['itslumwd2title'] = ( ! empty( $new_instance['itslumwd2title'] ) ) ? strip_tags( $new_instance['itslumwd2title'] ) : '';
		return $instance;
	}

} // class agpr_ITslum

// register agpr_ITslum widget
function register_itsuniq_widget() {
    register_widget( 'agpr_ITslum' );
}
add_action( 'widgets_init', 'register_itsuniq_widget' );
?>
