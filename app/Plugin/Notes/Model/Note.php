<?php
/**
 * Note Model
 *
 * Extends the AppModel. Responsible for notes data.
 *
 * @category Notes
 * @package  Notes.Model
 * @author   Iowa Interactive, LLC.
 */
class Note extends NotesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Note';

    public $belongsTo = array(
        'Account' => array(
            'className' => 'Accounts.Account',
            'foreignKey' => 'account_id',
        )
    );

    /**
     * Validation rules
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'foreign_plugin' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'The foreign plugin was null.'
            ),
        ),
        'foreign_obj' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'The foreign object was null.'
            ),
        ),
        'foreign_key' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'The foreign key was null.'
            ),
        ),
        'note' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => array('Please enter a note to save this data.'),
            ),
        ),
    );

    /**
    * Returns a list of all the notes that have been applied
    * we are passing the foreign_obj and the foreign_key the limit is the defult records returned.
    *
    * @param string $foreign_obj This will pass the plugin name which are set to null
    * @param int    $foreign_key This will pass the users id which are set to null
    * @param int    $limit       This will be the default of total notes returned.
    *
    * @return array
    * @access public
    */
    public function getNotesForObject($foreign_obj = null, $foreign_key = null, $limit = 10)
    {
         return $this->find(
             'all',
             array(
                'conditions' => array('Note.foreign_obj' => $foreign_obj, 'Note.foreign_key' => $foreign_key),
                'order' => array('Note.created DESC', 'Note.id DESC'),
                'limit' => $limit,
                'contain' => array('Account')
             )
         );
    }
    /**
     * Details Method
     *
     * @param int   $id       The Note id
     * @param array $contains contains array
     *
     * @return void
     */
    public function details($id, $contains = false)
    {
        try
        {
            $this->recursive = 1;
            return $this->find(
                'first',
                array(
                    'conditions' => array(
                        'Note.id' => $id
                    ),
                    'contain' => array(
                        'Account' => array('label')
                    )
                )
            );
        }
        catch (Exception $e)
        {
            throw new Exception(sprintf(__('Note (%s) could not be found'), $id));
        }
    }

    /**
    * This gets a note count based on the total count based on the return of the find.
    *
    * @param string $foreign_obj This is the plugin that is passed. The value is set to a default of null.
    * @param int    $foreign_key This is a userid which by defualt we are setting to null.
    *
    * @return count
    * @access public
    */
    public function noteCount($foreign_obj = null, $foreign_key = null)
    {
        return $this->find(
            'count',
            array(
                'conditions' => array(
                    'Note.foreign_obj' => $foreign_obj,
                    'Note.foreign_key' => $foreign_key
                )
            )
        );
    }

    /**
    * Adds a system based note.
    *
    * @param int    $account_id     Account id of admin.
    * @param string $foreign_plugin Plugin name.
    * @param string $foreign_obj    Object name.
    * @param int    $foreign_key    Key of object record.
    * @param string $note           Note string being added.
    *
    * @return boolean
    * @access public
    */
    public function addNote($account_id = null, $foreign_plugin = null, $foreign_obj = null, $foreign_key = null, $note = null)
    {
        // format the data
        $data['Note']['account_id'] = $account_id;
        $data['Note']['foreign_plugin'] = $foreign_plugin;
        $data['Note']['foreign_obj'] = $foreign_obj;
        $data['Note']['foreign_key'] = $foreign_key;
        $data['Note']['note'] = $note;

        // add the note
        if (!$this->add($data))
        {
            throw new Exception('Note could not be saved.');
        }
    }

    /**
    * Adds a system based note.
    *
    * @param int    $account_id     Account id of admin.
    * @param string $foreign_plugin Plugin name.
    * @param string $foreign_obj    Object name.
    * @param int    $foreign_key    Key of object record.
    * @param string $note           Note string being added.
    *
    * @return boolean
    * @access public
    */
    public function sysNote($account_id = null, $foreign_plugin = null, $foreign_obj = null, $foreign_key = null, $note = null)
    {
        try
        {
            // append system note to note
            $note = sprintf('[system] %s', $note);

            // add the note
            $this->addNote($account_id, $foreign_plugin, $foreign_obj, $foreign_key, $note);
        }
        catch (Exception $e)
        {
            throw new Exception('System note could not be saved');
        }

        return true;
    }
}