<?php

namespace App\Imports;
 
use App\Models\InventoryBranch;
// use App\Models\InventoryBrand;
use App\Models\InventoryPartname;
use DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;

class InventoryImport implements ToModel
{
    private $req,$control_id;

    public function __construct($req,$control_id) 
    {
     $this->date = $req->date;
     $this->branch = $req->branch;
     $this->control_id = $control_id['control_id'];
    }
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new InventoryPartname([
            'brand' => $row[0],
            'partname' => $row[1],
            'partnameno' => $row[2],
            'sap' => $row[4],
            'unitprice' => $row[6],
            'branch_id' => $this->branch,
            'as_of' =>  $this->date,
            'control_id'=> $this->control_id
        ]);
        
    }
}
