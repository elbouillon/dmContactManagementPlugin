<?php
/**
 * Email components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class emailComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->emailPager = $this->getPager($query);
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->email = $this->getRecord($query);
  }


}
