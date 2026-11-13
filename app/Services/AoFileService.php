<?php

namespace App\Services;

use Exception;

class AoFileService
{
    /**
     * Get single received file by ID with authorization/scope checks.
     */
    public function getReceivedFileById($id)
    {
        return getFileReceivedLinkById()->findOrFail($id);
    }
}