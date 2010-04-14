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
	$data = $parse->fromFile(sfConfig::get('upload_dir') . DIRECTORY_SEPARATOR . 'contacts.vcf');

	// output results
	echo '<pre>';
	print_r($data);
	echo '</pre>';
  }
}
