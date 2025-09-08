<?php
/*
Plugin Name: Modern Pop-up
Description: Görsel ve yazı desteği olan, çerezler sayesinde kullanıcıya sadece bir kez gösterilen modern bir pop-up eklentisi.
Version: 1.1
Author: Talha CABA
Author URI: https://talha.gen.tr
*/

if (!defined('ABSPATH')) {
    exit;
}

// ----------------------------------------------------
// 1. Admin Menüsü
// ----------------------------------------------------
function mpu_admin_menu() {
    add_menu_page(
        'Pop-up Ayarları',
        'Modern Pop-up',
        'manage_options',
        'modern-pop-up-ayarlari',
        'mpu_settings_page_html',
        'dashicons-format-image',
        6
    );
}
add_action('admin_menu', 'mpu_admin_menu');

// ----------------------------------------------------
// 2. Ayarlar Sayfası
// ----------------------------------------------------
function mpu_settings_page_html() {
    if (isset($_POST['mpu_save_settings'])) {
        update_option('mpu_aktif', isset($_POST['mpu_aktif']) ? 1 : 0);
        update_option('mpu_baslik', sanitize_text_field($_POST['mpu_baslik']));
        update_option('mpu_metin', wp_kses_post($_POST['mpu_metin']));
        update_option('mpu_gorsel_id', absint($_POST['mpu_gorsel_id']));
        ?>
        <div class="notice notice-success is-dismissible">
            <p>Ayarlarınız başarıyla kaydedildi!</p>
        </div>
        <?php
    }

    $mpu_aktif = get_option('mpu_aktif', 0);
    $mpu_baslik = get_option('mpu_baslik', '');
    $mpu_metin = get_option('mpu_metin', '');
    $mpu_gorsel_id = get_option('mpu_gorsel_id', '');
    $mpu_gorsel_url = wp_get_attachment_url($mpu_gorsel_id);
    ?>

    <div class="wrap mpu-admin-wrap">
        <h1 class="wp-heading-inline">Modern Pop-up Ayarları</h1>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content" style="position: relative;">
                    <div class="meta-box-sortables ui-sortable">
                        <div id="mpu-settings" class="postbox">
                            <h2 class="hndle"><span>Ayarlar</span></h2>
                            <div class="inside">
                                <form method="post" id="mpu-settings-form">
                                    <table class="form-table">
                                        <tbody>
                                            <tr class="mpu-setting-row">
                                                <th scope="row">Pop-up'ı Aktif Et</th>
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" name="mpu_aktif" value="1" <?php checked(1, $mpu_aktif); ?>>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr class="mpu-setting-row">
                                                <th scope="row"><label for="mpu_baslik">Başlık</label></th>
                                                <td>
                                                    <input type="text" name="mpu_baslik" id="mpu_baslik" value="<?php echo esc_attr($mpu_baslik); ?>" class="regular-text">
                                                </td>
                                            </tr>
                                            <tr class="mpu-setting-row">
                                                <th scope="row"><label for="mpu_metin">Metin</label></th>
                                                <td>
                                                    <textarea name="mpu_metin" id="mpu_metin" rows="5" class="large-text"><?php echo esc_textarea($mpu_metin); ?></textarea>
                                                </td>
                                            </tr>
                                            <tr class="mpu-setting-row">
                                                <th scope="row"><label for="mpu_gorsel_id">Görsel</label></th>
                                                <td>
                                                    <input type="hidden" name="mpu_gorsel_id" id="mpu_gorsel_id" value="<?php echo esc_attr($mpu_gorsel_id); ?>">
                                                    <button class="button button-secondary" id="mpu_upload_button">Görsel Yükle/Seç</button>
                                                    <button class="button button-secondary" id="mpu_remove_button" <?php echo empty($mpu_gorsel_id) ? 'style="display:none;"' : ''; ?>>Kaldır</button>
                                                    <br>
                                                    <img id="mpu_preview_image" src="<?php echo esc_url($mpu_gorsel_url); ?>" style="max-width:300px; margin-top:10px;" <?php echo empty($mpu_gorsel_id) ? 'style="display:none;"' : ''; ?>>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p class="submit">
                                        <input type="submit" name="mpu_save_settings" class="button button-primary" value="Ayarları Kaydet">
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="postbox-container-1" class="postbox-container">
                    <div class="meta-box-sortables">
                        <div class="postbox">
                            <h2 class="hndle"><span>Canlı Önizleme</span></h2>
                            <div class="inside mpu-preview-inside">
                                <div id="mpu-live-preview-container">
                                    <div id="mpu-preview-content">
                                        <span id="mpu-preview-close">&times;</span>
                                        <img id="mpu-preview-image" src="<?php echo esc_url($mpu_gorsel_url); ?>" alt="" class="mpu-image" <?php echo empty($mpu_gorsel_url) ? 'style="display:none;"' : ''; ?>>
                                        <div class="mpu-text-content">
                                            <h2 id="mpu-preview-baslik"><?php echo esc_html($mpu_baslik); ?></h2>
                                            <p id="mpu-preview-metin"><?php echo esc_html($mpu_metin); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

// ----------------------------------------------------
// 3. Script ve Stil Dosyalarını Ekleme
// ----------------------------------------------------
function mpu_enqueue_scripts() {
    if (is_admin()) {
        wp_enqueue_style('mpu-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css', array(), '1.0');
        wp_enqueue_media();
        wp_enqueue_script('mpu-admin-js', plugin_dir_url(__FILE__) . 'assets/js/admin.js', array('jquery'), '1.0', true);
    }
    wp_enqueue_style('mpu-style', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), '1.0');
    wp_enqueue_script('mpu-main-js', plugin_dir_url(__FILE__) . 'assets/js/main.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'mpu_enqueue_scripts');
add_action('admin_enqueue_scripts', 'mpu_enqueue_scripts');

// ----------------------------------------------------
// 4. Pop-up'ı Ön Yüzde Gösterme
// ----------------------------------------------------
function mpu_display_popup() {
    $mpu_aktif = get_option('mpu_aktif', 0);
    $mpu_baslik = get_option('mpu_baslik', '');
    $mpu_metin = get_option('mpu_metin', '');
    $mpu_gorsel_id = get_option('mpu_gorsel_id', '');
    $mpu_gorsel_url = wp_get_attachment_url($mpu_gorsel_id);

    if (!$mpu_aktif || (isset($_COOKIE['mpu_seen']) && $_COOKIE['mpu_seen'] == 'true')) {
        return;
    }

    $gorsel_html = !empty($mpu_gorsel_url) ? '<img src="' . esc_url($mpu_gorsel_url) . '" alt="' . esc_attr($mpu_baslik) . '" class="mpu-image">' : '';
    ?>

    <div id="mpu-modal">
        <div id="mpu-content">
            <span id="mpu-close">&times;</span>
            <?php echo $gorsel_html; ?>
            <div class="mpu-text-content">
                <?php if (!empty($mpu_baslik)) : ?>
                    <h2><?php echo esc_html($mpu_baslik); ?></h2>
                <?php endif; ?>
                <?php if (!empty($mpu_metin)) : ?>
                    <p><?php echo nl2br(esc_html($mpu_metin)); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php
}
add_action('wp_body_open', 'mpu_display_popup');
