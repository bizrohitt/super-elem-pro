<?php
namespace SuperElemPro\Modules\Forms\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit;

/**
 * Advanced Form Widget
 * 
 * @since 1.0.0
 */
class FormWidget extends Widget_Base
{

    public function get_name()
    {
        return 'sep-advanced-form';
    }

    public function get_title()
    {
        return __('Advanced Form', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-form-horizontal';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    public function get_keywords()
    {
        return ['form', 'contact', 'email', 'submit'];
    }

    protected function register_controls()
    {

        // Form Fields Section
        $this->start_controls_section(
            'section_form_fields',
            [
                'label' => __('Form Fields', 'super-elem-pro'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'field_type',
            [
                'label' => __('Type', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'text' => __('Text', 'super-elem-pro'),
                    'email' => __('Email', 'super-elem-pro'),
                    'textarea' => __('Textarea', 'super-elem-pro'),
                    'number' => __('Number', 'super-elem-pro'),
                    'tel' => __('Phone', 'super-elem-pro'),
                    'url' => __('URL', 'super-elem-pro'),
                    'select' => __('Select', 'super-elem-pro'),
                    'checkbox' => __('Checkbox', 'super-elem-pro'),
                    'radio' => __('Radio', 'super-elem-pro'),
                    'date' => __('Date', 'super-elem-pro'),
                    'time' => __('Time', 'super-elem-pro'),
                    'file' => __('File Upload', 'super-elem-pro'),
                    'hidden' => __('Hidden', 'super-elem-pro'),
                ],
                'default' => 'text',
            ]
        );

        $repeater->add_control(
            'field_label',
            [
                'label' => __('Label', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'field_name',
            [
                'label' => __('Field Name', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'description' => __('Used for form processing. Use lowercase and underscores.', 'super-elem-pro'),
            ]
        );

        $repeater->add_control(
            'field_placeholder',
            [
                'label' => __('Placeholder', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'field_type!' => ['checkbox', 'radio', 'hidden'],
                ],
            ]
        );

        $repeater->add_control(
            'field_required',
            [
                'label' => __('Required', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'field_type!' => 'hidden',
                ],
            ]
        );

        $repeater->add_control(
            'field_options',
            [
                'label' => __('Options', 'super-elem-pro'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
                'description' => __('One option per line', 'super-elem-pro'),
                'condition' => [
                    'field_type' => ['select', 'checkbox', 'radio'],
                ],
            ]
        );

        $repeater->add_control(
            'field_width',
            [
                'label' => __('Column Width', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '100' => '100%',
                    '50' => '50%',
                    '33' => '33%',
                    '25' => '25%',
                ],
                'default' => '100',
            ]
        );

        $repeater->add_control(
            'field_default_value',
            [
                'label' => __('Default Value', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'form_fields',
            [
                'label' => __('Form Fields', 'super-elem-pro'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'field_type' => 'text',
                        'field_label' => __('Name', 'super-elem-pro'),
                        'field_name' => 'name',
                        'field_placeholder' => __('Your name', 'super-elem-pro'),
                        'field_required' => 'yes',
                        'field_width' => '100',
                    ],
                    [
                        'field_type' => 'email',
                        'field_label' => __('Email', 'super-elem-pro'),
                        'field_name' => 'email',
                        'field_placeholder' => __('Your email', 'super-elem-pro'),
                        'field_required' => 'yes',
                        'field_width' => '100',
                    ],
                    [
                        'field_type' => 'textarea',
                        'field_label' => __('Message', 'super-elem-pro'),
                        'field_name' => 'message',
                        'field_placeholder' => __('Your message', 'super-elem-pro'),
                        'field_required' => 'yes',
                        'field_width' => '100',
                    ],
                ],
                'title_field' => '{{{ field_label }}}',
            ]
        );

        $this->end_controls_section();

        // Submit Button Section
        $this->start_controls_section(
            'section_button',
            [
                'label' => __('Submit Button', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Text', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Submit', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'button_size',
            [
                'label' => __('Size', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'xs' => __('Extra Small', 'super-elem-pro'),
                    'sm' => __('Small', 'super-elem-pro'),
                    'md' => __('Medium', 'super-elem-pro'),
                    'lg' => __('Large', 'super-elem-pro'),
                    'xl' => __('Extra Large', 'super-elem-pro'),
                ],
                'default' => 'md',
            ]
        );

        $this->add_control(
            'button_align',
            [
                'label' => __('Alignment', 'super-elem-pro'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'super-elem-pro'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'super-elem-pro'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'super-elem-pro'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
            ]
        );

        $this->end_controls_section();

        // Actions Section
        $this->start_controls_section(
            'section_actions',
            [
                'label' => __('Actions After Submit', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'email_to',
            [
                'label' => __('Email To', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => get_option('admin_email'),
                'placeholder' => get_option('admin_email'),
                'description' => __('Leave empty to use admin email', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'email_subject',
            [
                'label' => __('Email Subject', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('New Form Submission', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'success_message',
            [
                'label' => __('Success Message', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Thank you! Your submission has been received.', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'error_message',
            [
                'label' => __('Error Message', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Oops! Something went wrong. Please try again.', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'redirect_to',
            [
                'label' => __('Redirect After Submit', 'super-elem-pro'),
                'type' => Controls_Manager::URL,
                'placeholder' => '',
                'description' => __('Leave empty to stay on the same page', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'webhook_url',
            [
                'label' => __('Webhook URL', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'https://example.com/webhook',
                'description' => __('Send form data to external service', 'super-elem-pro'),
            ]
        );

        $this->end_controls_section();

        // Multi-Step Section
        $this->start_controls_section(
            'section_multi_step',
            [
                'label' => __('Multi-Step Form', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'enable_multi_step',
            [
                'label' => __('Enable Multi-Step', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'show_progress_bar',
            [
                'label' => __('Show Progress Bar', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'enable_multi_step' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Controls would go here (skipped for brevity)
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $form_id = 'sep-form-' . $this->get_id();
        ?>
        <div class="sep-form-wrapper">
            <form id="<?php echo esc_attr($form_id); ?>" class="sep-advanced-form"
                data-form-id="<?php echo esc_attr($this->get_id()); ?>" data-settings='<?php echo json_encode([
                         'success_message' => $settings['success_message'],
                         'error_message' => $settings['error_message'],
                         'redirect_to' => !empty($settings['redirect_to']['url']) ? $settings['redirect_to']['url'] : '',
                     ]); ?>'>
                <?php wp_nonce_field('sep_nonce', 'sep_form_nonce'); ?>

                <div class="sep-form-fields">
                    <?php foreach ($settings['form_fields'] as $index => $field): ?>
                        <?php $this->render_field($field, $index); ?>
                    <?php endforeach; ?>
                </div>

                <div class="sep-form-submit" style="text-align: <?php echo esc_attr($settings['button_align']); ?>;">
                    <button type="submit" class="sep-submit-btn sep-btn-<?php echo esc_attr($settings['button_size']); ?>">
                        <span class="sep-btn-text"><?php echo esc_html($settings['button_text']); ?></span>
                        <span class="sep-btn-loader" style="display: none;">
                            <svg width="20" height="20" viewBox="0 0 50 50">
                                <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor" stroke-width="4"
                                    stroke-dasharray="80" stroke-dashoffset="60">
                                    <animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25"
                                        dur="1s" repeatCount="indefinite" />
                                </circle>
                            </svg>
                        </span>
                    </button>
                </div>

                <div class="sep-form-messages"></div>
            </form>
        </div>
        <?php
    }

    private function render_field($field, $index)
    {
        $field_id = 'field-' . $index;
        $field_name = !empty($field['field_name']) ? $field['field_name'] : 'field_' . $index;
        $required = $field['field_required'] === 'yes' ? 'required' : '';
        $required_mark = $field['field_required'] === 'yes' ? '<span class="sep-required">*</span>' : '';

        ?>
        <div class="sep-form-field sep-field-width-<?php echo esc_attr($field['field_width']); ?>"
            data-field-type="<?php echo esc_attr($field['field_type']); ?>">

            <?php if (!empty($field['field_label']) && $field['field_type'] !== 'hidden'): ?>
                <label for="<?php echo esc_attr($field_id); ?>" class="sep-field-label">
                    <?php echo esc_html($field['field_label']); ?>
                    <?php echo $required_mark; ?>
                </label>
            <?php endif; ?>

            <?php
            switch ($field['field_type']) {
                case 'textarea':
                    ?>
                    <textarea id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_name); ?>"
                        placeholder="<?php echo esc_attr($field['field_placeholder']); ?>" class="sep-field sep-textarea" <?php echo $required; ?>><?php echo esc_textarea($field['field_default_value']); ?></textarea>
                    <?php
                    break;

                case 'select':
                    $options = explode("\n", $field['field_options']);
                    ?>
                    <select id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_name); ?>"
                        class="sep-field sep-select" <?php echo $required; ?>>
                        <option value=""><?php echo esc_html($field['field_placeholder']); ?></option>
                        <?php foreach ($options as $option): ?>
                            <option value="<?php echo esc_attr(trim($option)); ?>">
                                <?php echo esc_html(trim($option)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php
                    break;

                case 'checkbox':
                case 'radio':
                    $options = explode("\n", $field['field_options']);
                    ?>
                    <div class="sep-field-group">
                        <?php foreach ($options as $opt_index => $option): ?>
                            <label class="sep-<?php echo esc_attr($field['field_type']); ?>-label">
                                <input type="<?php echo esc_attr($field['field_type']); ?>"
                                    name="<?php echo esc_attr($field_name); ?><?php echo $field['field_type'] === 'checkbox' ? '[]' : ''; ?>"
                                    value="<?php echo esc_attr(trim($option)); ?>" <?php echo $required; ?>>
                                <span><?php echo esc_html(trim($option)); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <?php
                    break;

                case 'file':
                    ?>
                    <input type="file" id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_name); ?>"
                        class="sep-field sep-file" <?php echo $required; ?>>
                    <?php
                    break;

                case 'hidden':
                    ?>
                    <input type="hidden" name="<?php echo esc_attr($field_name); ?>"
                        value="<?php echo esc_attr($field['field_default_value']); ?>">
                    <?php
                    break;

                default:
                    ?>
                    <input type="<?php echo esc_attr($field['field_type']); ?>" id="<?php echo esc_attr($field_id); ?>"
                        name="<?php echo esc_attr($field_name); ?>"
                        placeholder="<?php echo esc_attr($field['field_placeholder']); ?>"
                        value="<?php echo esc_attr($field['field_default_value']); ?>" class="sep-field sep-input" <?php echo $required; ?>>
                    <?php
                    break;
            }
            ?>
        </div>
        <?php
    }
}