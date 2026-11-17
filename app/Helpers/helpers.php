<?php

use App\Models\AoModel;
use App\Models\ArtWorkFileLinkTemp;
use Illuminate\Support\Facades\Auth;

if (! function_exists('appIsProduction')) {
    function appIsProduction(): bool
    {
        if (! function_exists('app')) {
            return env('APP_ENV', 'production') === 'production';
        }

        return app()->environment('production');
    }
}

// artwork

if (!function_exists('getFileReceivedLinkById')) {
    /**
     * Get the base Eloquent query for files received by the authenticated user.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */

    function getFileReceivedLinkById()
    {
        // if (!Auth::check()) {
        //     return collect();
        // }

        return AoModel::where('assigned_to_role_id', Auth::user()->role_id)->whereNotNull('item_code');
    }

    function getFileReceivedLinkById_NoItemCode()
    {
        // if (!Auth::check()) {
        //     return collect();
        // }

        // return ArtWorkFileLinkTemp::where('assigned_to_role_id', Auth::user()->role_id)
        // ->whereNull('item_code')->orWhere('item_code','');

         return ArtWorkFileLinkTemp::
        whereNull('item_code')->orWhere('item_code','');
        
    }
}
