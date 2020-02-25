<?php
/**
 * Page model
 *
 * Extends the AppModel. Responsible for managing page data.
 *
 * @package Pages.Model
 * @author  Iowa Interactive, LLC.
 */
class Page extends PagesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Page';

    var $validate = array(
        'title' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'slug' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
    );

    /**
     * beforeValidate method
     *
     * @param array $options options array
     *
     * @return void
     */
    public function beforeValidate($options = array())
    {
        // create the url slug
        $this->data['Page']['slug'] = GenLib::makeSlug($this->data['Page']['title']);

        return true;
    }

    /**
     * getPageBySlug method
     *
     * @param str $slug expecting page url slug
     *
     * @return array page data array
     */
    public function getPageBySlug($slug = '')
    {
        return $this->find('first', array('conditions' => array('enabled' => 1, 'slug' => $slug)));
    }

    /**
     * getSlugs method
     *
     * @return page data array
     */
    public function getSlugs()
    {
        $this->displayField = 'slug';
        return $this->find('list', array('conditions' => array('enabled' => 1)));
    }

    /**
     * getPageById method
     *
     * @param int $id expecting page ID
     *
     * @return array page data array
     */
    public function getPageById($id = null)
    {
        // contain
        $contain = array('Program');

        // return results
        return $this->find('first', array('conditions' => array('Page.id' => $id), 'contain' => $contain));
    }
}