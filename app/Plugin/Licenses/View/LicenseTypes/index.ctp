<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3>License Types</h3>
            <p class="bottom">Manage License Types</p>
        </div>
        <div id="section_nav_holder">
            <ul id="section_nav">
                <li><a href="#"></a></li>
            </ul>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <h2>License Types</h2>
        <hr />
        <div class="actions">
            <?php echo $this->Html->link(__('New License Type', true), array('plugin' => 'licenses', 'controller' => 'license_types', 'action' => 'add'), array('class'=>'button')); ?>
        </div>
        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th><?php echo $this->Paginator->sort('LicenseType.type', 'License Type');?></th>
                    <th><?php echo $this->Paginator->sort('LicenseType.enabled', 'Enabled');?></th>
                    <th><?php echo $this->Paginator->sort('LicenseType.modified', 'Modified');?></th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ($license_types as $license_type):
                ?>
                <tr>
                    <td><?php echo $this->Html->link($license_type['LicenseType']['label'], array('plugin' => 'licenses', 'controller' => 'license_types', 'action' => 'view', $license_type['LicenseType']['id'])); ?>&nbsp;</td>
                    <td><?php echo $license_type['LicenseType']['enabled']; ?></td>
                    <td><?php echo $license_type['LicenseType']['modified']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php echo $this->element('pagination_links'); ?>
    </div>
</div>