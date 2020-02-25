<?php
    // standard address format
    if (isset($address['id']) && preg_match('/[0-9]+/', $address['id']))
    {
        // attn
        if (preg_match('/[a-z0-9]/', $address['attention']))
        {
            echo sprintf(__('Attn: %s'), h($address['attention']));
            echo '<br />';
        }

        // address
        if (! empty($address['addr1']))
        {
            echo $address['addr1'];
        }
        
        if (! empty($address['addr2']))
        {
            echo '<br />';
            echo h($address['addr2']);
        }
        
        // city, state, zip
        if (strlen($address['city']) > 0 && strlen($address['state']) > 0 && ! empty($address['postal']))
        {
            echo '<br />';
            echo sprintf(
                '%s, %s %s',
                h($address['city']),
                h(strtoupper($address['state'])),
                h($address['postal'])
            );
        }

        // phone
        if (! empty($address['phone1']))
        {
            echo '<br />';
            echo h($this->TextProcessing->formatPhone($address['phone1']));
            if (! empty($address['ext1']))
            {
                echo sprintf(' x%d', h($address['ext1']));
            }
        }
    }
    else if (isset($add_addr_link) && isset($add_addr_link['fk']))
    {
        // add address link
        echo $this->Html->link(
            __('Add Address'),
            array(
                'plugin'     => 'address_book',
                'controller' => 'addresses',
                'action'     => 'add',
                'fp'         => $add_addr_link['fp'],
                'fo'         => $add_addr_link['fo'],
                'fk'         => $add_addr_link['fk'],
                'return'     => base64_encode($this->here)
            )
        );
    }
    else
    {
        // no set
        echo '<span class="blank">' . __('No Address on file') . '</span>';
    }
