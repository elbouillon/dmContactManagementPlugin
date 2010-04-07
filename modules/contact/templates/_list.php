<?php // Vars: $contactPager

echo $contactPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($contactPager as $contact)
{
  echo _open('li.element');

    echo _link($contact);

  echo _close('li');
}

echo _close('ul');

echo $contactPager->renderNavigationBottom();