<div id="variant" class="form_section <?php echo $open_close_class;?>">
    <h3>
        <?php echo h(Inflector::pluralize($label)); ?>
    <?php if ($required): ?>
        <span class="req"><?php echo __('Required Section'); ?></span>
    <?php endif; ?>
    </h3>

    <?php if (isset($description) && $description['element_plugin'] == 'licenses')
       { echo $description['description'] . '</br></br>'; } ?>

    <?php if (GenLib::isData($license_variants, '0', array('id'))): ?>
    <div class="span-12">
        <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
            <tbody><tr>
                <th scope="row"><?php echo __('Variant Name'); ?></th>
                <th scope="row"><?php echo __('Document'); ?></th>
                <th scope="row"><?php echo __('Actions'); ?></th>
            </tr>
            <?php foreach ($license_variants as $license_variant) : ?>
            <tr>
                <td>
                    <span title="<?php echo $license_variant['Variant']['descr']; ?>">
                        <?php echo h($license_variant['Variant']['label']); ?>
                    </span>
                </td>
                <td>
                    <?php
                        if (GenLib::isData($license_variant, 'Upload.0', array('id'))):
                            echo $this->Html->link(
                                '<i class="icon-eye-open"></i> ' . __('View'),
                                DS.'files'.DS.$license_variant['Upload'][0]['file_name'],
                                array(
                                    'title'  => __('View Document'),
                                    'target' => '_blank',
                                    'class'  => 'inline_action blue',
                                    'escape' => false
                                )
                            );
                        endif;
                    ?>
                </td>
                <td width="85">
                    <?php
                        if ($app_open)
                        {
                            echo $this->Html->link(
                                '<i class="icon-remove-sign"></i> ' . __('Remove'),
                                array(
                                    'plugin'     => 'licenses',
                                    'controller' => 'variants',
                                    'action'     => 'delete',
                                    $license_variant['id'],
                                    'return'     => $return,
                                ),
                                array(
                                    'class' => 'inline_action warning',
                                    'title' => __('Remove Variant'),
                                    'escape' => false
                                ),
                                sprintf(
                                    __('Are you sure you want to remove %s variant from this license?'),
                                    $license_variant['Variant']['abbr']
                                )
                            );
                        }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody></table>
    </div>
    <div class="span-5 last">
        <div class="actions">
        <?php
            echo $this->Html->link(
                __('Add License Variant'),
                array(
                    'plugin'     => 'licenses',
                    'controller' => 'variants',
                    'action'     => 'add',
                    'fp'         => 'licenses',
                    'fo'         => 'license',
                    'fk'         => $license['id'],
                    'return'     => $return,
                ),
                array('class' => 'button small')
            );
        ?>
        </div>
    </div>

    <?php else: ?>

    <div class="actions text_center">
        <?php
            echo $this->Html->link(
                __('Add License Variant'),
                array(
                    'plugin'     => 'licenses',
                    'controller' => 'variants',
                    'action'     => 'add',
                    'fp'         => 'licenses',
                    'fo'         => 'license',
                    'fk'         => $license['id'],
                    'return'     => $return,
                ),
                array('class' => 'button small')
            );
        ?>
    </div>

    <?php endif; ?>
</div>
