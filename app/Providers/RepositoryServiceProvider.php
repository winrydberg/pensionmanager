<?php

namespace App\Providers;

use App\Repository\Eloquent\ActivityRepository;
use Illuminate\Support\ServiceProvider;
use App\Repository\Interfaces\EloquentRepositoryInterface; 
use App\Repository\Interfaces\DepartmentRepositoryInterface; 
use App\Repository\Interfaces\UserRepositoryInterface; 
use App\Repository\Interfaces\CompanyRepositoryInterface; 
use App\Repository\Interfaces\RegionRepositoryInterface; 
use App\Repository\Eloquent\UserRepository; 
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\ClaimRepository;
use App\Repository\Eloquent\DepartmentRepository; 
use App\Repository\Eloquent\RegionRepository; 
use App\Repository\Eloquent\CompanyRepository;
use App\Repository\Eloquent\CustomerRepository;
use App\Repository\Eloquent\IssueRepository;
use App\Repository\Eloquent\NotificationRepository;
use App\Repository\Eloquent\ReportRepository;
use App\Repository\Eloquent\SchemeRepository;
use App\Repository\Interfaces\ActivityRepositoryInterface;
use App\Repository\Interfaces\ClaimRepositoryInterface;
use App\Repository\Interfaces\CustomerRepositoryInterface;
use App\Repository\Interfaces\IssueRepositoryInterface;
use App\Repository\Interfaces\NotificationRepositoryInterface;
use App\Repository\Interfaces\ReportRepositoryInterface;
use App\Repository\Interfaces\SchemeRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(RegionRepositoryInterface::class, RegionRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(SchemeRepositoryInterface::class, SchemeRepository::class);
        $this->app->bind(ClaimRepositoryInterface::class, ClaimRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(IssueRepositoryInterface::class, IssueRepository::class);
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
        // $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
    }
}