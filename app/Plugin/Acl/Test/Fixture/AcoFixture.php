<?php
/* Aco Fixture generated on: 2012-03-20 10:51:34 : 1332258694 */

/**
 * AcoFixture
 *
 */
class AcoFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary', 'collate' => NULL, 'comment' => ''),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index', 'collate' => NULL, 'comment' => ''),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'collate' => NULL, 'comment' => ''),
		'alias' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index', 'collate' => NULL, 'comment' => ''),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'collate' => NULL, 'comment' => ''),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'parent_id' => array('column' => 'parent_id', 'unique' => 0), 'model' => array('column' => array('model', 'foreign_key'), 'unique' => 0), 'left' => array('column' => array('lft', 'rght'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'parent_id' => NULL,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'controllers',
			'lft' => '1',
			'rght' => '148'
		),
		array(
			'id' => '2',
			'parent_id' => '1',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'AaAuth',
			'lft' => '2',
			'rght' => '13'
		),
		array(
			'id' => '3',
			'parent_id' => '2',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Client',
			'lft' => '3',
			'rght' => '12'
		),
		array(
			'id' => '4',
			'parent_id' => '3',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'registration',
			'lft' => '4',
			'rght' => '5'
		),
		array(
			'id' => '5',
			'parent_id' => '3',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'resetPassword',
			'lft' => '6',
			'rght' => '7'
		),
		array(
			'id' => '6',
			'parent_id' => '3',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'login',
			'lft' => '8',
			'rght' => '9'
		),
		array(
			'id' => '7',
			'parent_id' => '3',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'logout',
			'lft' => '10',
			'rght' => '11'
		),
		array(
			'id' => '8',
			'parent_id' => '1',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Accounts',
			'lft' => '14',
			'rght' => '67'
		),
		array(
			'id' => '9',
			'parent_id' => '8',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Accounts',
			'lft' => '15',
			'rght' => '38'
		),
		array(
			'id' => '10',
			'parent_id' => '9',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'activate',
			'lft' => '16',
			'rght' => '17'
		),
		array(
			'id' => '11',
			'parent_id' => '9',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'registration',
			'lft' => '18',
			'rght' => '19'
		),
		array(
			'id' => '12',
			'parent_id' => '9',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'password',
			'lft' => '20',
			'rght' => '21'
		),
		array(
			'id' => '13',
			'parent_id' => '9',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'login',
			'lft' => '22',
			'rght' => '23'
		),
		array(
			'id' => '14',
			'parent_id' => '9',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'logout',
			'lft' => '24',
			'rght' => '25'
		),
		array(
			'id' => '15',
			'parent_id' => '9',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'myAccount',
			'lft' => '26',
			'rght' => '27'
		),
		array(
			'id' => '16',
			'parent_id' => '9',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => '28',
			'rght' => '29'
		),
		array(
			'id' => '17',
			'parent_id' => '9',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'view',
			'lft' => '30',
			'rght' => '31'
		),
		array(
			'id' => '18',
			'parent_id' => '9',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'add',
			'lft' => '32',
			'rght' => '33'
		),
		array(
			'id' => '19',
			'parent_id' => '9',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'edit',
			'lft' => '34',
			'rght' => '35'
		),
		array(
			'id' => '20',
			'parent_id' => '9',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'delete',
			'lft' => '36',
			'rght' => '37'
		),
		array(
			'id' => '21',
			'parent_id' => '1',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Acl',
			'lft' => '68',
			'rght' => '119'
		),
		array(
			'id' => '22',
			'parent_id' => '21',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Acl',
			'lft' => '69',
			'rght' => '74'
		),
		array(
			'id' => '23',
			'parent_id' => '22',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => '70',
			'rght' => '71'
		),
		array(
			'id' => '24',
			'parent_id' => '22',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'acos_index',
			'lft' => '72',
			'rght' => '73'
		),
		array(
			'id' => '25',
			'parent_id' => '21',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Acos',
			'lft' => '75',
			'rght' => '82'
		),
		array(
			'id' => '26',
			'parent_id' => '25',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => '76',
			'rght' => '77'
		),
		array(
			'id' => '27',
			'parent_id' => '25',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'empty_acos',
			'lft' => '78',
			'rght' => '79'
		),
		array(
			'id' => '28',
			'parent_id' => '25',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'build_acl',
			'lft' => '80',
			'rght' => '81'
		),
		array(
			'id' => '29',
			'parent_id' => '21',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Aros',
			'lft' => '83',
			'rght' => '118'
		),
		array(
			'id' => '30',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => '84',
			'rght' => '85'
		),
		array(
			'id' => '31',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'check',
			'lft' => '86',
			'rght' => '87'
		),
		array(
			'id' => '32',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'users',
			'lft' => '88',
			'rght' => '89'
		),
		array(
			'id' => '33',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'update_user_role',
			'lft' => '90',
			'rght' => '91'
		),
		array(
			'id' => '34',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'ajax_role_permissions',
			'lft' => '92',
			'rght' => '93'
		),
		array(
			'id' => '35',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'role_permissions',
			'lft' => '94',
			'rght' => '95'
		),
		array(
			'id' => '36',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'user_permissions',
			'lft' => '96',
			'rght' => '97'
		),
		array(
			'id' => '37',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'empty_permissions',
			'lft' => '98',
			'rght' => '99'
		),
		array(
			'id' => '38',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'clear_user_specific_permissions',
			'lft' => '100',
			'rght' => '101'
		),
		array(
			'id' => '39',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'grant_all_controllers',
			'lft' => '102',
			'rght' => '103'
		),
		array(
			'id' => '40',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'deny_all_controllers',
			'lft' => '104',
			'rght' => '105'
		),
		array(
			'id' => '41',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'get_role_controller_permission',
			'lft' => '106',
			'rght' => '107'
		),
		array(
			'id' => '42',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'grant_role_permission',
			'lft' => '108',
			'rght' => '109'
		),
		array(
			'id' => '43',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'deny_role_permission',
			'lft' => '110',
			'rght' => '111'
		),
		array(
			'id' => '44',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'get_user_controller_permission',
			'lft' => '112',
			'rght' => '113'
		),
		array(
			'id' => '45',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'grant_user_permission',
			'lft' => '114',
			'rght' => '115'
		),
		array(
			'id' => '46',
			'parent_id' => '29',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'deny_user_permission',
			'lft' => '116',
			'rght' => '117'
		),
		array(
			'id' => '47',
			'parent_id' => '1',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'DebugKit',
			'lft' => '120',
			'rght' => '127'
		),
		array(
			'id' => '48',
			'parent_id' => '47',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'ToolbarAccess',
			'lft' => '121',
			'rght' => '126'
		),
		array(
			'id' => '49',
			'parent_id' => '48',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'history_state',
			'lft' => '122',
			'rght' => '123'
		),
		array(
			'id' => '50',
			'parent_id' => '48',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'sql_explain',
			'lft' => '124',
			'rght' => '125'
		),
		array(
			'id' => '51',
			'parent_id' => '1',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Pages',
			'lft' => '128',
			'rght' => '147'
		),
		array(
			'id' => '52',
			'parent_id' => '51',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Pages',
			'lft' => '129',
			'rght' => '146'
		),
		array(
			'id' => '53',
			'parent_id' => '52',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'home',
			'lft' => '130',
			'rght' => '131'
		),
		array(
			'id' => '54',
			'parent_id' => '52',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'display',
			'lft' => '132',
			'rght' => '133'
		),
		array(
			'id' => '55',
			'parent_id' => '52',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'error404',
			'lft' => '134',
			'rght' => '135'
		),
		array(
			'id' => '56',
			'parent_id' => '8',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Programs',
			'lft' => '39',
			'rght' => '52'
		),
		array(
			'id' => '57',
			'parent_id' => '56',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'activate',
			'lft' => '40',
			'rght' => '41'
		),
		array(
			'id' => '58',
			'parent_id' => '56',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => '42',
			'rght' => '43'
		),
		array(
			'id' => '59',
			'parent_id' => '56',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'view',
			'lft' => '44',
			'rght' => '45'
		),
		array(
			'id' => '60',
			'parent_id' => '56',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'add',
			'lft' => '46',
			'rght' => '47'
		),
		array(
			'id' => '61',
			'parent_id' => '56',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'edit',
			'lft' => '48',
			'rght' => '49'
		),
		array(
			'id' => '62',
			'parent_id' => '56',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'delete',
			'lft' => '50',
			'rght' => '51'
		),
		array(
			'id' => '63',
			'parent_id' => '8',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'GroupPrograms',
			'lft' => '53',
			'rght' => '66'
		),
		array(
			'id' => '64',
			'parent_id' => '63',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'activate',
			'lft' => '54',
			'rght' => '55'
		),
		array(
			'id' => '65',
			'parent_id' => '63',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => '56',
			'rght' => '57'
		),
		array(
			'id' => '66',
			'parent_id' => '63',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'view',
			'lft' => '58',
			'rght' => '59'
		),
		array(
			'id' => '67',
			'parent_id' => '63',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'add',
			'lft' => '60',
			'rght' => '61'
		),
		array(
			'id' => '68',
			'parent_id' => '63',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'edit',
			'lft' => '62',
			'rght' => '63'
		),
		array(
			'id' => '69',
			'parent_id' => '63',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'delete',
			'lft' => '64',
			'rght' => '65'
		),
		array(
			'id' => '70',
			'parent_id' => '52',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => '136',
			'rght' => '137'
		),
		array(
			'id' => '71',
			'parent_id' => '52',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'view',
			'lft' => '138',
			'rght' => '139'
		),
		array(
			'id' => '72',
			'parent_id' => '52',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'add',
			'lft' => '140',
			'rght' => '141'
		),
		array(
			'id' => '73',
			'parent_id' => '52',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'edit',
			'lft' => '142',
			'rght' => '143'
		),
		array(
			'id' => '74',
			'parent_id' => '52',
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'delete',
			'lft' => '144',
			'rght' => '145'
		),
	);
}
