<?php
/**
 * Plugin Name:       WooCommerce Billing Email Domain Warning
 * Plugin URI:        https://github.com/amirrezashf/WooCommerce-Billing-Email-Domain-Warning
 * Description:       Shows an admin warning on WooCommerce order edit pages when the billing email uses an uncommon or non-allowed domain.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Requires Plugins:  woocommerce
 * Author:            Amirreza Shayesteh Far
 * Author URI:        https://github.com/amirrezashf
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       woocommerce-billing-email-domain-warning
 * Domain Path:       /languages
 *
 * @package WooCommerceBillingEmailDomainWarning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Billing_Email_Domain_Warning' ) ) {
	/**
	 * Main plugin class.
	 */
	final class WC_Billing_Email_Domain_Warning {

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		private const VERSION = '1.0.0';

		/**
		 * Singleton instance.
		 *
		 * @var self|null
		 */
		private static $instance = null;

		/**
		 * Get singleton instance.
		 *
		 * @return self
		 */
		public static function instance(): self {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		private function __construct() {
			add_action( 'before_woocommerce_init', array( $this, 'declare_hpos_compatibility' ) );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'plugins_loaded', array( $this, 'init' ), 20 );
		}

		/**
		 * Declare WooCommerce HPOS compatibility.
		 *
		 * @return void
		 */
		public function declare_hpos_compatibility(): void {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
					'custom_order_tables',
					__FILE__,
					true
				);
			}
		}

		/**
		 * Load plugin translations.
		 *
		 * @return void
		 */
		public function load_textdomain(): void {
			load_plugin_textdomain(
				'woocommerce-billing-email-domain-warning',
				false,
				dirname( plugin_basename( __FILE__ ) ) . '/languages'
			);
		}

		/**
		 * Initialize plugin after WooCommerce is available.
		 *
		 * @return void
		 */
		public function init(): void {
			if ( ! class_exists( 'WooCommerce' ) ) {
				add_action( 'admin_notices', array( $this, 'render_missing_woocommerce_notice' ) );
				return;
			}

			add_action(
				'woocommerce_admin_order_data_after_billing_address',
				array( $this, 'render_billing_email_domain_warning' ),
				20,
				1
			);
		}

		/**
		 * Render admin notice when WooCommerce is missing.
		 *
		 * @return void
		 */
		public function render_missing_woocommerce_notice(): void {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			echo '<div class="notice notice-warning"><p>';
			echo esc_html__( 'WooCommerce Billing Email Domain Warning requires WooCommerce to be installed and active.', 'woocommerce-billing-email-domain-warning' );
			echo '</p></div>';
		}

		/**
		 * Render warning under billing address on WooCommerce order edit screen.
		 *
		 * @param mixed $order WooCommerce order object.
		 *
		 * @return void
		 */
		public function render_billing_email_domain_warning( $order ): void {
			if ( ! $order instanceof WC_Order ) {
				return;
			}

			if ( ! current_user_can( $this->get_required_capability() ) ) {
				return;
			}

			$email = $order->get_billing_email();

			if ( empty( $email ) || ! is_email( $email ) ) {
				return;
			}

			$domain = $this->extract_email_domain( $email );

			if ( '' === $domain ) {
				return;
			}

			if ( $this->is_allowed_domain( $domain ) ) {
				return;
			}

			printf(
				'<p class="wc-bedw-warning" style="%1$s">%2$s</p>',
				esc_attr( $this->get_inline_warning_style() ),
				esc_html( $this->get_warning_message() )
			);
		}

		/**
		 * Get required capability for viewing the warning.
		 *
		 * @return string
		 */
		private function get_required_capability(): string {
			/**
			 * Filters the required capability for viewing billing email domain warnings.
			 *
			 * @param string $capability Required capability.
			 */
			return (string) apply_filters( 'wc_bedw_required_capability', 'edit_shop_orders' );
		}

		/**
		 * Extract email domain.
		 *
		 * @param string $email Email address.
		 *
		 * @return string
		 */
		private function extract_email_domain( string $email ): string {
			$email = strtolower( trim( $email ) );
			$parts = explode( '@', $email );

			if ( 2 !== count( $parts ) || empty( $parts[1] ) ) {
				return '';
			}

			return sanitize_text_field( $parts[1] );
		}

		/**
		 * Check whether domain is allowed.
		 *
		 * @param string $domain Email domain.
		 *
		 * @return bool
		 */
		private function is_allowed_domain( string $domain ): bool {
			$domain = strtolower( trim( $domain ) );

			if ( '' === $domain ) {
				return false;
			}

			return in_array( $domain, $this->get_allowed_domains(), true );
		}

		/**
		 * Get allowed email domains.
		 *
		 * @return array<int,string>
		 */
		private function get_allowed_domains(): array {
			$domains = array(
				'yahoo.com',
				'outlook.com',
				'gmail.com',
				'icloud.com',
				'me.com',
				'hotmail.com',
				'ymail.com',
				'aol.com',
				'live.com',
				'chmail.ir',
				'mail.com',
				'yandex.com',
				'yahoo.ca',
				'rocketmail.com',
				'mail.ru',
				'proton.me',
				'msn.com',
				'yahoo.co.uk',
				'ut.ac.ir',
				'mac.com',
				'protonmail.com',
				'yahoo.de',
			);

			/**
			 * Filters allowed billing email domains.
			 *
			 * @param array<int,string> $domains Allowed email domains.
			 */
			$domains = (array) apply_filters( 'wc_bedw_allowed_domains', $domains );

			$domains = array_map(
				static function ( $domain ) {
					return strtolower( trim( (string) $domain ) );
				},
				$domains
			);

			$domains = array_filter( $domains );

			return array_values( array_unique( $domains ) );
		}

		/**
		 * Get warning message.
		 *
		 * @return string
		 */
		private function get_warning_message(): string {
			/**
			 * Filters billing email domain warning message.
			 *
			 * @param string $message Warning message.
			 */
			return (string) apply_filters(
				'wc_bedw_warning_message',
				__( '⚠️ ممکن است ایمیل برای کاربر ارسال نشود!', 'woocommerce-billing-email-domain-warning' )
			);
		}

		/**
		 * Get inline warning CSS.
		 *
		 * Kept inline intentionally because the output is a very small admin-only notice.
		 *
		 * @return string
		 */
		private function get_inline_warning_style(): string {
			/**
			 * Filters inline style for the warning message.
			 *
			 * @param string $style Inline CSS.
			 */
			return (string) apply_filters(
				'wc_bedw_warning_inline_style',
				'margin:6px 0 0;color:#b91c1c;font-size:16px;line-height:1.6;font-weight:500;'
			);
		}
	}
}

WC_Billing_Email_Domain_Warning::instance();
