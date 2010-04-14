<?php
/**
 * Contact actions
 * 
 */
class contactActions extends myFrontModuleActions
{

  /**
   * @param dmWebRequest $request 
   * 
   */
  public function executeImport(dmWebRequest $request)
  {
    $form = new ContactImportForm();
    
    if ($request->hasParameter($form->getName()))
    {
      $data = $request->getParameter($form->getName());
      $form->bind($data, $request->getFiles($form->getName()));
      if ($form->isValid())
      {
        //$form->save();
        $this->getUser()->setFlash('contact_form_valid', true);
        $this->getService('dispatcher')->notify(new sfEvent($this, 'contact_import.saved', array(
          'contact' => $form->getObject()
        )));
        $this->redirectBack();
      }
    }

    $this->form = $this->forms['ContactImport'];
  }
}
