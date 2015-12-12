<?php

/**
 * @file
 * Contains Drupal\moderation_state\ModerationInformationInterface.
 */

namespace Drupal\moderation_state;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormInterface;

/**
 * Interface for moderation_information service.
 */
interface ModerationInformationInterface {

  /**
   * Determines if an entity is one we should be moderating.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity we may be moderating.
   *
   * @return bool
   *   TRUE if this is an entity that we should act upon, FALSE otherwise.
   */
  public function isModeratableEntity(EntityInterface $entity);

  /**
   * Determines if an entity type/bundle is one that will be moderated.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition to check.
   * @param string $bundle
   *   The bundle to check.
   *
   * @return bool
   *   TRUE if this is a bundle we want to moderate, FALSE otherwise.
   */
  public function isModeratableBundle(EntityTypeInterface $entity_type, $bundle);

  /**
   * Filters an entity list to just bundle definitions for revisionable entities.
   *
   * @param EntityTypeInterface[] $entity_types
   *   The master entity type list filter.
   *
   * @return array
   *   An array of only the config entities we want to modify.
   */
  public function selectRevisionableEntityTypes(array $entity_types);

  /**
   * Determines if config entity is a bundle for entities that may be moderated.
   *
   * This is the same check as exists in selectRevisionableEntityTypes(), but
   * that one cannot use the entity manager due to recursion and this one
   * doesn't have the entity list otherwise so must use the entity manager. The
   * alternative would be to call getDefinitions() on entityTypeManager and use
   * that in a sub-call, but that would be unnecessarily memory intensive.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to check.
   *
   * @return bool
   *   TRUE if we want to add a Moderation operation to this entity, FALSE
   *   otherwise.
   */
  public function isBundleForModeratableEntity(EntityInterface $entity);

  /**
   * Determines if this form is for a moderated entity.
   *
   * @param \Drupal\Core\Form\FormInterface $form_object
   *   The form definition object for this form.
   *
   * @return bool
   *   TRUE if the form is for an entity that is subject to moderation, FALSE
   *   otherwise.
   */
  public function isModeratedEntityForm(FormInterface $form_object);

  /**
   * Determines if the form is the bundle edit of a revisionable entity.
   *
   * The logic here is not entirely clear, but seems to work. The form- and
   * entity-dereference chaining seems excessive but is what works.
   *
   * @param \Drupal\Core\Form\FormInterface $form_object
   *   The form definition object for this form.
   *
   * @return bool
   *   True if the form is the bundle edit form for an entity type that supports
   *   revisions, false otherwise.
   */
  public function isRevisionableBundleForm(FormInterface $form_object);

}
