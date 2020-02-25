<?php
    
    // var to keep track of what is set
    $blank = true;

    // phone1
    if (isset($address['phone1']) && preg_match('/[a-z0-9]/', $address['phone1']))
    {
        // not blank
        $blank = false;

        $ext = '';
        if (preg_match('/[a-z0-9]/', $address['ext1']))
        {
            $ext = sprintf(', x%s', $address['ext1']);
        }

        echo '<div><strong>';
        echo __('Phone 1:');
        echo '</strong> ';
        echo h(sprintf('%s%s', $this->TextProcessing->formatPhone($address['phone1']), $ext));
        echo '</div>';
    }
    else if (isset($address['phone']) && preg_match('/[a-z0-9]/', $address['phone']))
    {
        // not blank
        $blank = false;

        // for `contacts` entries
        $ext = '';
        if (preg_match('/[a-z0-9]/', $address['ext']))
        {
            $ext = sprintf(', x%s', $address['ext']);
        }

        echo '<div><strong>';
        echo __('Phone:');
        echo '</strong>';
        echo h(sprintf('%s%s', $this->TextProcessing->formatPhone($address['phone']), $ext));
        echo '</div>';
    }

    // phone2
    if (isset($address['phone2']) && preg_match('/[a-z0-9]/', $address['phone2']))
    {
        // not blank
        $blank = false;

        $ext = '';
        if (preg_match('/[a-z0-9]/', $address['ext2']))
        {
            $ext = sprintf(', x%s', $address['ext2']);
        }

        echo '<div><strong>';
        echo __('Phone 2:');
        echo '</strong> ';
        echo h(sprintf('%s%s', $this->TextProcessing->formatPhone($address['phone2']), $ext));
        echo '</div>';
    }

    // fax
    if (isset($addres['fax']) && preg_match('/[a-z0-9]/', $address['fax']))
    {
        // not blank
        $blank = false;

        echo '<div><strong>';
        echo __('Fax:');
        echo '</strong> ';
        echo h(sprintf('%s%s', $this->TextProcessing->formatPhone($address['fax']), $ext));
        echo '</div>';
    }

    if ($blank) {
        echo '<span class="blank">none</span>';
    }
