<div id="Firm" class="form_section">
    <h3><?php echo __('Property Information'); ?> <span class="req"><?php echo __('Required Section'); ?></span></h3>

<?php if (GenLib::isData($property_owner, null, array('id'))) : ?>

    <div class="span-12">
        <table border="0" cellpadding="0" cellspacing="0" class="vertical_data">
            <tbody>
                <tr>
                    <th scope="row"><?php echo __('Property Owner'); ?></th>
                    <td>
                        <?php echo $this->TextProcessing->checkForBlank(h(sprintf('%s %s', $property_owner['first_name'], $property_owner['last_name']))); ?>
                        <?php echo $this->element('AddressBook.templates/phone_and_fax', array('address' => $property_owner)); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Property Address'); ?></th>
                    <td><?php echo $this->element('AddressBook.templates/standard_address', array('address' => $address)); ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Year Built'); ?></th>
                    <td><?php echo $this->TextProcessing->checkForBlank($abatement['dwelling_year_built']); ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Work Description'); ?></th>
                    <td><?php echo $this->TextProcessing->checkForBlank(h($abatement['work_description'])); ?></td>
                </tr>
            </tbody>
       </table>
    </div>
    <div class="span-5 last">
        <div class="actions">
        <?php
            echo $this->Html->link(
                __('Edit Property Information'),
                array(
                    'plugin'     => 'abatements',
                    'controller' => 'abatements',
                    'action'     => 'property_info',
                    'fp'         => 'Abatements',
                    'fo'         => 'Abatement',
                    'fk'         => $abatement['id'],
                    'return'     => base64_encode($this->here),
                ),
                array('class' => 'button small')
            );
        ?>
        </div>
    </div>

<?php else: ?>

    <?php if ($isIncomplete): ?>

    <div class="actions text_center">
    <?php
        echo $this->Html->link(
            __('Add Property Information'),
            array(
                'plugin'     => 'abatements',
                'controller' => 'abatements',
                'action'     => 'property_info',
                'fp'         => 'Abatements',
                'fo'         => 'Abatement',
                'fk'         => $abatement['id'],
                'return'     => base64_encode($this->here),
            ),
            array('class' => 'button small')
        );
    ?>

    </div>

    <?php else: ?>

    <div class="notice text_center"><?php echo __('Abatement completed'); ?></div>

    <?php endif; ?>

<?php endif; ?>
</div>
