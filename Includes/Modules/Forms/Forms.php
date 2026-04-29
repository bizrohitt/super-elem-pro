<?php
namespace SuperElemPro\Modules\Forms;

if (!defined('ABSPATH'))
    exit;

class Forms
{
    public function __construct()
    {
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('wp_ajax_sep_form_submit', [$this, 'handle_submission']);
        add_action('wp_ajax_nopriv_sep_form_submit', [$this, 'handle_submission']);
    }

    public function register_widgets($widgets_manager)
    {
        require_once(__DIR__ . '/Widgets/FormWidget.php');
        $widgets_manager->register(new Widgets\FormWidget());
    }

    public function handle_submission()
    {
        check_ajax_referer('super_elem_pro_nonce', 'nonce');

        $form_data = $_POST['fields'];
        // 1. Save to database
        // 2. Send Email
        // 3. Trigger Webhook

        wp_send_json_success(['message' => 'Sent successfully!']);
    }
}