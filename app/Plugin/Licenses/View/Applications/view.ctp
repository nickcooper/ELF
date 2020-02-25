<div id="body" class="span-24">
    <?php echo $this->element('section_nav', array($fo_link)); ?>
    <div id="section" class="span-19 last">
        <div class="pad">

            <?php

            // Closed view with missing serialized data message
            if (!$app_open && $missing_serial_data) :
                echo '<div class="notice">This application does not have any historical information associated with it. Instead, this application is displaying today\'s current information.</div>';
            endif;

            ?>

            <?php echo $this->element('edit_action_bar'); ?>
            <div id="license_panel" class="content_panel">

            <?php
                echo $this->element(
                    'dynamic_section_page',
                    array(
                        'open' => $app_open,
                        'data' => &$application_view_data,
                        'sections' => $sections,
                    )
                );
            ?>
            </div>
            <?php echo $this->element('edit_action_bar'); ?>
        </div>
    </div>
</div>