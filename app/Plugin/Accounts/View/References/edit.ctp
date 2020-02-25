<div id="body" class="span-24">
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3><span class="pre"><?php echo __('ACCOUNT'); ?></span> <?php echo __('References'); ?></h3>
                <p class="bottom">
                    <?php echo $this->IiHtml->returnLink(); ?>
                </p>
            </div>
        </div>
    </div>

    <div id="section" class="span-19 last">
        <div class="pad">
            <h3><?php echo __('Reference Information'); ?></h3>
            <p>Provide names, addresses, and phone numbers of three (3) persons or firms, preferably in the electrical industry, to be used as references. These can be Supervisors, instructors, mentors, co-workers, supply houses, or clients. Three (3) references are required.</p>

                <?php echo $this->Form->create('Reference'); ?>

<? $i = 0; while($i < 3): ?>
            <fieldset>
                <legend>Contact Information</legend>
                <?php 
                    if (Hash::check($this->data, sprintf('Reference.%s.id', $i))) :
                        echo $this->Form->input(sprintf('Reference.%s.id', $i), array('type' => 'hidden'));
                    endif; 
                ?>
                <?php 
                    if (Hash::check($this->data, sprintf('Reference.%s.Contact.id', $i))) :
                        echo $this->Form->input(sprintf('Reference.%s.Contact.id', $i), array('type' => 'hidden'));
                    endif; 
                ?>
                <?php echo $this->Form->input(sprintf('Reference.%s.Contact.first_name', $i), array('label' => 'First Name', 'type' => 'text')); ?>
                <?php echo $this->Form->input(sprintf('Reference.%s.Contact.last_name', $i), array('label' => 'Last Name', 'type' => 'text')); ?>
                <?php echo $this->Form->input(sprintf('Reference.%s.Contact.phone', $i), array('label' => 'Phone Number', 'type' => 'phone', 'class' => 'text span-5 phone')); ?>
            </fieldset>
                    <?php echo $this->Element('AddressBook.address_form_short', array('prefix' => sprintf('Reference.%s', $i))); ?>
            </fieldset>
            <fieldset>
                <legend>Notes</legend>
                <?php echo $this->Form->input(sprintf('Reference.%s.notes', $i), array('label' => false, 'type' => 'textarea'));?>
            </fieldset>
<? $i++; endwhile; ?>
        <?php echo $this->Form->end('Save'); ?>
        </div>
    </div>
</div>