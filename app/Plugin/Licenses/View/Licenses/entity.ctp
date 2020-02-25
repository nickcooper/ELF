<div id="body" class="span-24">

    <!-- left column -->
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3><?php echo $data[$entity]['label']; ?></h3>
                <p class="attn"><?php echo $label; ?></p>
            </div>

            <?php if (isset($nav) && count($nav)) : ?>
                <div id="section_nav_holder">
                    <ul id="section_nav">
                        <?php
                            // loop the nav options
                            foreach ($nav as $options) :
                                echo "<li>";

                                foreach ($options['url'][1] as $param_key => $map) :
                                    $options['url'][1][$param_key] = Hash::get($data, $map);
                                endforeach;

                                $link_type = 'link';
                                if ($options['acl']) :
                                    $link_type = 'aclLink';
                                endif;

                                $return = '';
                                if (isset($options['return']) && $options['return']) :
                                    $return = '/return:'.base64_encode($this->here);
                                endif;

                                echo $this->Html->{$link_type}(
                                    $options['label'],
                                    vsprintf($options['url'][0], $options['url'][1]).$return,
                                    $options['attr']
                                );

                                echo "</li>";
                            endforeach;
                        ?>
                    </ul>
                </div>
            <?php endif; ?>
       </div>
    </div>

    <!-- right column -->
    <div id="section" class="span-19 last">
        <div class="pad">
            <!-- page description -->
            <?php echo ($descr ? '<p>'.$descr.'</p><hr />' : '') ; ?>

            <!-- page actions/buttons -->
            <?php echo $this->element('entity_page_actions'); ?>

            <!-- page content -->
            <?php
                // display the sections
                echo $this->element(
                    'dynamic_section_page',
                    array(
                        'open' => true,
                        'data' => &$data,
                        'sections' => $sections,
                    )
                );
            ?>

            <!-- page actions/buttons -->
            <?php echo $this->element('entity_page_actions'); ?>
        </div>
    </div>

</div>