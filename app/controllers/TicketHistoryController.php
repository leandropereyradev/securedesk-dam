<?php

namespace app\controllers;

use app\models\TicketHistoryModel;

class TicketHistoryController
{
  public static function logChange(array $data): bool
  {
    return TicketHistoryModel::logChange($data);
  }

  public static function listHistory(int $ticketId): array
  {
    return TicketHistoryModel::getHistory($ticketId);
  }
}
