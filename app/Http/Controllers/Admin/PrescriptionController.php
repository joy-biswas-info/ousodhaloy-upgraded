<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::with('order')->latest()->paginate(20);
        return view('admin.prescriptions.index', compact('prescriptions'));
    }

    public function review(Request $request, Prescription $p)
    {
        $p->update(['status' => $request->status, 'admin_note' => $request->note]);
        return back()->with('success', 'Prescription reviewed.');
    }
}