<?php
/**
 * Viet Coffee - Professional Admin Experience
 * Enhanced settings page + recommendations
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Keep existing admin menu but enhance the page output
// The main menu registration stays in functions.php (or we can move it here)
// We improve the page rendering.

function viet_coffee_settings_page_enhanced() {
    $options = get_option( 'viet_coffee_options', array() );
    $featured_id = isset( $options['featured_product_id'] ) ? $options['featured_product_id'] : '';
    $imported = get_option( 'viet_coffee_demo_imported', false );
    $has_woo = class_exists( 'WooCommerce' );
    ?>
    <div class="wrap viet-coffee-admin">
        <h1 style="margin-bottom:8px;">☕ Viet Coffee Theme</h1>
        <p class="description" style="max-width:720px;">
            <?php esc_html_e( 'Professional landing page theme for Vietnamese coffee. Use the Theme Customizer for most design changes (fast & live).', 'viet-coffee' ); ?>
        </p>

        <div class="viet-coffee-cards" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:24px; margin-top:24px;">

            <!-- Quick Actions Card -->
            <div class="card" style="padding:24px; border:1px solid #e2e8f0; border-radius:12px; background:#fff;">
                <h2 style="margin-top:0; font-size:18px;">🚀 Quick Start</h2>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <a href="<?php echo admin_url( 'customize.php?return=' . urlencode( admin_url( 'admin.php?page=viet-coffee-settings' ) ) ); ?>" 
                       class="button button-primary button-hero" style="text-align:center; padding:14px; font-weight:600;">
                        <?php esc_html_e( 'Open Theme Customizer (Recommended)', 'viet-coffee' ); ?>
                    </a>
                    <p style="margin:4px 0 0; font-size:13px; color:#64748b;">
                        Edit hero, colors, homepage sections, testimonials, contact info with live preview.
                    </p>
                    <a href="<?php echo admin_url( 'nav-menus.php' ); ?>" class="button" style="text-align:center;">
                        <?php esc_html_e( 'Edit Primary Menu', 'viet-coffee' ); ?>
                    </a>
                </div>
            </div>

            <!-- Status Card -->
            <div class="card" style="padding:24px; border:1px solid #e2e8f0; border-radius:12px; background:#fff;">
                <h2 style="margin-top:0; font-size:18px;">📊 Status</h2>
                <ul style="margin:0; padding-left:18px; font-size:14px; line-height:1.7;">
                    <li>✅ WooCommerce: <strong><?php echo $has_woo ? 'Active' : '<span style="color:#b91c1c;">Not active (required)</span>'; ?></strong></li>
                    <li>✅ Demo Data: <strong><?php echo $imported ? 'Imported' : 'Not imported yet'; ?></strong></li>
                    <li>✅ Theme Version: <strong><?php echo wp_get_theme()->get( 'Version' ) ?: '1.0'; ?></strong></li>
                </ul>
            </div>

            <!-- Featured Product Card -->
            <div class="card" style="padding:24px; border:1px solid #e2e8f0; border-radius:12px; background:#fff;">
                <h2 style="margin-top:0; font-size:18px;">⭐ Featured Product</h2>
                <form method="post" action="options.php" style="margin-top:8px;">
                    <?php
                    settings_fields( 'viet_coffee_settings' );
                    do_settings_sections( 'viet-coffee-settings' );
                    ?>
                    <table class="form-table" style="margin:0;">
                        <tr>
                            <td style="padding:0;">
                                <input type="number" name="viet_coffee_options[featured_product_id]" value="<?php echo esc_attr( $featured_id ); ?>" 
                                       class="regular-text" placeholder="e.g. 123" style="width:100%; max-width:180px;">
                                <p class="description" style="margin-top:4px;">
                                    <?php esc_html_e( 'Product ID shown in the Featured section on homepage.', 'viet-coffee' ); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button( __( 'Save Featured Product', 'viet-coffee' ), 'secondary', 'submit', false ); ?>
                </form>
            </div>

            <!-- Signature Selection Text Card -->
            <?php
            $sig_options = get_option( 'viet_coffee_options', array() );
            $sig_text    = isset( $sig_options['signature_text'] ) ? $sig_options['signature_text'] : '';
            ?>
            <div class="card" style="padding:24px; border:1px solid #e2e8f0; border-radius:12px; background:#fff;">
                <h2 style="margin-top:0; font-size:18px;">✍️ Signature Selection Text</h2>
                <form method="post" action="options.php" style="margin-top:8px;">
                    <?php settings_fields( 'viet_coffee_settings' ); ?>
                    <table class="form-table" style="margin:0;">
                        <tr>
                            <td style="padding:0;">
                                <textarea name="viet_coffee_options[signature_text]" rows="5"
                                          class="large-text" style="width:100%;"
                                          placeholder="<?php esc_attr_e( 'Enter the description text for the Signature Selection section…', 'viet-coffee' ); ?>"><?php echo esc_textarea( $sig_text ); ?></textarea>
                                <p class="description" style="margin-top:4px;">
                                    <?php esc_html_e( 'This text is displayed in the Signature Selection section on the homepage.', 'viet-coffee' ); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button( __( 'Save Signature Text', 'viet-coffee' ), 'secondary', 'submit', false ); ?>
                </form>
            </div>

            <!-- Story Settings Card -->
            <?php
            $story_options = get_option( 'viet_coffee_options', array() );
            $story_image   = isset( $story_options['story_image'] ) ? $story_options['story_image'] : '';
            $story_content = isset( $story_options['story_content'] ) ? $story_options['story_content'] : '';
            ?>
            <div class="card" style="padding:24px; border:1px solid #e2e8f0; border-radius:12px; background:#fff; grid-column: span 1 / -1;">
                <h2 style="margin-top:0; font-size:18px;">📖 Story Section Settings</h2>
                <form method="post" action="options.php" style="margin-top:8px;">
                    <?php settings_fields( 'viet_coffee_settings' ); ?>
                    <table class="form-table" style="margin:0;">
                        <!-- Story Image -->
                        <tr>
                            <th scope="row" style="padding:12px 0 12px 0; width:200px;"><label><?php esc_html_e( 'Story Image', 'viet-coffee' ); ?></label></th>
                            <td style="padding:12px 0;">
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <?php if ( $story_image ) : ?>
                                        <img src="<?php echo esc_url( $story_image ); ?>" style="max-width:120px; height:auto; border-radius:6px; border:1px solid #e2e8f0;">
                                    <?php endif; ?>
                                    <input type="hidden" id="story_image" name="viet_coffee_options[story_image]" value="<?php echo esc_attr( $story_image ); ?>">
                                    <button type="button" class="button" id="upload_story_image_btn"><?php esc_html_e( 'Upload / Choose Image', 'viet-coffee' ); ?></button>
                                </div>
                                <p class="description" style="margin-top:8px;"><?php esc_html_e( 'Recommended size: 1200x900px. This image appears on the left side of the Story section.', 'viet-coffee' ); ?></p>
                            </td>
                        </tr>

                        <!-- Story Content -->
                        <tr>
                            <th scope="row" style="padding:12px 0;"><label for="story_content"><?php esc_html_e( 'Story Content', 'viet-coffee' ); ?></label></th>
                            <td style="padding:12px 0;">
                                <textarea id="story_content" name="viet_coffee_options[story_content]" rows="6"
                                          class="large-text" style="width:100%;"
                                          placeholder="<?php esc_attr_e( 'Enter your story description here…', 'viet-coffee' ); ?>"><?php echo esc_textarea( $story_content ); ?></textarea>
                                <p class="description" style="margin-top:8px;"><?php esc_html_e( 'This text appears on the right side of the Story section. You can use multiple lines.', 'viet-coffee' ); ?></p>
                            </td>
                        </tr>

                        <!-- Story Highlights -->
                        <?php
                        $highlight_labels = array(
                            'highlight_1' => __( 'First Highlight', 'viet-coffee' ),
                            'highlight_2' => __( 'Second Highlight', 'viet-coffee' ),
                            'highlight_3' => __( 'Third Highlight', 'viet-coffee' ),
                        );
                        foreach ( $highlight_labels as $key => $label ) :
                            $num = substr( $key, -1 );
                            $emoji = isset( $story_options[ "{$key}_emoji" ] ) ? $story_options[ "{$key}_emoji" ] : '';
                            $title = isset( $story_options[ "{$key}_title" ] ) ? $story_options[ "{$key}_title" ] : '';
                            $desc  = isset( $story_options[ "{$key}_desc" ] ) ? $story_options[ "{$key}_desc" ] : '';
                        ?>
                        <tr>
                            <th scope="row" style="padding:12px 0;"><label><?php echo esc_html( $label ); ?></label></th>
                            <td style="padding:12px 0;">
                                <div style="display:grid; grid-template-columns:100px 1fr; gap:12px;">
                                    <input type="text" name="viet_coffee_options[<?php echo esc_attr( $key ); ?>_emoji]" value="<?php echo esc_attr( $emoji ); ?>"
                                           class="small-text" style="width:100%;" placeholder="🌱" maxlength="10">
                                    <input type="text" name="viet_coffee_options[<?php echo esc_attr( $key ); ?>_title]" value="<?php echo esc_attr( $title ); ?>"
                                           class="regular-text" style="width:100%;" placeholder="<?php esc_attr_e( 'Highlight Title', 'viet-coffee' ); ?>">
                                </div>
                                <textarea name="viet_coffee_options[<?php echo esc_attr( $key ); ?>_desc]" rows="2" class="large-text" 
                                          style="width:100%; margin-top:8px;"
                                          placeholder="<?php esc_attr_e( 'Highlight description…', 'viet-coffee' ); ?>"><?php echo esc_textarea( $desc ); ?></textarea>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php submit_button( __( 'Save Story Settings', 'viet-coffee' ), 'primary', 'submit', false ); ?>
                </form>
            </div>

            <!-- Demo Import Card -->
            <div class="card" style="padding:24px; border:1px solid #4A2C1A; border-radius:12px; background:#fffaf0; grid-column: span 1 / -1;">
                <h2 style="margin-top:4px; font-size:18px; color:#4A2C1A;">📦 One-Click Demo Import</h2>
                <p style="max-width:720px; margin-bottom:16px;">
                    <?php esc_html_e( 'Imports 6 beautiful sample products, navigation menu, story page, and sets hero content. Perfect for new sites.', 'viet-coffee' ); ?>
                </p>

                <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
                    <button type="button" id="viet-coffee-import-btn" class="button button-primary button-large">
                        <?php echo $imported ? esc_html__( 'Re-import / Add Missing Data', 'viet-coffee' ) : esc_html__( 'Import Demo Products + Menu + Story', 'viet-coffee' ); ?>
                    </button>
                    <button type="button" id="viet-coffee-reset-btn" class="button">
                        <?php esc_html_e( 'Reset Demo Data', 'viet-coffee' ); ?>
                    </button>
                    <span id="viet-coffee-import-status" style="margin-left:8px; font-weight:500;"></span>
                </div>
                <p style="font-size:12px; color:#666; margin-top:12px;">
                    <?php esc_html_e( 'You can safely re-run the importer. Existing demo SKUs are skipped on re-import.', 'viet-coffee' ); ?>
                </p>
            </div>

        </div>

        <div style="margin-top:28px; font-size:13px; color:#64748b;">
            <?php esc_html_e( 'Pro Tip: Most visual changes are done via', 'viet-coffee' ); ?>
            <strong><a href="<?php echo admin_url( 'customize.php' ); ?>">Appearance → Customize</a></strong>.
            <?php esc_html_e( 'Changes are live previewed and easy to tweak.', 'viet-coffee' ); ?>
        </div>
    </div>

    <script>
    (function($){
        function showStatus(msg, isError) {
            const $el = $('#viet-coffee-import-status');
            $el.html('<span style="color:' + (isError ? '#b91c1c' : '#166534') + '; font-weight:600;">' + msg + '</span>');
        }

        $('#viet-coffee-import-btn').on('click', function(){
            const $btn = $(this);
            $btn.prop('disabled', true).text('<?php echo esc_js( __( 'Importing…', 'viet-coffee' ) ); ?>');
            showStatus('<?php echo esc_js( __( 'Importing demo data...', 'viet-coffee' ) ); ?>');

            $.post(ajaxurl, {
                action: 'viet_coffee_import_demo',
                nonce: '<?php echo wp_create_nonce( "viet_coffee_demo_import" ); ?>'
            }, function(res){
                $btn.prop('disabled', false).text('<?php echo esc_js( __( 'Re-Import / Add Missing Data', 'viet-coffee' ) ); ?>');
                if (res.success) {
                    showStatus( res.data || '<?php echo esc_js( __( '✅ Demo data imported successfully!', 'viet-coffee' ) ); ?>' );
                    setTimeout(function(){ location.reload(); }, 1600);
                } else {
                    showStatus( '❌ ' + (res.data || 'Import failed.'), true );
                }
            }).fail(function(){
                $btn.prop('disabled', false).text('<?php echo esc_js( __( 'Re-Import / Add Missing Data', 'viet-coffee' ) ); ?>');
                showStatus('❌ Network error. Please try again.', true);
            });
        });

        $('#viet-coffee-reset-btn').on('click', function(){
            if (!confirm('<?php echo esc_js( __( 'This will delete demo products, story page and menu. Continue?', 'viet-coffee' ) ); ?>')) return;

            const $btn = $(this);
            $btn.prop('disabled', true);
            showStatus('<?php echo esc_js( __( 'Resetting...', 'viet-coffee' ) ); ?>');

            $.post(ajaxurl, {
                action: 'viet_coffee_reset_demo',
                nonce: '<?php echo wp_create_nonce( "viet_coffee_demo_import" ); ?>'
            }, function(res){
                $btn.prop('disabled', false);
                if (res.success) {
                    showStatus( res.data || '<?php echo esc_js( __( 'Demo data removed.', 'viet-coffee' ) ); ?>' );
                    setTimeout(function(){ location.reload(); }, 1200);
                } else {
                    showStatus( '❌ ' + (res.data || 'Reset failed.'), true );
                }
            }).fail(function(){
                $btn.prop('disabled', false);
                showStatus('❌ Network error during reset.', true);
            });
        });
    })(jQuery);
    </script>

    <style>
        .viet-coffee-admin .card { box-shadow: 0 1px 3px rgb(15 23 42 / 0.08); }
        .viet-coffee-admin .button-hero { height: auto; line-height: 1.1; }
    </style>

    <script>
    (function($){
        // Media Upload for Story Image
        let story_image_frame;
        $('#upload_story_image_btn').on('click', function(e){
            e.preventDefault();
            
            if (story_image_frame) {
                story_image_frame.open();
                return;
            }

            story_image_frame = wp.media({
                title: '<?php echo esc_js( __( 'Select Story Image', 'viet-coffee' ) ); ?>',
                button: { text: '<?php echo esc_js( __( 'Use Image', 'viet-coffee' ) ); ?>' },
                multiple: false
            });

            story_image_frame.on('select', function(){
                const attachment = story_image_frame.state().get('selection').first().toJSON();
                $('#story_image').val(attachment.url);
                
                // Update preview
                const preview = '<img src="' + attachment.url + '" style="max-width:120px; height:auto; border-radius:6px; border:1px solid #e2e8f0; margin-bottom:8px;">';
                $('#upload_story_image_btn').before('<div style="margin-bottom:8px;">' + preview + '</div>');
            });

            story_image_frame.open();
        });
    })(jQuery);
    </script>
    <?php
}

// Replace the old settings page with enhanced version (hook after menu)
function viet_coffee_replace_settings_page() {
    // Remove old page function if needed. We override by re-registering the callback.
}
add_action( 'admin_menu', function() {
    // Re-add with better callback (the original is still in functions.php, this overrides display)
    remove_menu_page( 'viet-coffee-settings' );
    add_menu_page(
        'Viet Coffee',
        'Viet Coffee',
        'manage_options',
        'viet-coffee-settings',
        'viet_coffee_settings_page_enhanced',
        'dashicons-coffee',
        60
    );
}, 99 );

// Keep the settings registration (for featured product)
function viet_coffee_settings_init_enhanced() {
    register_setting( 'viet_coffee_settings', 'viet_coffee_options' );
}
add_action( 'admin_init', 'viet_coffee_settings_init_enhanced' );
