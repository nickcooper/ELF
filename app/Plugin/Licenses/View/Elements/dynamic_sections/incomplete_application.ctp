<?php if ($open_application['ApplicationStatus']['label'] == 'Incomplete') :?>
You currently have an incomplete application for this license type. In order to submit your application to the agency, you will need to select Complete Application to make any changes prior to submitting this for approval. You can also delete this application by selecting the Delete Application button.<br /> <br />
<?php
    echo $this->Html->aclLink(
        'Complete Application',
        sprintf('/licenses/applications/view/%s', $open_application['id']) . DS . 'return:' . $return,
        array(
            'title' => 'Complete Application',
            'class' => 'button small',
            'escape' => false,
        )
    );
    echo $this->Html->aclLink(
        'Delete Application',
        sprintf('/licenses/applications/cancel/%s', $open_application['id']) . DS . 'return:' . $return,
        array(
            'title' => 'Delete Application',
            'class' => 'button small',
            'escape' => false,
        )
    );
?>
<?php endif; ?>