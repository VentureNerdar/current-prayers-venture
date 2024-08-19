<?php

declare(strict_types=1);

$url = dt_get_url_path();
$dt_post_type = explode('/', $url)[0];
dt_please_log_in();

if (!current_user_can('access_disciple_tools')) {
    wp_die(esc_html('Permission denied'), 'Permission denied', 403);
}

get_header();

?>

<!-- HTML -->
<div id="currentPrayers">
    <div id="alert" style="display: none;"></div>

    <div class="wrap-current-prayers">
        <div id="contactPrayerRequests" class="column">
            <h2>
                <?php esc_attr_e('Contacts Prayer Requests', 'current-prayers-venture') ?>
                <!-- Contact Prayer Requests -->
            </h2>
        </div>

        <div id="groupsPrayerRequests" class="column">
            <h2>
                <?php esc_attr_e('Groups Prayer Requests', 'current-prayers-venture') ?>
                <!-- Groups Prayer Requests -->
            </h2>
        </div>
    </div>
</div>

<!-- e.o HTML -->

<script>
</script>
<?php
get_footer();
