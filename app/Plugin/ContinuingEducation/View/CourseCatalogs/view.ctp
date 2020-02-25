<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3><span class="pre"><?php echo __('View'); ?></span> <?php echo __('Course Catalog Item'); ?></h3>
            <p class="bottom"><?php echo $this->Html->link(__('< Back to Listing'), array('action' => 'index')); ?></p>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <div class="actions">
            <?php echo $this->Html->link(__('Finished'), array('action' => 'index'), array('class' => 'button')); ?>
        </div>
        <div id="Course_Details" class="form_section">
            <h3><?php echo __('Course Details'); ?></h3>
            <div class="span-12">
                <table border="0" cellpadding="0" cellspacing="0" class="vertical_data">
                    <tr>
                        <th scope="row"><?php echo __('Course Title'); ?></th>
                        <td><?php echo h($course['CourseCatalog']['label']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Course Description'); ?></th>
                        <td><?php echo h($course['CourseCatalog']['descr']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Program'); ?></th>
                        <td><?php echo h($course['Program']['label']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Code Hours'); ?></th>
                        <td><?php echo h($course['CourseCatalog']['code_hours']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Non-code Hours'); ?></th>
                        <td><?php echo h($course['CourseCatalog']['non_code_hours']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Allowed Number of Test Attempts'); ?></th>
                        <td><?php echo h($course['CourseCatalog']['test_attempts']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Months until expired'); ?></th>
                        <td><?php echo h($course['CourseCatalog']['cycle']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Enabled'); ?></th>
                        <td><?php echo $this->Html->enableButton($this->params['plugin'], $this->params['controller'], $course); ?></td>
                    </tr>
                </table>
            </div>
            <div class="span-5 last">
                <div class="actions">
                    <?php
                        echo $this->Html->link(
                            __('Edit Course Details'),
                            array(
                                'action' => 'edit',
                                $course['CourseCatalog']['id'],
                            ),
                            array('class' => 'button small')
                        );
                    ?>
                </div>
            </div>
        </div>
        <div class="actions bottom">
            <?php echo $this->Html->link(__('Finished'), array('action' => 'index'), array('class' => 'button')); ?>
        </div>
    </div>
</div>
