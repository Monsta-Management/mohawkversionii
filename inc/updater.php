<?php
/**
 * Mohawk V2 Theme GitHub Updater
 *
 * Uses the YahnisElsts Plugin Update Checker library to automatically
 * detect and pull updates from a specified GitHub repository.
 *
 * @package Mohawk_V2
 * @since 2.0.0
 */

defined( 'ABSPATH' ) || exit;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

add_action( 'after_setup_theme', function() {

	// Only include PUC if it's not already loaded by a plugin.
    if ( ! class_exists( 'YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
		$puc_path = get_template_directory() . '/helpers/plugin-update-checker/plugin-update-checker.php';

		if ( ! file_exists( $puc_path ) ) {
			add_action( 'admin_notices', function () {
				echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Mohawk V2 Theme', 'mohawkversionii' ) . ':</strong> '
					. esc_html__( 'Missing the plugin-update-checker library. Automatic updates will be disabled.', 'mohawkversionii' )
					. '</p></div>';
			} );

			return;
		}

		require_once $puc_path;
	}

	// Initialize GitHub updater.
	$theme_updater = PucFactory::buildUpdateChecker(
		'https://github.com/Monsta-Management/mohawkversionii',
		get_template_directory(),
		'mohawkversionii'
	);

	$theme_updater->setBranch( 'stable' );

	// Apply shared or local token.
	if ( ! function_exists( 'mohawk_theme_apply_github_token' ) ) {
		function mohawk_theme_apply_github_token( $updater ) {
			if ( function_exists( 'monsta_get_github_token' ) ) {
				$token = monsta_get_github_token();
			} else {
				$token = get_option( 'mohawk_theme_github_token', '' );
			}

			if ( ! empty( $token ) ) {
				$updater->setAuthentication( $token );
			}
		}
	}

	mohawk_theme_apply_github_token( $theme_updater );

}, 5 );

/**
 * Add theme GitHub token settings if Monsta Utility Tools Pack is not active
 */
add_action( 'admin_init', function() {

	if ( function_exists( 'monsta_get_github_token' ) ) {
		return;
	}

	add_settings_section(
		'mohawk_theme_github_section',
		esc_html__( 'Mohawk V2 Theme - GitHub Updates', 'mohawkversionii' ),
		function() {
			echo '<p>' . esc_html__( 'Provide a GitHub personal access token to receive theme updates (used only if Monsta Utility Tools Pack is not active).', 'mohawkversionii' ) . '</p>';
		},
		'general'
	);

	add_settings_field(
		'mohawk_theme_github_token',
		esc_html__( 'MONSTA_GITHUB_TOKEN', 'mohawkversionii' ),
		function() {
			$token = get_option( 'mohawk_theme_github_token', '' );
			echo '<input type="password" name="mohawk_theme_github_token" value="' . esc_attr( $token ) . '" class="regular-text" />';
			echo '<p class="description">' . esc_html__( 'Private GitHub token used for theme updates.', 'mohawkversionii' ) . '</p>';
		},
		'general',
		'mohawk_theme_github_section'
	);

	register_setting( 'general', 'mohawk_theme_github_token', [
		'type' => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default' => '',
	] );

	// Token expiry field.
	add_settings_field(
		'mohawk_theme_github_token_expiry',
		esc_html__( 'Expiry Date', 'mohawkversionii' ),
		function() {
			$expiry = get_option( 'mohawk_theme_github_token_expiry', '' );
			echo '<input type="date" name="mohawk_theme_github_token_expiry" value="' . esc_attr( $expiry ) . '" />';
			echo '<p class="description">' . esc_html__( 'Optional: Set the expiry date for your GitHub token. Used for tracking and renewal reminders.', 'mohawkversionii' ) . '</p>';
			
			echo '<hr style="margin: 20px 0;">';
			echo '<h4>Token Renewal Instructions</h4>';
			echo '<ol class="description" style="margin-left: 20px;">';
			echo '<li>Sign in to your GitHub account (<a href="https://github.com/johnjezonajias" target="_blank">johnjezonajias</a>).</li>';
			echo '<li>Go to <a href="https://github.com/settings/tokens?type=beta" target="_blank">Settings → Developer Settings → Fine-grained Tokens</a>.</li>';
			echo '<li>Click <strong>Generate new token</strong> and select the <strong>@Monsta-Management</strong> organization.</li>';
			echo '<li>Grant <strong>Read and Write</strong> access to <em>Actions</em> and <em>Code</em> for repositories used by Monsta Deployer.</li>';
			echo '<li>Set the expiry to 1 year (maximum allowed by GitHub).</li>';
			echo '<li>Copy the generated token and paste it into the field above.</li>';
			echo '<li>Update the expiry date field to match the new token’s expiry date.</li>';
			echo '</ol>';
			echo '<p class="description">After saving, the new token will immediately take effect for deployment authentication.</p>';
		},
		'general',
		'mohawk_theme_github_section'
	);

	register_setting( 'general', 'mohawk_theme_github_token_expiry', [
		'type' => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default' => '',
	] );

} );

/**
 * Show admin notice if GitHub token is expired or close to expiry
 * with renewal instructions
 */
add_action( 'admin_notices', function() {

	if ( function_exists( 'monsta_get_github_token' ) ) {
		return;
	}

	$expiry = get_option( 'mohawk_theme_github_token_expiry', '' );
	$token  = get_option( 'mohawk_theme_github_token', '' );

	if ( empty( $token ) || empty( $expiry ) ) {
		return;
	}

	$today = current_time( 'Y-m-d' );
	$days_remaining = ( strtotime( $expiry ) - strtotime( $today ) ) / DAY_IN_SECONDS;

	if ( $days_remaining <= 0 || $days_remaining <= 14 ) {

		$class = $days_remaining <= 0 ? 'notice-error' : 'notice-warning';
		$title = esc_html__( 'Mohawk V2 Theme GitHub Token', 'mohawkversionii' );

		$days_text = $days_remaining <= 0
			? esc_html__( 'expired', 'mohawkversionii' )
			: sprintf( esc_html__( 'will expire in %d days', 'mohawkversionii' ), intval( $days_remaining ) );

		$renew_instructions = '
			<p>' . esc_html__( 'Your GitHub token for theme updates has', 'mohawkversionii' ) . ' <strong>' . esc_html( $days_text ) . '</strong>.</p>
			<p>' . esc_html__( 'Please generate a new token to continue receiving updates.', 'mohawkversionii' ) . '</p>
			<h4>' . esc_html__( 'How to renew your GitHub token', 'mohawkversionii' ) . '</h4>
			<ol>
				<li>' . sprintf(
					esc_html__( 'Sign in to your GitHub account (%s).', 'mohawkversionii' ),
					'<a href="https://github.com/johnjezonajias" target="_blank">@johnjezonajias</a>'
				) . '</li>
				<li>' . esc_html__( 'Go to Settings → Developer Settings → Personal Access Tokens → Fine-grained tokens.', 'mohawkversionii' ) . '</li>
				<li>' . esc_html__( 'Click "Generate new token" and select the @Monsta-Management organization.', 'mohawkversionii' ) . '</li>
				<li>' . esc_html__( 'Grant "Read & Write" access to Code and Actions for repositories used by Monsta Deployer.', 'mohawkversionii' ) . '</li>
				<li>' . esc_html__( 'Set the expiry to 1 year (maximum allowed).', 'mohawkversionii' ) . '</li>
				<li>' . esc_html__( 'Copy the generated token and paste it into Settings → General → GitHub Token.', 'mohawkversionii' ) . '</li>
				<li>' . esc_html__( 'Update the expiry date field to match the new token’s expiry date.', 'mohawkversionii' ) . '</li>
			</ol>
		';

		echo '<div class="notice ' . esc_attr( $class ) . '"><p><strong>' . esc_html( $title ) . ':</strong></p>' . $renew_instructions . '</div>';
	}

} );
