<?php

module_load_include('php', 'workbench_states', 'plugins/export_ui/workbench_base_ui.class');

class workbench_events_ui extends workbench_base_ui {

  function edit_form(&$form, &$form_state) {
  // Get the basic edit form
  parent::edit_form($form, $form_state);

    $form['target_state'] = array(
      '#type' => 'select',

      '#options' => workbench_moderation_state_labels(),
        '#default_value' => $form_state['item']->target_state,
      '#title' => t('Target State'),
      '#description' => t(""),
    );

    $form['origin_states'] = array(
      '#type' => 'checkboxes',
      '#options' => workbench_moderation_state_labels(),
      '#default_value' => $form_state['item']->origin_states,
      '#title' => t('Origin States'),
      '#description' => t(""),
    );
  }

  /**
   * @todo, need to validate the origin and target states.
   */
  function edit_form_basic_validate($form, &$form_state) {
    parent::edit_form_validate($form, $form_state);
    // Need to validate target and origin_states

// if (preg_match("/[^A-Za-z0-9 ]/", $form_state['values']['category'])) {
     // form_error($form['category'], t('Categories may contain only alphanumerics or spaces.'));
   // }
  }

  function edit_form_submit(&$form, &$form_state) {
    parent::edit_form_submit($form, $form_state);
    //$form_state['item']->target_state = $form_state['values']['target_state'];
  }
}
