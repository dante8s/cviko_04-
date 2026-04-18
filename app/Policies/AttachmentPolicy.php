<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttachmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Attachment $attachment): bool
    {
        return $attachment->attachable->user_id === $user->id;
    }

    public function create(User $user, Note $note): bool
    {
        return $note->user_id === $user->id || $user->isAdmin();
    }

    public function delete(User $user, Attachment $attachment): bool
    {
        return $attachment->attachable->user_id === $user->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Attachment $attachment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Attachment $attachment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Attachment $attachment): bool
    {
        return false;
    }
}
