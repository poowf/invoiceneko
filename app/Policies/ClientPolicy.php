<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
    }

    public function before($user, $ability)
    {
    }

    public function index(User $user)
    {
        return $user->can('view-client', Client::class);
    }

    /**
     * Determine whether the user can view the client.
     *
     * @param \App\Models\User   $user
     * @param \App\Models\Client $client
     *
     * @return mixed
     */
    public function view(User $user, Client $client)
    {
        $userCompanies = $user->companies()->pluck('companies.id');

        return $userCompanies->contains($client->company_id) && $user->can('view-client', $client);
    }

    /**
     * Determine whether the user can create clients.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-client', Client::class);
    }

    /**
     * Determine whether the user can update the client.
     *
     * @param \App\Models\User   $user
     * @param \App\Models\Client $client
     *
     * @return mixed
     */
    public function update(User $user, Client $client)
    {
        $userCompanies = $user->companies()->pluck('companies.id');

        return $userCompanies->contains($client->company_id) && $user->can('update-client', $client);
    }

    /**
     * Determine whether the user can delete the client.
     *
     * @param \App\Models\User   $user
     * @param \App\Models\Client $client
     *
     * @return mixed
     */
    public function delete(User $user, Client $client)
    {
        $userCompanies = $user->companies()->pluck('companies.id');

        return $userCompanies->contains($client->company_id) && $user->can('delete-client', $client);
    }
}
