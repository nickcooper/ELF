<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3><span class="pre">License Type</span> <?php echo $license_type['LicenseType']['label']; ?></h3>
            <p class="bottom"><?php echo $this->Html->link('< back to listing', array('plugin' => 'licenses', 'controller' => 'license_types', 'action' => 'index')); ?></p>
        </div>
        <div id="section_nav_holder">
            <ul id="section_nav" class="tab_nav">
                <li class="selected"><a href="#license_type">License Type Information</a></li>
                <li><a href="#license_type_notes">Notes <span class="count">(0)</span></a></li>
            </ul>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <div class="actions">
            <?php echo $this->Html->link('Finished', array('plugin' => 'licenses', 'controller' => 'license_types', 'actions' => 'index'), array('class' => 'button')) ?>
        </div>
        <div id="license_type" class="content_panel">
            <h2>License Type</h2>
            <div id="License_Type_Information" class="form_section">
                <h3>License Type Information</h3>
                <div class="span-12">
                    <table border="0" cellpadding="0" cellspacing="0" class="vertical_data">
                        <tr>
                            <th width="120" scope="row">License Type</th>
                            <td><?php echo $license_type['LicenseType']['label']; ?></td>
                        </tr>
                        <tr>
                            <th width="120" scope="row">License Type Description</th>
                            <td><?php echo $license_type['LicenseType']['descr']; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="span-5 last">
                    <div class="actions"><?php echo $this->Html->link('Edit License Type Info', array('plugin' => 'licenses', 'controller' => 'license_types', 'action' => 'edit', $license_type['LicenseType']['id']), array('class' => 'button small')); ?></div>
                </div>
            </div>
        </div>
        <!-- close #license_type -->

        <div id="license_type_notes" class="content_panel hide">
            <h2>License Types Notes</h2>
        </div>
        <!-- close #license_type_notes -->
        
        <div class="actions">
            <?php echo $this->Html->link('Finished', array('plugin' => 'licenses', 'controller' => 'license_types', 'actions' => 'index'), array('class' => 'button')) ?>
        </div>
    </div>
</div>