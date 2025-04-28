<?php

/**
 * @file
 * Contains the RSVP Enabler Service
 */
namespace Drupal\rsvplist;

use Drupal\Core\Database\Connection;
use Drupal\node\Entity\Node;
use Exception;

class EnablerService {

  protected $database_connection;

  public function __construct(Connection $connection) {
    $this->database_connection = $connection;
  }

  /**
   * Sets an individual node to be RSVP enabled.
   *
   * @param \Drupal\node\Entity\Node $node
   * @throws Exception
   * @return false|void
   */
  public function isEnabled(Node &$node) {
    if ($node->isNew()) {
      return FALSE;
    }
    try {
      $select = $this->database_connection->select('rsvplist_enabled', 're',);
      $select->fields('re', ['nid']);
      $select->condition('nid', $node->id());
      $results = $select->execute();

      return !(empty($results->fetchCol()));
    }
    catch (\Exception $e) {
      \Drupal::Messenger()->addError(
        t('Unable to determine RSVP settings at this time. Please try agin.' . $e->getMessage())
      );
      return NULL;
    }
  }

  /**
   * Deletes RSVP enabled settings for an individual node.
   *
   * @param Node $node
   */
    public function delEnabled(Node &$node) {
      try {
        $delete = $this->database_connection->delete('rsvplist_enabled');
        $delete->condition('nid', $node->id());
        $result = $delete->execute();
      }
      catch (\Exception $e) {
        \Drupal::Messenger()->addError(
          t('Unable to save RSVP settings at this time. Please try agin.')
        );
      }
    }

}
