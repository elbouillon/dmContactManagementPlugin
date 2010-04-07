<?php
/**
 * Phone number components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class phoneNumberComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->phoneNumberPager = $this->getPager($query);
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->phoneNumber = $this->getRecord($query);
  }


}
