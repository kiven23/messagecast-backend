<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPartname extends Model
{
    use HasFactory;
    protected $fillable = ['brand','partname','partnameno','sap','unitprice','branch_id','as_of','control_id'];
}
