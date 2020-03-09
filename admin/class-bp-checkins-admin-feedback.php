<?php
/**
 * Plugin review class.
 * Prompts users to give a review of the plugin on WordPress.org after a period of usage.
 *
 * Heavily based on code by Rhys Wynne
 * https://winwar.co.uk/2014/10/ask-wordpress-plugin-reviews-week/
 *
 * @package Bp_Checkins
 */

if ( ! class_exists( 'Bp_Checkins_Admin_Feedback' ) ) :

	/**
	 * The feedback.
	 */
	class Bp_Checkins_Admin_Feedback {

		/**
		 * Slug.
		 *
		 * @var string $slug
		 */
		private $slug;

		/**
		 * Name.
		 *
		 * @var string $name
		 */
		private $name;

		/**
		 * Time limit.
		 *
		 * @var string $time_limit
		 */
		private $time_limit;

		/**
		 * No Bug Option.
		 *
		 * @var string $nobug_option
		 */
		public $nobug_option;

		/**
		 * Activation Date Option.
		 *
		 * @var string $date_option
		 */
		public $date_option;

		/**
		 * Class constructor.
		 *
		 * @param string $args Arguments.
		 */
		public function __construct( $args ) {
			$this->slug = $args['slug'];
			$this->name = $args['name'];

			$this->date_option  = $this->slug . '_activation_date';
			$this->nobug_option = $this->slug . '_no_bug';

			if ( isset( $args['time_limit'] ) ) {
				$this->time_limit = $args['time_limit'];
			} else {
				$this->time_limit = WEEK_IN_SECONDS;
			}

			// Add actions.
			add_action( 'admin_init', array( $this, 'check_installation_date' ) );
			add_action( 'admin_init', array( $this, 'set_no_bug' ), 5 );
		}

		/**
		 * Seconds to words.
		 *
		 * @param string $seconds Seconds in time.
		 */
		public function seconds_to_words( $seconds ) {

			// Get the years.
			$years = ( intval( $seconds ) / YEAR_IN_SECONDS ) % 100;
			if ( $years > 1 ) {
				/* translators: Number of years */
				return sprintf( __( '%s years', 'bp-checkins' ), $years );
			} elseif ( $years > 0 ) {
				return __( 'a year', 'bp-checkins' );
			}

			// Get the weeks.
			$weeks = ( intval( $seconds ) / WEEK_IN_SECONDS ) % 52;
			if ( $weeks > 1 ) {
				/* translators: Number of weeks */
				return sprintf( __( '%s weeks', 'bp-checkins' ), $weeks );
			} elseif ( $weeks > 0 ) {
				return __( 'a week', 'bp-checkins' );
			}

			// Get the days.
			$days = ( intval( $seconds ) / DAY_IN_SECONDS ) % 7;
			if ( $days > 1 ) {
				/* translators: Number of days */
				return sprintf( __( '%s days', 'bp-checkins' ), $days );
			} elseif ( $days > 0 ) {
				return __( 'a day', 'bp-checkins' );
			}

			// Get the hours.
			$hours = ( intval( $seconds ) / HOUR_IN_SECONDS ) % 24;
			if ( $hours > 1 ) {
				/* translators: Number of hours */
				return sprintf( __( '%s hours', 'bp-checkins' ), $hours );
			} elseif ( $hours > 0 ) {
				return __( 'an hour', 'bp-checkins' );
			}

			// Get the minutes.
			$minutes = ( intval( $seconds ) / MINUTE_IN_SECONDS ) % 60;
			if ( $minutes > 1 ) {
				/* translators: Number of minutes */
				return sprintf( __( '%s minutes', 'bp-checkins' ), $minutes );
			} elseif ( $minutes > 0 ) {
				return __( 'a minute', 'bp-checkins' );
			}

			// Get the seconds.
			$seconds = intval( $seconds ) % 60;
			if ( $seconds > 1 ) {
				/* translators: Number of seconds */
				return sprintf( __( '%s seconds', 'bp-checkins' ), $seconds );
			} elseif ( $seconds > 0 ) {
				return __( 'a second', 'bp-checkins' );
			}
		}

		/**
		 * Check date on admin initiation and add to admin notice if it was more than the time limit.
		 */
		public function check_installation_date() {
			if ( ! get_site_option( $this->nobug_option ) || false === get_site_option( $this->nobug_option ) ) {
				add_site_option( $this->date_option, time() );

				// Retrieve the activation date.
				$install_date = get_site_option( $this->date_option );

				// If difference between install date and now is greater than time limit, then display notice.
				if ( ( time() - $install_date ) > $this->time_limit ) {
					add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
				}
			}
		}

		/**
		 * Display the admin notice.
		 */
		public function display_admin_notice() {
			$screen = get_current_screen();

			if ( isset( $screen->base ) && 'plugins' === $screen->base ) {
				$no_bug_url = wp_nonce_url( admin_url( '?' . $this->nobug_option . '=true' ), 'bp-checkins-feedback-nounce' );
				$time       = $this->seconds_to_words( time() - get_site_option( $this->date_option ) );
				?>

<style>
.notice.bp-checkins-notice {
	border-left-color: #008ec2 !important;
	padding: 20px;
}

.rtl .notice.bp-checkins-notice {
	border-right-color: #008ec2 !important;
}

.notice.notice.bp-checkins-notice .bp-checkins-notice-inner {
	display: table;
	width: 100%;
}

.notice.bp-checkins-notice .bp-checkins-notice-inner .bp-checkins-notice-icon,
.notice.bp-checkins-notice .bp-checkins-notice-inner .bp-checkins-notice-content,
.notice.bp-checkins-notice .bp-checkins-notice-inner .bp-checkins-install-now {
	display: table-cell;
	vertical-align: middle;
}

.notice.bp-checkins-notice .bp-checkins-notice-icon {
	color: #509ed2;
	font-size: 50px;
	width: 60px;
}

.notice.bp-checkins-notice .bp-checkins-notice-icon img {
	width: 64px;
}

.notice.bp-checkins-notice .bp-checkins-notice-content {
	padding: 0 40px 0 20px;
}

.notice.bp-checkins-notice p {
	padding: 0;
	margin: 0;
}

.notice.bp-checkins-notice h3 {
	margin: 0 0 5px;
}

.notice.bp-checkins-notice .bp-checkins-install-now {
	text-align: center;
}

.notice.bp-checkins-notice .bp-checkins-install-now .bp-checkins-install-button {
	padding: 6px 50px;
	height: auto;
	line-height: 20px;
}

.notice.bp-checkins-notice a.no-thanks {
	display: block;
	margin-top: 10px;
	color: #72777c;
	text-decoration: none;
}

.notice.bp-checkins-notice a.no-thanks:hover {
	color: #444;
}

@media (max-width: 767px) {

	.notice.notice.bp-checkins-notice .bp-checkins-notice-inner {
		display: block;
	}

	.notice.bp-checkins-notice {
		padding: 20px !important;
	}

	.notice.bp-checkins-noticee .bp-checkins-notice-inner {
		display: block;
	}

	.notice.bp-checkins-notice .bp-checkins-notice-inner .bp-checkins-notice-content {
		display: block;
		padding: 0;
	}

	.notice.bp-checkins-notice .bp-checkins-notice-inner .bp-checkins-notice-icon {
		display: none;
	}

	.notice.bp-checkins-notice .bp-checkins-notice-inner .bp-checkins-install-now {
		margin-top: 20px;
		display: block;
		text-align: left;
	}

	.notice.bp-checkins-notice .bp-checkins-notice-inner .no-thanks {
		display: inline-block;
		margin-left: 15px;
	}
}
</style>
			<div class="notice updated bp-checkins-notice">
				<div class="bp-checkins-notice-inner">
					<div class="bp-checkins-notice-icon">
						<img src="<?php echo BPCHK_PLUGIN_URL . 'admin/wbcom/assets/imgs/bp_chekins.png'; ?>" alt="<?php echo esc_attr__( 'BuddyPress Check-ins', 'bp-checkins' ); ?>" />
					</div>
					<div class="bp-checkins-notice-content">
						<h3><?php echo esc_html__( 'Are you enjoying BuddyPress Check-ins?', 'bp-checkins' ); ?></h3>
						<p>
							<?php /* translators: 1. Name */ ?>
							<?php printf( esc_html__( 'We hope you\'re enjoying %1$s! Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'bp-checkins' ), esc_html( $this->name ) ); ?>
						</p>
					</div>
					<div class="bp-checkins-install-now">
						<?php printf( '<a href="%1$s" class="button button-primary bp-checkins-install-button" target="_blank">%2$s</a>', esc_url( 'https://wordpress.org/support/plugin/bp-check-in/reviews/' ), esc_html__( 'Leave a Review', 'bp-checkins' ) ); ?>
						<a href="<?php echo esc_url( $no_bug_url ); ?>" class="no-thanks"><?php echo esc_html__( 'No thanks / I already have', 'bp-checkins' ); ?></a>
					</div>
				</div>
			</div>
				<?php
			}
		}

		/**
		 * Set the plugin to no longer bug users if user asks not to be.
		 */
		public function set_no_bug() {

			// Bail out if not on correct page.
			if ( ! isset( $_GET['_wpnonce'] ) || ( ! wp_verify_nonce( $_GET['_wpnonce'], 'bp-checkins-feedback-nounce' ) || ! is_admin() || ! isset( $_GET[ $this->nobug_option ] ) || ! current_user_can( 'manage_options' ) ) ) {
				return;
			}

			add_site_option( $this->nobug_option, true );
		}
	}
endif;

/*
* Instantiate the Bp_Checkins_Admin_Feedback class.
*/
new Bp_Checkins_Admin_Feedback(
	array(
		'slug'       => 'bp_checkins',
		'name'       => __( 'BuddyPress Check-ins', 'bp-checkins' ),
		'time_limit' => WEEK_IN_SECONDS,
	)
);
