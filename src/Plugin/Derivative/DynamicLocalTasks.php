<?php

/**
 * @file
 * Contains Drupal\moderation_state\Plugin\Derivative\DynamicLocalTasks.
 */

namespace Drupal\moderation_state\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Config\Entity\ConfigEntityTypeInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DynamicLocalTasks extends DeriverBase implements ContainerDeriverInterface {

  use StringTranslationTrait;

  /**
   * The base plugin ID
   *
   * @var string
   */
  protected $basePluginId;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Creates an FieldUiLocalTask object.
   *
   * @param string $base_plugin_id
   *   The base plugin ID.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The translation manager.
   */
  public function __construct($base_plugin_id, EntityTypeManagerInterface $entity_type_manager, TranslationInterface $string_translation) {
    $this->entityTypeManager = $entity_type_manager;
    $this->stringTranslation = $string_translation;
    $this->basePluginId = $base_plugin_id;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $base_plugin_id,
      $container->get('entity_type.manager'),
      $container->get('string_translation')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $this->derivatives = [];

    foreach ($this->moderatableEntityTypes() as $entity_type_id => $entity_type) {
      $this->derivatives["$entity_type_id.moderation_tab"] = [
        'route_name' => "entity.$entity_type_id.moderation",
        'title' => $this->t('Manage moderation'),
          // @todo - are we sure they all have an edit_form?
        'base_route' => "entity.$entity_type_id.edit_form",
        'weight' => 30,
      ] + $base_plugin_definition;
    }

    return $this->derivatives;
  }

  /**
   * Returns an iterable of the entities to which to attach local tasks.
   *
   * @return array
   *   An array of just those entity types we care about.
   */
  protected function moderatableEntityTypes() {
    $entity_types = $this->entityTypeManager->getDefinitions();

    return array_filter($entity_types, function (EntityTypeInterface $type) use ($entity_types) {
      return ($type instanceof ConfigEntityTypeInterface) && ($bundle_of = $type->get('bundle_of')) && $entity_types[$bundle_of]->isRevisionable();
    });
  }
}
