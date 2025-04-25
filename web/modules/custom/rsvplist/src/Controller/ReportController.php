<?php

/**
 * @file
 * Provide site administrators with a list of all the RSVP List signups
 * so they know who is attending their event.
 */

namespace Drupal\rsvplist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
class ReportController extends ControllerBase {

  /**
   * Gets and returns all RSVPs for all nodes.
   * These are returned as an associative array, which each row containing
   * the username, the node title, and email of RSVP
   * @return array|null
   */
  protected function load(): ?array {
    try {
      $database = \Drupal::database();
      $select_query = $database->select('rsvplist', 'r');


      // Join the user table, so we can get the entry creator's username
      $select_query->join('users_field_data', 'u', 'r.uid = u.uid');
      // Join the node table, so we can get the event's name
      $select_query->join('node_field_data', 'n', 'r.nid = n.nid');

      // Select these specific fields for the output
      $select_query->addField('u', 'name', 'username');
      $select_query->addField('n',  'title');
      $select_query->addField('r', 'mail');
      return $select_query->execute()->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
      \Drupal::messenger()->addError(t('Unable to access the database at
        this time. Please try again later.'));

      return NULL;
    }
  }

  /**
   * Creates the RSVPList usage page
   *
   * @return array
   *  Render array for the RSVPList report output
   */

  public function report(): array {
    $content = [];

    $content['message'] = [
      '#markup' => t('Below is a list of all the Event RSVPs including
        username, email address and the name of the event they will be attending.'),
    ];

    $headers = [
      t('Username'),
      t('Event'),
      t('Email'),
    ];

    // Because load() returns an associtative array with each table row
    // as its own array, we can simply define the HTML table rows like this:
    $table_rows = $this->load();

    // However, as an example, if load() did not return the results in
    // a structure compatible with what we need, we could populate the
    // $table_rows variable like so
    /**
      $table_rows = [];
      // Load the entries from database.
      $rsvp_entries = $this->load();

      // go through each entry and add it to $rows
      // Ultimately each array will be rendered as a row in an HTML table
      foreach ($rsvp_entries as $entry) {
        $table_rows[] = $entry;
      }
    */

    // Creat the render array for rendering an HTML table.
    $content['table'] = [
      '#theme' => 'table',
      '#header' => $headers,
      '#rows' => $table_rows,
      '#empty' => t('No entries available.'),
    ];

    // Do not cache this page by setting the max-age to 0.
    $content['#cache']['max-age'] = 0;

    // Return the populated render array
    return $content;
  }
}
