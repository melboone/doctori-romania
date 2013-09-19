<?php
// $Id: last_changed.admin.inc,v 1.1 2010/07/01 19:19:12 andrewlevine Exp $

/**
 * Form for /admin/settings/last_changed page.
 */
function last_changed_settings_form() {
  $form = array();

  $last_changed_types = module_invoke_all('last_changed_types');
  $form['last_changed_types'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Keep track of changes to'),
    '#default_value' => variable_get('last_changed_types', drupal_map_assoc(array('node', 'comment', 'user'))),
    '#options' => $last_changed_types,
    '#description' => t('If a type is checked, a timestamp will be kept of the latest change for all records of that type.')
  );

  $options = array(0 => 'Never');
  foreach (array(60, 1800, 3600, 43200, 86400, 604800, 1209600, 2629743, 31556926) as $seconds) {
    $options[$seconds] = format_interval($seconds, 1) . ' ' . t('ago');
  }
  $form['last_changed_expiration'] = array(
    '#type' => 'select',
    '#title' => t('Remove records of last changes more than'),
    '#default_value' => variable_get('last_changed_expiration', 0),
    '#options' => $options,
    '#description' => t('All records of latest changes more than this time ago will be deleted.'),
  );

  return system_settings_form($form);
}
