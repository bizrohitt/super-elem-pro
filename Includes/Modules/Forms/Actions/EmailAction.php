<?php
namespace SuperElemPro\Modules\Forms\Actions;

if (!defined('ABSPATH'))
    exit;

/**
 * Email Action
 * 
 * @since 1.0.0
 */
class EmailAction
{

    public static function send($form_id, $fields, $settings = [])
    {
        $to = !empty($settings['email_to']) ? sanitize_email($settings['email_to']) : get_option('admin_email');
        $subject = !empty($settings['email_subject']) ? sanitize_text_field($settings['email_subject']) : __('New Form Submission', 'super-elem-pro');

        $message = self::build_email_body($fields);

        $headers = ['Content-Type: text/html; charset=UTF-8'];

        // Add reply-to header if email field exists
        foreach ($fields as $field) {
            if ($field['type'] === 'email' && is_email($field['value'])) {
                $headers[] = 'Reply-To: ' . sanitize_email($field['value']);
                break;
            }
        }

        $sent = wp_mail($to, $subject, $message, $headers);

        do_action('sep_after_email_sent', $sent, $form_id, $fields);

        return $sent;
    }

    private static function build_email_body($fields)
    {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>

        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                }

                .email-wrapper {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }

                .email-header {
                    background: #0073aa;
                    color: #fff;
                    padding: 20px;
                    text-align: center;
                }

                .email-body {
                    background: #f9f9f9;
                    padding: 20px;
                }

                .field-row {
                    padding: 10px;
                    border-bottom: 1px solid #ddd;
                }

                .field-label {
                    font-weight: bold;
                    color: #0073aa;
                }

                .field-value {
                    margin-top: 5px;
                }
            </style>
        </head>

        <body>
            <div class="email-wrapper">
                <div class="email-header">
                    <h2><?php esc_html_e('New Form Submission', 'super-elem-pro'); ?></h2>
                </div>
                <div class="email-body">
                    <?php foreach ($fields as $field): ?>
                        <div class="field-row">
                            <div class="field-label"><?php echo esc_html($field['label']); ?>:</div>
                            <div class="field-value"><?php echo nl2br(esc_html($field['value'])); ?></div>
                        </div>
                    <?php endforeach; ?>

                    <div class="field-row">
                        <div class="field-label"><?php esc_html_e('Submitted On', 'super-elem-pro'); ?>:</div>
                        <div class="field-value"><?php echo date('F j, Y \a\t g:i a'); ?></div>
                    </div>
                </div>
            </div>
        </body>

        </html>
        <?php
        return ob_get_clean();
    }
}