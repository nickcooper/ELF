<?php
/**
 * Variants Controller
 *
 * @category License
 * @package  App.Controller
 * @author   Iowa Interactive, LLC.
 */
class VariantsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Variants';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'Licenses.License',
        'Licenses.LicenseVariant',
        'Licenses.Variant',
    );

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('ForeignObject');

    /**
     * add method
     *
     * @param int $license_id Expecting license record id
     *
     * @return void
     */
    public function add($license_id = null)
    {
        try
        {
            // set the id
            if (! $license_id && isset($this->foreign_key))
            {
                $license_id = $this->foreign_key;
            }

            if (! $license_id)
            {
                throw new Exception(__('Invalid license ID. Unable to add variant.'));
            }

            // process form post
            if ($this->request->is('post') || $this->request->is('put'))
            {
                $this->request->data['LicenseVariant']['license_id'] = $license_id;
                $variant_id = $this->request->data['Variant']['id'];

                // add variant to license
                if (! $this->License->addVariant($license_id, $variant_id, $this->request->data))
                {
                    throw new Exception(__('Unable to update license number with variant.'));
                }

                // passed
                $this->Session->setFlash(__('Variant was added to license.'));
                $this->redirect(base64_decode($this->request->params['named']['return']));
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }

        $this->set('parent', $license_id);
        $this->set('variants', $this->Variant->getList());
    }

    /**
     * delete method
     *
     * @param int $license_variant_id expecting license variant record id
     *
     * @return void
     *
     * @todo Remove child upload records.
     */
    public function delete($license_variant_id)
    {
        $licenseVariant = $this->LicenseVariant->details($license_variant_id, array('Variant'));
        $license_id = $licenseVariant['LicenseVariant']['license_id'];

        if (! $this->License->removeVariant($license_id, $license_variant_id))
        {
            $this->Session->setFlash(__('Unable to remove variant.'));
        }
        else
        {
            $this->Session->setFlash(sprintf(__('%s variant has been removed.'), $licenseVariant['Variant']['abbr']));
        }

        $this->redirect($this->request->params['named']['return']);
    }
}