<?php
namespace App\Repository\Interfaces;

use Illuminate\Support\Collection;

interface NotificationRepositoryInterface
{
   public function getNotifications(): Collection;

   public function getReadNotifications(): Collection;

   public function getUserNotifCount(): array;
}