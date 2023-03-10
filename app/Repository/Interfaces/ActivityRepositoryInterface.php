<?php
namespace App\Repository\Interfaces;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
interface ActivityRepositoryInterface
{
   public function getActivityByDate($month):  Collection;

   public function getClaimActivities($claimid): Collection;
}