<?php
/**
 * Settings Page
 *
 * @package SuperElemPro\Admin
 * @author  Rohit
 * @since   1.0.0
 */

namespace SuperElemPro\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Settings
 */
class Settings {

    /**
     * Available tabs.
     *
     * @var array
     */
    private array $tabs = [];

    /**
     * Constructor.
     */
    public function __construct() {
        $this->tabs = [
            'widgets'       => __( 'Widgets', 'super-elem-pro' ),
            'extensions'    => __( 'Extensions', 'super-elem-pro' ),
            'theme_builder' => __( 'Theme Builder', 'super-elem-pro' ),
            'dynamic_tags'  => __( 'Dynamic Tags', 'super-elem-pro' ),
            'woocommerce'   => __( 'WooCommerce', 'super-elem-pro' ),
            'developer'     => __( 'Developer', 'super-elem-pro' ),
        ];
    }

    /**
     * Render the full settings page.
     *
     * @return void
     */
    public function render(): void {

        $active_tab      = sanitize_key( $_GET['sep_tab'] ?? 'widgets' );
        $enabled_modules = get_option( 'sep_modules_enabled', sep_get_default_modules() );
        $all_modules     = \SuperElemPro\Core\Plugin::instance()->get_loader()->get_modules();

        ?>
        <div class="wrap sep-settings-page">

            <!-- Header -->
            <div class="sep-header">
                <h1>
                    <span class="dashicons dashicons-superhero"></span>
                    <?php esc_html_e( 'Super Elem Pro', 'super-elem-pro' ); ?>
                    <span class="sep-version">v<?php echo esc_html( SEP_VERSION ); ?></span>
                </h1>
                <p><?php esc_html_e( 'Personal Elementor Power-Up by Rohit', 'super-elem-pro' ); ?></p>
            </div>

            <!-- Saved notice -->
            <?php if ( isset( $_GET['settings-updated'] ) ) : ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php esc_html_e( 'Settings saved successfully!', 'super-elem-pro' ); ?></p>
                </div>
            <?php endif; ?>

            <!-- Tab navigation -->
            <nav class="nav-tab-wrapper sep-tabs">
                <?php foreach ( $this->tabs as $tab_key => $tab_label ) : ?>
                    <a href="<?php echo esc_url( add_query_arg( [ 'page' => 'super-elem-pro', 'sep_tab' => $tab_key ], admin_url( 'admin.php' ) ) ); ?>"
                       class="nav-tab <?php echo $active_tab === $tab_key ? 'nav-tab-active' : ''; ?>">
                        <?php echo esc_html( $tab_label ); ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <!-- Settings form -->
            <form method="post" action="options.php" class="sep-settings-form">
                <?php settings_fields( 'sep_settings_group' ); ?>

                <div class="sep-tab-content">
                    <?php $this->render_tab_modules( $active_tab, $all_modules, $enabled_modules ); ?>
                </div>

                <?php submit_button( __( 'Save Settings', 'super-elem-pro' ) ); ?>
            </form>

            <!-- Footer -->
            <div class="sep-footer">
                <p>
                    <?php
                    printf(
                        /* translators: %s: author name */
                        esc_html__( 'Super Elem Pro — Made with ❤️ by %s', 'super-elem-pro' ),
                        '<strong>Rohit</strong>'
                    );
                    ?>
                    | <?php esc_html_e( 'Personal Use Only', 'super-elem-pro' ); ?>
                </p>
            </div>

        </div>
        <?php
    }

    /**
     * Render module toggles for the active tab.
     *
     * @param string $tab             Active tab key.
     * @param array  $all_modules     All registered modules.
     * @param array  $enabled_modules Currently enabled modules.
     * @return void
     */
    private function render_tab_modules( string $tab, array $all_modules, array $enabled_modules ): void {

        $tab_modules = array_filter( $all_modules, fn( $m ) => ( $m['tab'] ?? '' ) === $tab );

        if ( empty( $tab_modules ) ) {
            echo '<p>' . esc_html__( 'No modules in this tab yet.', 'super-elem-pro' ) . '</p>';
            return;
        }

        echo '<div class="sep-modules-grid">';

        foreach ( $tab_modules as $key => $module ) {

            $is_enabled  = ! empty( $enabled_modules[ $key ] );
            $has_require = ! empty( $module['requires'] );
            $is_available = ! $has_require || ( function_exists( 'is_plugin_active' ) && is_plugin_active( $module['requires'] ) );

            ?>
            <div class="sep-module-card <?php echo $is_enabled ? 'sep-module-enabled' : ''; ?> <?php echo ! $is_available ? 'sep-module-unavailable' : ''; ?>">
                <div class="sep-module-icon">
                    <span class="dashicons <?php echo esc_attr( $module['icon'] ?? 'dashicons-admin-generic' ); ?>"></span>
                </div>
                <div class="sep-module-info">
                    <h3 class="sep-module-title"><?php echo esc_html( $module['label'] ); ?></h3>
                    <p class="sep-module-desc"><?php echo esc_html( $module['desc'] ); ?></p>
                    <?php if ( ! $is_available ) : ?>
                        <p class="sep-module-notice">
                            <?php
                            printf(
                                /* translators: %s: plugin file */
                                esc_html__( 'Requires %s to be active.', 'super-elem-pro' ),
                                '<code>' . esc_html( $module['requires'] ) . '</code>'
                            );
                            ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="sep-module-toggle">
                    <label class="sep-toggle">
                        <input
                            type="checkbox"
                            name="sep_modules_enabled[<?php echo esc_attr( $key ); ?>]"
                            value="1"
                            <?php checked( $is_enabled ); ?>
                            <?php disabled( ! $is_available ); ?>
                        />
                        <span class="sep-toggle-slider"></span>
                    </label>
                </div>
            </div>
            <?php
        }

        echo '</div>';
    }
}