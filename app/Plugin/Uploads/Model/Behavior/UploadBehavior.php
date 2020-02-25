<?php
/**
 * Upload Behavior.
 *
 * Allows a model to act as upload, and upload files.
 *
 * @package Upload.Model.Behavior
 * @author  Iowa Interactive, LLC.
 *
 * @todo Add database file storage
 * @todo Add in virus scans with clam av
 * @todo Check files to make sure they're the type they claim to be
 * @todo Allow setting dimensions on uploads as a part of the upload
 * @todo Add validation rule for file size
 * @todo Add validation rules for images to force users to give us images of specific dimensions or min/max dimensions
 * @todo Add setting to allow overwrite of file
 * @todo upload outside of webroot and create a helper to pull images from outside webroot
 */
class UploadBehavior extends ModelBehavior
{
    /**
     * Array defining required settings to be defined in models that act as upload
     *
     * @var array
     * @access private
     */
    private $_required_settings = array('save_location', 'allowed_types');

    /**
     * behavior setup
     *
     * @param Object $Model  model that actsAs Upload
     * @param array  $config defined in the actAs array of the model loading this behavior
     *
     * @return void
     * @access public
     */
    public function setup(Model $Model, $config = array())
    {
        // Load custom config
        Configure::load('Uploads.config');

        $this->settings[$Model->alias] = (array) $config;

        // Verify all the necessary settings have been made
        $this->_validateSettings($Model);
    }

    /**
     * Validataion rule for file uploads to verify that a file was
     * successfully uploaded
     *
     * @param array $params file upload attributes
     *
     * @return boolean
     * @access public
     */
    public function isUploadedFile($params)
    {
        $val = array_shift($params);

        if ((isset($val['error']) && $val['error'] == 0)
            || (!empty( $val['tmp_name']) && $val['tmp_name'] != 'none')
        )
        {
            return is_uploaded_file($val['tmp_name']);
        }

        return false;
    }

    /**
     * Checks settings made in model and throws an exception if all required
     * settings haven't been made
     *
     * @param Object &$Model model that actsAs Upload
     *
     * @return boolean success
     * @access private
     *
     * @throws Exception - if setting is not defined
     */
    private function _validateSettings(&$Model = null)
    {
        $config =& $this->settings[$Model->alias];

        // Make sure all of the settings we care about are made
        foreach ($config as $settings)
        {
            foreach ($this->_required_settings as $setting)
            {
                if (!isset($settings[$setting]))
                {
                    throw new Exception('Invalid upload settings');
                }
            }
        }

        return true;
    }
}