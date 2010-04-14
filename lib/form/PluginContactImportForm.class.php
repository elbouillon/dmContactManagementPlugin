<?php

/**
 * PluginAddress form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id$
 */
class ContactImportForm extends dmForm
{
  public function setup()
  {
    $this->widgetSchema['loutre'] = new sfWidgetFormInputText();
    $this->validatorSchema['loutre'] = new sfValidatorString(array('required' => true));

    $this->widgetSchema['file'] = new sfWidgetFormInputFile();
    $this->validatorSchema['file'] = new sfValidatorFile(array('required' => true,'mime_types' => array('text/x-vcard')));
  }
}