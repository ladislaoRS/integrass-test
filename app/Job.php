<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function candidates()
    {
        return $this->hasManyThrough(User::class, JobApplication::class);
    }
}
