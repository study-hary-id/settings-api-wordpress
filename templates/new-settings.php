<div class="wrap">
    <h1>New Settings</h1>
    <form action="options.php" method="post">
        <?php
        settings_fields( 'settings_plugin_options' );
        do_settings_sections( 'new_settings_plugin' );
        submit_button();
        ?>
    </form>
</div>
