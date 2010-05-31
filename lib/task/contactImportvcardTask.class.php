<?php

class contactImportvcardTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
    new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    // add your own options here
    ));

    $this->namespace        = 'contact';
    $this->name             = 'import-vcard';
    $this->briefDescription = 'Get a Vcard file and try to import contact from it in the DB';
    $this->detailedDescription = <<<EOF
The [contact:import-vcard|INFO] task does things.
Call it with:

  [php symfony contact:import-vcard|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    // instantiate a parser object
    $parse = new Contact_Vcard_Parse();

    // parse it
    $data = $parse->fromFile(sfConfig::get('sf_upload_dir') . DIRECTORY_SEPARATOR . 'contacts.vcf');

    // output results
    $contact_collection = new Doctrine_Collection('contact');
    $company_collection = new Doctrine_Collection('company');

    foreach($data as $contact)
    {
      print_r($contact);
      $vcard = new vcard($contact);

      if($vcard->isCompany())
      {
        // it's a company
//        $db_contact = new Company();
//        $db_contact->merge($vcard->getCompanyData());
//        $company_collection->add($db_contact);
        print_r($vcard->getCompanyData());
      }
      else
      {
        // it's a contact
//        $db_contact = new Contact();
//        $db_contact->merge($vcard->getContactData());
//        $contact_collection->add($db_contact);

        print_r($vcard->getContactData());
      }
    }

//    $company_collection->save();
//    $contact_collection->save();
  }
}

/**
 * Parse a vcf file generated from Google Apps only
 * 
 * @see http://pear.php.net/manual/fr/package.fileformats.contact-vcard-parse.data.php
 */
class vcard
{
  private $vcards = array();
  private $vcardsToGdata = array(
  'HOME' => 'home',
  'HOME_FAX' => 'home_fax',
  'CELL' => 'mobile',
  'INTERNET' => 'other',
  'WORK_FAX' => 'work_fax',
  'WORK'     => 'work',
  );

  public function  __construct(array $vcard_file)
  {
    $this->vcards = $vcard_file;
  }

  /**
   *
   * @return string|null depand on value
   */
  public function getName()
  {
    return $this->vcards['FN'][0]['value'][0][0];
  }

  public function getFirstName()
  {
    return $this->vcards['N'][0]['value'][1][0];
  }

  public function getLastName()
  {
    return $this->vcards['N'][0]['value'][0][0];
  }
  
  public function getOrgName()
  {
    return $this->vcards['ORG'][0]['value'][0][0];
  }

  public function isCompany()
  {
    return $this->getName() === "null" && strlen($this->getOrgName()) != 0;
  }

  public function getContactData()
  {
    return array(
      'firstname' => $this->getFirstName(),
      'lastname' => $this->getLastName(),
      'PhoneNumbers' => $this->getTels(),
      'Addresses' => $this->getAddresses(),
      'Emails' => $this->getEmails()
    );
  }

  public function getCompanyData()
  {
    return array(
      'name' => $this->getOrgName(),
      'PhoneNumbers' => $this->getTels(),
      'Addresses' => $this->getAddresses(),
      'Emails' => $this->getEmails()
    );
  }

  public function getEmails()
  {
    $vtels = $this->vcards['EMAIL'];

    foreach ($vtels as $tel)
    {
      $tels[] = $this->parseTelData($tel);
    }

    return $tels;
  }

  public function getTels()
  {
    $vtels = $this->vcards['TEL'];

    foreach ($vtels as $tel)
    {
      $tels[] = $this->parseTelData($tel);
    }

    return $tels;
  }

  private function parseTelData($tel)
  {
    return array(
    'type'    => $this->convertTypeToGdata(implode('_', $tel['param']['TYPE'])),
    'number'  => $tel['value'][0][0]
    );
  }

  public function getAddresses()
  {
    $vaddresses = $this->vcards['ADR'];

    foreach ($vaddresses as $address)
    {
      $addresses[] = $this->parseAddressData($address);
    }

    return $addresses;
  }

  /**
   *
   * @param array $address
   * @return array
   */
  private function parseAddressData($address)
  {
    return array(
    'type'    => $this->convertTypeToGdata($address['param']['type'][0]),
    'address' => implode(' ', $address['value'][2]),
    'zipcode' => $address['value'][5],
    'city'    => $address['value'][3],
    'country' => $address['value'][6]
    );
  }

  private function convertTypeToGdata($type)
  {
    if(!isset($this->vcardsToGdata[$type]))
    {
      throw new dmException(sprintf('Vcard type "%s" is not managed by the importer', $type));
    }

    return $this->vcardsToGdata[$type];
  }
}
