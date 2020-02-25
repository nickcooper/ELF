<div class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo $label;?></h3>

    <?php if(isset($description) && $description['element_plugin'] == 'accounts')
               { echo $description['description'] . '</br></br>'; } ?>

    <?php if($this->action == 'confirm_user') { $return = null; } ?>

    <?php if (GenLib::isData($rosters, '0.CourseSection', array('id'))): ?>
    <table border="0" cellpadding="0" cellspacing="0" class="light_data">
        <thead>
            <tr>
                <th scope="col">Course Name</th>
                <th scope="col">Course Section #</th>
                <th scope="col">Code Hours</th>
                <th scope="col">Non-code Hours</th>
                <th scope="col">Training Provider</th>
                <th scope="col">Date</th>
                <th scope="col">Refresher Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rosters as $roster) : ?>
                <?php if(in_array($roster['CourseSection']['course_catalog_id'], $valid_courses)) : ?>
                    <tr>
                        <td>
                            <?php
                                if ($app_open)
                                {
                                    echo $this->Html->aclLink(
                                        $roster['CourseSection']['label'],
                                        array(
                                            'plugin' => 'continuing_education',
                                            'controller' => 'course_sections',
                                            'action' => 'view',
                                            $roster['CourseSection']['id'],
                                            'return' => $return,
                                        ),
                                        array('title' => 'View course roster')
                                    );
                                }
                                else
                                {
                                    echo $roster['CourseSection']['label'];
                                }
                            ?>
                        </td>
                        <td><?php echo $roster['CourseSection']['course_section_number']; ?></td>
                        <td><?php echo $roster['CourseSection']['CourseCatalog']['code_hours']; ?></td>
                        <td><?php echo $roster['CourseSection']['CourseCatalog']['non_code_hours']; ?></td>
                        <td><?php echo $roster['CourseSection']['TrainingProvider']['label']; ?></td>
                        <td><?php echo GenLib::dateFormat($roster['CourseSection']['end_date']); ?></td>
                        <td width="85">
                            <?php
                                if ($app_open)
                                {
                                    echo $this->Html->aclLink(
                                        $roster['expire_date'] ? GenLib::dateFormat($roster['expire_date']) : 'Edit date',
                                        array(
                                            'plugin' => 'continuing_education',
                                            'controller' => 'course_rosters',
                                            'action' => 'edit_expire_date',
                                            $roster['id'],
                                            'return' => $return,
                                        ),
                                        array('title' => 'Edit expire date')
                                    );
                                }
                                else
                                {
                                    echo $roster['expire_date'] ? GenLib::dateFormat($roster['expire_date']) : '';
                                }
                            ?>
                        </td>
                    </tr>
                <? endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else : ?>

    <div class="notice text_center"><?php echo __('No courses have been taken.'); ?></div>

    <?php endif; ?>
</div>
