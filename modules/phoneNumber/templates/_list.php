<?php // Vars: $phoneNumberPager

echo $phoneNumberPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($phoneNumberPager as $phoneNumber)
{
  echo _open('li.element');

    echo $phoneNumber;

  echo _close('li');
}

echo _close('ul');

echo $phoneNumberPager->renderNavigationBottom();