<?php

/**
 * @file
 *
 */

namespace Drupal\rsvplist\Plugin\Block;

use Drupal;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Provides the RSVP main block
 *
 * @Block(
 *    id = "rsvp_block",
 *    admin_label = @Translation("The RSVP Block")
 * )
 */
class RSVPBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {


    return \Drupal::formbuilder()->getForm('Drupal\rsvplist\Form\RSVPForm');

//    return [
//      '#type' => 'markup',
//      '#markup' => $this->t('My RSVP List Block')
//    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account) {

    // If viewing a node, get the fully loaded node object.
    $node = Drupal::routeMatch()->getParameter('node');

    if (!(is_null($node))) {
      return AccessResult::allowedIfHasPermission($account, 'view rsvplist');
    }

    return AccessResult::forbidden();
  }
}
