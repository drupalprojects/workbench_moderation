<?php

/**
 * @file
 * Contains \Drupal\moderation_state\EntityOperations.
 */

namespace Drupal\moderation_state;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\node\Entity\Node;

/**
 * Defines a class for reacting to entity events.
 */
class EntityOperations {

  /**
   * @var \Drupal\moderation_state\ModerationInformationInterface
   */
  protected $moderationInfo;

  /**
   * Constructs a new EntityOperations object.
   *
   * @param \Drupal\moderation_state\ModerationInformationInterface $moderation_info
   *   Entity type manager service.
   */
  public function __construct(ModerationInformationInterface $moderation_info) {
    $this->moderationInfo = $moderation_info;
  }

  /**
   * Acts on an entity and set published status based on the moderation state.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity being saved.
   */
  public function entityPresave(EntityInterface $entity) {
    if ($entity instanceof ContentEntityInterface && $this->moderationInfo->isModeratableEntity($entity)) {
      // @todo write a test for this.
      if ($entity->moderation_state->entity) {
        // This is *probably* not necessary if configuration is setup correctly,
        // but it can't hurt.
        $entity->setNewRevision(TRUE);
        $published_state = $entity->moderation_state->entity->isPublishedState();
        // A newly-created revision is always the default revision, or else
        // it gets lost.
        $entity->isDefaultRevision($entity->isNew() || $published_state);
        // Only nodes have a concept of published.
        // @todo This should also get split off to a per-entity tagged service.
        if ($entity instanceof Node) {
          $entity->setPublished($published_state);
        }
      }
    }
  }
}
