<?php if (!defined('ABSPATH'))
    exit; ?>
<div class="wrap super-elem-pro-admin">
    <div class="sep-header">
        <div class="sep-brand">
            <h1>Super Elem Pro</h1>
            <span class="sep-version">v<?php echo SUPER_ELEM_PRO_VERSION; ?></span>
        </div>
        <div class="sep-author">By Rohit (Personal Use Only)</div>
    </div>

    <h2 class="nav-tab-wrapper">
        <a href="#widgets" class="nav-tab nav-tab-active" data-tab="widgets">Widgets</a>
        <a href="#theme-builder" class="nav-tab" data-tab="theme-builder">Theme Builder</a>
        <a href="#popup-builder" class="nav-tab" data-tab="popup-builder">Popup Builder</a>
        <a href="#extensions" class="nav-tab" data-tab="extensions">Extensions</a>
        <a href="#dynamic-tags" class="nav-tab" data-tab="dynamic-tags">Dynamic Tags</a>
    </h2>

    <form id="sep-settings-form" method="post">
        <div class="tab-content active" id="tab-widgets">
            <?php include 'tab-widgets.php'; ?>
        </div>
        <div class="tab-content" id="tab-theme-builder">
            <?php include 'tab-theme-builder.php'; ?>
        </div>
        <div class="tab-content" id="tab-extensions">
            <?php include 'tab-extensions.php'; ?>
        </div>

        <div class="sep-footer">
            <button type="submit" class="button button-primary button-hero">Save All Settings</button>
        </div>
    </form>
</div>