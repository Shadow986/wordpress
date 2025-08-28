<?php

class NewsEventsLicenseManager {
    
    private $license_server = 'https://your-license-server.com/api/';
    private $plugin_slug = 'news-events-elementor-widgets';
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_license_page']);
        add_action('admin_init', [$this, 'check_license_status']);
        add_action('wp_ajax_activate_license', [$this, 'activate_license']);
        add_action('wp_ajax_deactivate_license', [$this, 'deactivate_license']);
    }
    
    public function is_licensed() {
        $license_key = get_option('news_events_license_key');
        $license_status = get_option('news_events_license_status');
        
        return !empty($license_key) && $license_status === 'valid';
    }
    
    public function add_license_page() {
        add_options_page(
            'News & Events License',
            'News & Events License',
            'manage_options',
            'news-events-license',
            [$this, 'license_page']
        );
    }
    
    public function license_page() {
        $license_key = get_option('news_events_license_key');
        $license_status = get_option('news_events_license_status');
        ?>
        <div class="wrap">
            <h1>News & Events License</h1>
            
            <?php if ($license_status === 'valid'): ?>
                <div class="notice notice-success">
                    <p><strong>License Active:</strong> Your license is valid and active.</p>
                </div>
            <?php else: ?>
                <div class="notice notice-error">
                    <p><strong>License Required:</strong> Please enter a valid license key to use this plugin.</p>
                </div>
            <?php endif; ?>
            
            <form method="post" action="">
                <?php wp_nonce_field('news_events_license_nonce', 'news_events_license_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">License Key</th>
                        <td>
                            <input type="text" 
                                   name="license_key" 
                                   value="<?php echo esc_attr($license_key); ?>" 
                                   class="regular-text"
                                   placeholder="Enter your license key">
                        </td>
                    </tr>
                </table>
                
                <?php if ($license_status === 'valid'): ?>
                    <input type="submit" name="deactivate_license" class="button-secondary" value="Deactivate License">
                <?php else: ?>
                    <input type="submit" name="activate_license" class="button-primary" value="Activate License">
                <?php endif; ?>
            </form>
            
            <hr>
            <h3>Get Your License</h3>
            <p>Purchase a license key at: <a href="https://your-website.com/purchase" target="_blank">your-website.com/purchase</a></p>
        </div>
        <?php
    }
    
    public function check_license_status() {
        if (!isset($_POST['news_events_license_nonce']) || 
            !wp_verify_nonce($_POST['news_events_license_nonce'], 'news_events_license_nonce')) {
            return;
        }
        
        if (isset($_POST['activate_license'])) {
            $this->activate_license_key($_POST['license_key']);
        }
        
        if (isset($_POST['deactivate_license'])) {
            $this->deactivate_license_key();
        }
    }
    
    private function activate_license_key($license_key) {
        $response = wp_remote_post($this->license_server . 'activate', [
            'body' => [
                'license_key' => sanitize_text_field($license_key),
                'plugin_slug' => $this->plugin_slug,
                'site_url' => home_url()
            ],
            'timeout' => 15
        ]);
        
        if (is_wp_error($response)) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>License server connection failed.</p></div>';
            });
            return;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if ($data['success']) {
            update_option('news_events_license_key', $license_key);
            update_option('news_events_license_status', 'valid');
            update_option('news_events_license_expires', $data['expires']);
            
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success"><p>License activated successfully!</p></div>';
            });
        } else {
            update_option('news_events_license_status', 'invalid');
            add_action('admin_notices', function() use ($data) {
                echo '<div class="notice notice-error"><p>License activation failed: ' . esc_html($data['message']) . '</p></div>';
            });
        }
    }
    
    private function deactivate_license_key() {
        $license_key = get_option('news_events_license_key');
        
        wp_remote_post($this->license_server . 'deactivate', [
            'body' => [
                'license_key' => $license_key,
                'plugin_slug' => $this->plugin_slug,
                'site_url' => home_url()
            ]
        ]);
        
        delete_option('news_events_license_key');
        delete_option('news_events_license_status');
        delete_option('news_events_license_expires');
        
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success"><p>License deactivated.</p></div>';
        });
    }
    
    public function show_license_notice() {
        if (!$this->is_licensed()) {
            echo '<div class="notice notice-error">
                <p><strong>News & Events Plugin:</strong> 
                <a href="' . admin_url('options-general.php?page=news-events-license') . '">License activation required</a> to use this plugin.</p>
            </div>';
        }
    }
}
