<?php
	Router::connect('/payments/payments/view/*', array('plugin' => 'payments', 'controller' => 'payments', 'action' => 'receipt'));

