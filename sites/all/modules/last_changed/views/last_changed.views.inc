<?php
// $Id: last_changed.views.inc,v 1.1 2010/07/01 19:19:13 andrewlevine Exp $

function last_changed_views_data() {
  $data = array(
    'last_changed' => array(
      'table' => array(
        'group' => 'Last Changed',
        'join' => array(
          'node' => array(
            'left_field' => 'nid',
            'field' => 'id',
            'type' => 'LEFT',
            'extra' => array(
              array(
                'field' => 'type',
                'operator' => '=',
                'value' => 'node',
              ),
            ),
          ),
          'comments' => array(
            'left_field' => 'cid',
            'field' => 'id',
            'type' => 'LEFT',
            'extra' => array(
              array(
                'field' => 'type',
                'operator' => '=',
                'value' => 'comment',
              ),
            ),
          ),
          'users' => array(
            'left_field' => 'uid',
            'field' => 'id',
            'type' => 'LEFT',
            'extra' => array(
              array(
                'field' => 'type',
                'operator' => '=',
                'value' => 'user',
              ),
            ),
          ),
        ),
      ),
      'last_changed' => array(
        'title' => t('Last changed'),
        'help' => t('The last time this record changed.'),
        'field' => array(
          'handler' => 'views_handler_field_date',
          'click sortable' => TRUE,
        ),
        'sort' => array(
          'handler' => 'views_handler_sort_date',
        ),
        'filter' => array(
          'handler' => 'views_handler_filter_date',
        ),
      )
    ),
  );
  return $data;
}