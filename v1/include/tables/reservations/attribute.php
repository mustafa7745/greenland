<?php
require_once 'post_data.php';


class ReservationsAttribute
{
  public $name = "Reservation";
  public $table_name = "reservations";
  public $id = "id";
  public $deliveryManId = "deliveryManId";
  public $acceptStatus = "acceptStatus";
  public $acceptanceId = "acceptanceId";
  public $createdAt = "createdAt";
  public $updatedAt = "updatedAt";
  //////////
  public $WAIT_TO_ACCEPT_RESERVED_STATUS = 0;
  public $ACCEPTED_RESERVED_STATUS = 1;
}
