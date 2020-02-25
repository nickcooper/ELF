<?php
/**
 * Upload Model
 *
 * Responsible for managing upload data.
 *
 * @package Uploads.Model
 * @author  Iowa Interactive, LLC.
 */
class Upload extends UploadsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Upload';

    /**
     * Display Field
     *
     * @var String
     * @access public
     */
    public $displayField = 'label';

    /**
     * virtual field for full web address of file
     */
    public $virtualFields = array(
        'web_path' => '(CASE WHEN file_name REGEXP "[a-zA-Z0-9]" THEN CONCAT("/", file_path, "/", file_name) ELSE "/img/photos/default-image.png" END)'
    );

    /**
     * Validation rules
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'label' => array(
            'notempty' => array(
                'rule'    => array('notempty'),
                'message' => 'Please enter a description when uploading a file.'),
        ),
    );

    /**
     * beforeSave Callback
     *
     * Verify file(s) if files were successfully uploaded
     *
     * @param array $options options array
     *
     * @return boolean success
     * @access public
     */
    public function beforeSave($options = array())
    {
        // we don't know what the array key is so we have to use a loop
        foreach ($this->data as $key => $data)
        {
            // skip files that were not uploaded (error code 4)
            if ($data['file']['error'] == 4)
            {
                continue; // skip to the next file
            }

            // validate there was a file size
            if (!GenLib::isData($data, 'file', array('size')))
            {
                return false;
            }

            // define the foreign obj
            $foreign_plugin = $data['foreign_plugin'];
            $foreign_obj    = $data['foreign_obj'];
            $foreign_key    = $data['foreign_key'];

            // set the identifier
            $data['identifier'] = $key;

            // load the foreign_obj model
            $ForeignModel = ClassRegistry::init(sprintf('%s.%s', $foreign_plugin, $foreign_obj));

            // get the upload configuration
            $configuration = $ForeignModel->actsAs['Uploads.Upload'][$key];
            //debug($configuration);

            // move the tmp file and include the new file data for save
            $this->data[$key] = array_merge($data, $this->_createUpload($data, $configuration));
            unset($this->data[$key]['file']);

            if (!isset($this->data[$key]['file_size']) || !$this->data[$key]['file_size'])
            {
                // fail
                return false;
            }

            // check for hasOne association in config so we can replace existing records
            if (isset($configuration['association']) && $configuration['association']['hasOne'])
            {
                try {
                    // delete any existing records
                    parent::deleteAll(
                        array(
                            sprintf('%s.foreign_obj', $key) => $foreign_obj,
                            sprintf('%s.foreign_key', $key) => $foreign_key,
                            sprintf('%s.identifier', $key) => $key,
                        )
                    );
                }
                catch (Exception $e)
                {
                    throw $e;
                }
            }
        }

        return parent::beforeSave($options);
    }

    /**
     * _createUpload Callback
     *
     * @param array $data   Results from the find
     * @param bool  $config true if this model was the model that the query originated on
     *
     * @return array
     * @access public
     */
    private function _createUpload($data = array(), $config = array())
    {
        if (empty($data['file']['size']))
        {
            return array();
        }

        $save_location = $config['save_location'];
        $ext = pathinfo($data['file']['name'], PATHINFO_EXTENSION);
        $filename = sprintf('%s.%s', GenLib::generateUUID(), $ext);
        $full_save_location = WWW_ROOT.$save_location.DS.$filename;

        // Move tmp file into save location
        move_uploaded_file($data['file']['tmp_name'], $full_save_location);

        $retval = array(
            'file_path'      => $save_location,  // file_path is always relative to webroot
            'file_name'      => $filename,
            'file_size'      => $data['file']['size'],
            'file_ext'       => $ext,
            'mime_type'      => $data['file']['type'],
        );

        return $retval;
    }

    /**
     * isOwnerOrManager method
     *
     * checks to see if Auth user has access to data
     *
     * @param int $id Upload id
     *
     * @return bool
     */
    public function isOwnerOrManager($id = null)
    {
        if (!$id)
        {
            return false;
        }

        $upload = $this->findById($id, array('foreign_plugin', 'foreign_obj', 'foreign_key'));

        $ForeignModel = ClassRegistry::init(sprintf('%s.%s', $upload['Upload']['foreign_plugin'], $upload['Upload']['foreign_obj']));

        return $ForeignModel->isOwnerOrManager($upload['Upload']['foreign_key']);
    }
}