<div id="body" class="span-24">
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3>
                    <span class="pre"><?php echo __('Abatement Notice'); ?></span>
                    <?php if (isset($this->data['Abatement']['abatement_number'])): ?>
                        <?php echo h($this->data['Abatement']['abatement_number']); ?>
                    <?php else: ?>
                        <em><?php echo __('Not Submitted'); ?></em>
                    <?php endif; ?>
                </h3>

                <p class="bottom"><?php echo $this->Html->returnLink('/abatements/abatements/index', 'Abatements'); ?></p>
            </div>

            <div id="section_nav_holder">
                <ul id="section_nav" class="tab_nav">
                    <li class="selected"><a href="#license_panel"><?php echo __('Abatement Information'); ?></a>
                    <?php /* <li><a href="#notes_panel">Notes <span class="count">(<?php echo $note_count; ?>)</span></a></li> */ ?>
                </ul>
            </div>
        </div>
    </div>

    <div id="section" class="span-19 last">
        <div class="pad">
            <?php echo $this->element('action_bar'); ?>

            <?php
                // abatement info
                echo $this->element('abatements/sections/abatement_info');

                // property info
                echo $this->element(
                    'abatements/sections/property_info',
                    array(
                        'abatement'      => $this->data['Abatement'],
                        'property_owner' => $this->data['PropertyOwner'],
                        'address'        => $this->data['Address'],
                    )
                );

                // rental occupants info
                if ($isRental)
                {
                    echo $this->element(
                        'abatements/sections/occupants',
                        array(
                            'label'     => __('Property Occupants'),
                            'required'  => true,
                            'abatement' => $this->data['Abatement'],
                            'occupants' => $this->data['PropertyOccupant'],
                        )
                    );
                }

                // phase info
                echo $this->element(
                    'abatements/sections/phases',
                    array(
                        'label'     => __('Abatement Phases'),
                        'required'  => true,
                        'abatement' => $this->data['Abatement'],
                        'phases'    => $this->data['AbatementPhase'],
                    )
                );

                // firm info
                echo $this->element(
                    'abatements/sections/firm_info',
                    array(
                        'label'          => __('Firm Information'),
                        'required'       => true,
                        'abatement'      => $this->data['Abatement'],
                        'license'        => $this->data['License'],
                        'firm'           => $this->data['Firm'],
                        'foreign_plugin' => 'Abatements',
                        'foreign_obj'    => 'Abatement',
                        'foreign_key'    => $this->data['Abatement']['id'],
                    )
                );

            ?>

            <?php echo $this->element('action_bar'); ?>
        </div>
    </div>
</div>
