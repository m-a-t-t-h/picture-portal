<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Albums extends Model
{
    public $table = "Albums";

    public function scopeToplevel($q)
    {
        return $q->where("pid", 0);
    }

    public function gallery(): HasOne
    {
        return $this->hasOne(AlbumRoots::class, "id", "albumRoot");
    }
}
