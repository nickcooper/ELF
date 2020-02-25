<?php
/**
 * AA Auth Configuration
 *
 * Copy this config file and rename it boostrap.php.
 */

/**
 * AA Auth bypass mode
 */
Configure :: write('AaAuth.bypass.mode', false); // turns on/off aa auth
Configure :: write('AaAuth.bypass.silent', false); // turns on/off bypass mode warning - bypass mode must also be set to true

/**
 * General settings
 */
Configure :: write('AaAuth.user.model', 'Accounts.Account'); // location of user model
Configure :: write('AaAuth.user.add_method', 'add'); // model method for inserting a new account, usually 'saveAll'
Configure :: write('AaAuth.user.edit_method', 'edit'); // model method for updating a new account, usually 'saveAll'
Configure :: write('AaAuth.user.default_group', 1); // default group id for newly created accounts (usually NOT an admin group)

/**
 * Silent Routes
 * This plugin will surpress permission warnings for the following routes
 * Useful for providing the login form before an action without screaming about permissions
 */
Configure :: write('AaAuth.silent_routes', array('/admin'));

/**
 * Error messages
 */
$auth_errs = array();

# 0 - SUCCESSFUL_RETURN
$auth_errs[0] = "The request was successful.";

# 1 - INVALID_USER_EXCEPTION = 'A&A Service: Invalid user ID'
$auth_errs[1] = "The username/password combination was incorrect.";

# 2 - ACCOUNT_LOCKED_EXCEPTION = 'A&A Service: The user account is locked'
$auth_errs[2] = "This account is locked. Please contact customer service.";

# 3 - CHANGE_PASSWORD_EXCEPTION = 'A&A Service: The user must change their password immediately'
$auth_errs[3] = "The account's password must be reset.";

# 4 - INSUFFICIENT_DATA_EXCEPTION = 'A&A Service: The method called did not receive enough data to process the request'
$auth_errs[4] = "Insufficient data. The request could not be processed.";

# 5 - INVALID_NEW_PASSWORD_EXCEPTION = 'A&A Service: The new password given did not meet the password rules'
$auth_errs[5] = "The new password did not meet the requirements.";

# 6 - ADMIN_PERMISSION_EXCEPTION = 'A&A Service: The user given doe not have sufficient permission to preform the requested action'
$auth_errs[6] = "Permission denied. This account is not allowed to preform that action.";

# 7 - ADMIN_ACCOUNT_LOCKED_EXCEPTION = 'A&A Service: The administrator suer account is locked'
$auth_errs[7] = "This admin account is locked. Please contact customer service.";

# 8 - INVALID_PRIVILEGE_EXCEPTION = 'A&A Service: One of the privileges given is invalid'
$auth_errs[8] = "Invalid privilege error.";

# 9 - PASSWORD_EXPIRED_EXCEPTION = 'A&A Service: The password for the given user has expired'
$auth_errs[9] = "This account's password has expired. Please reset the password.";

# 10 - ACCOUNT_DISABLED_EXCEPTION = 'A&A Service: The account for the given user is disabled'
$auth_errs[10] = "This account has been disabled. Please contact customer service.";

# 11 - ADMIN_ACCOUNT_DISABLED_EXCEPTION = 'A&A Service: The administrator account is disabled'
$auth_errs[11] = "This admin account has been disabled. Please contact customer service.";

# 12 - ADMIN_ACCOUNT_NOT_INITIALIZED_EXCEPTION = 'A&A Service: The administrator account has not been intialized yet'
$auth_errs[12] = "This admin account has not been initialized.";

# 13 - INVALID_TOKEN_EXCEPTION = 'A&A Service: An invalid Single-Sign-on token was given'
$auth_errs[13] = "Invalid Single-Sign-On token. Please try again.";

# 14 - UNEXPECTED_FATAL_EXCEPTION = 'A&A Service: The A&A Service received an unknown fatal error'
$auth_errs[14] = "There was an unexpected error. Please try again.";

# 15 - Failed local account syncronization attempt
$auth_errs[15] = "Failed to synchronize local account.";

# 16 - Failed local account create attempt
$auth_errs[16] = "Failed to localize account.";

# define the config var
Configure :: write('AaAuth.errors', $auth_errs);

?>
