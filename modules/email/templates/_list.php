<?php // Vars: $emailPager

echo $emailPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($emailPager as $email)
{
  echo _open('li.element');

    echo $email;

  echo _close('li');
}

echo _close('ul');

echo $emailPager->renderNavigationBottom();