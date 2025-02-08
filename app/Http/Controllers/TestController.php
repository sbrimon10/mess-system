<?php

namespace App\Http\Controllers;
use Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Events\SystemNotificationEvent;
use Illuminate\Support\Facades\Broadcast;
use App\Events\MyEvent;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class TestController extends Controller
{
    public function generatePDF()
{
    // Example data for tables
    $tableData1 = [
        ['id' => 1, 'name' => 'John', 'age' => 30],
        ['id' => 2, 'name' => 'Jane', 'age' => 25],
        ['id' => 3, 'name' => 'Doe', 'age' => 22],
    ];

    $tableData2 = [
        ['product' => 'Laptop', 'price' => 1000],
        ['product' => 'Smartphone', 'price' => 700],
        ['product' => 'Tablet', 'price' => 400],
    ];

    // Return the PDF view
    $pdf = Pdf::loadView('pdf.multiple_tables', compact('tableData1', 'tableData2'));
    
    return $pdf->download('multiple_tables.pdf');
}
public function sendtestnotification(){
echo 'test notification';
//Broadcast::on('newnotifications')->as('OrderPlaced')->with(['id' => 1, 'message' => 'test notification'])->send();
// Log::info('Sending test notification');
 //SystemNotificationEvent::dispatch('test notification event');
 event(new MyEvent('hello world'));
 event(new SystemNotificationEvent('test notification event'));

}
public function role(){
    echo '<pre>';
   $role= Role::all();
   echo count($role->permissions);
    print_r($role->toArray());
}
}