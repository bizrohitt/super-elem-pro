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
        if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'super_elem_pro_nonce')) {
            wp_send_json_error(['message' => 'Invalid security token.']);
        }

        $form_id = isset($_POST['form_id']) ? intval($_POST['form_id']) : 0;
        $fields = isset($_POST['fields']) ? $_POST['fields'] : [];

        // In a real scenario we would fetch form settings here
        // For now, we execute default actions
        
        // 1. Save to database
        if (class_exists('\SuperElemPro\Modules\Forms\Actions\DatabaseAction')) {
            \SuperElemPro\Modules\Forms\Actions\DatabaseAction::save($form_id, $fields);
        }

        // 2. Send Email (using admin email by default as settings aren't loaded)
        if (class_exists('\SuperElemPro\Modules\Forms\Actions\EmailAction')) {
            \SuperElemPro\Modules\Forms\Actions\EmailAction::send($form_id, $fields, []);
        }

        wp_send_json_success(['message' => 'Sent successfully!']);
    }
}