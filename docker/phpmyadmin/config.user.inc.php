<?php

/* Server settings */
$i = 1;
// Dev mutualisé dedale
$cfg['Servers'][$i]['host']            = $_ENV['PMA_HOST'];
$cfg['Servers'][$i]['verbose']         = 'Mutualisé dev - devmutmys701-adm';
$cfg['Servers'][$i]['port']            = $_ENV['PMA_PORT'];
$cfg['Servers'][$i]['auth_type']       = 'config';
$cfg['Servers'][$i]['user']            = $_ENV['PMA_USER'];
$cfg['Servers'][$i]['password']        = $_ENV['PMA_PASSWORD'];
$cfg['Servers'][$i]['connect_type']    = 'tcp';
$cfg['Servers'][$i]['compress']        = false;
$cfg['Servers'][$i]['AllowNoPassword'] = true;
$cfg['Servers'][$i]['platform']        = 'dev';

// VALIDATION dedale
++$i;
$cfg['Servers'][$i]['host']         = 'valmutmys601-adm.dedale.tf1.fr';
$cfg['Servers'][$i]['port']         = '4363';
$cfg['Servers'][$i]['connect_type'] = 'tcp';
$cfg['Servers'][$i]['compress']     = false;
$cfg['Servers'][$i]['auth_type']    = 'config';
$cfg['Servers'][$i]['user']         = 'val601_usr';
$cfg['Servers'][$i]['password']     = 'val601pwd';
$cfg['Servers'][$i]['verbose']      = 'Validation - valmutmys601-adm';
$cfg['Servers'][$i]['platform']     = 'validation';
