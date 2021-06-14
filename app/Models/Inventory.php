<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function branch()
    {
        return $this->hasOne(InventoryBranch::class, 'id', 'branch_id');
    }
    public function invty_list(){
        return $this->hasMany(InventoryPartname::class, 'control_id', 'control_id');
    }

}
