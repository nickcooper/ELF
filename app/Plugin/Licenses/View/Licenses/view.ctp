<div id="body" class="span-24">

    <!-- left column -->
    <?php echo $this->element('section_nav', array($fo_link)); ?>

    <!-- right column -->
    <div id="section" class="span-19 last">
        <div class="pad">
            <!-- page actions/buttons -->
            <?php echo $this->element('entity_page_actions'); ?>

            <!-- page content -->
            <?php
                // display the sections
                echo $this->element(
                    'dynamic_section_page',
                    array(
                        'open' => true,
                        'data' => &$license,
                        'sections' => $sections,
                    )
                );
            ?>

            <!-- page actions/buttons -->
            <?php echo $this->element('entity_page_actions'); ?>
        </div>
    </div>

</div>