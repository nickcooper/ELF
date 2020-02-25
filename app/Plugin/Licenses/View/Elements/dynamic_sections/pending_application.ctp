<?php if ($open_application['ApplicationStatus']['label'] == 'Pending') :?>
You currently have pending application for this license type awaiting administrative approval.<br /> <br />
<?php
    echo $this->Html->aclLink(
        'View Application',
        sprintf('/licenses/applications/view/%s', $open_application['id']) . DS . 'return:' . $return,
        array(
            'title' => 'View Application',
            'class' => 'button small',
            'escape' => false,
        )
    );
?>
<?php endif; ?>