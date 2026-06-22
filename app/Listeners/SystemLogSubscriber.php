<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemLog;

class SystemLogSubscriber
{
    /**
     * Determine if we should log for the current user.
     */
    protected function shouldLog($user)
    {
        if (!$user) {
            return false;
        }

        // Only log for specific roles
        return $user->hasAnyRole(['super_admin', 'admin', 'officer', 'mental_officer']);
    }

    /**
     * Get the primary role of the user for logging purposes.
     */
    protected function getPrimaryRole($user)
    {
        $roles = $user->getRoleNames();
        if ($roles->contains('super_admin')) return 'super_admin';
        if ($roles->contains('admin')) return 'admin';
        if ($roles->contains('officer')) return 'officer';
        if ($roles->contains('mental_officer')) return 'mental_officer';
        return $roles->first() ?? 'user';
    }

    /**
     * Handle user login events.
     */
    public function handleUserLogin(Login $event)
    {
        if ($this->shouldLog($event->user)) {
            SystemLog::create([
                'user_id' => $event->user->id,
                'role' => $this->getPrimaryRole($event->user),
                'action' => 'login',
                'ip_address' => request()->ip(),
            ]);
        }
    }

    /**
     * Handle user logout events.
     */
    public function handleUserLogout(Logout $event)
    {
        if ($this->shouldLog($event->user)) {
            SystemLog::create([
                'user_id' => $event->user->id,
                'role' => $this->getPrimaryRole($event->user),
                'action' => 'logout',
                'ip_address' => request()->ip(),
            ]);
        }
    }

    /**
     * Handle eloquent events.
     */
    public function handleEloquentEvent(string $event, array $data)
    {
        $user = Auth::user();

        if (!$this->shouldLog($user)) {
            return;
        }

        $model = $data[0] ?? null;

        if (!$model || !is_object($model)) {
            return;
        }

        // Skip logging for SystemLog itself to prevent infinite loops
        if ($model instanceof SystemLog) {
            return;
        }

        // Determine action from event name (eloquent.created: App\Models\User -> created)
        $action = 'unknown';
        if (str_starts_with($event, 'eloquent.created')) {
            $action = 'created';
        } elseif (str_starts_with($event, 'eloquent.updated')) {
            $action = 'updated';
        } elseif (str_starts_with($event, 'eloquent.deleted')) {
            $action = 'deleted';
        } else {
            return; // We only care about create/update/delete
        }

        $oldValues = null;
        $newValues = null;

        if ($action === 'created') {
            $newValues = $model->getAttributes();
        } elseif ($action === 'updated') {
            $newValues = $model->getChanges();
            $oldValues = array_intersect_key($model->getOriginal(), $newValues);
        } elseif ($action === 'deleted') {
            $oldValues = $model->getAttributes();
        }

        SystemLog::create([
            'user_id' => $user->id,
            'role' => $this->getPrimaryRole($user),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleUserLogin',
            Logout::class => 'handleUserLogout',
            'eloquent.created: *' => 'handleEloquentEvent',
            'eloquent.updated: *' => 'handleEloquentEvent',
            'eloquent.deleted: *' => 'handleEloquentEvent',
        ];
    }
}
