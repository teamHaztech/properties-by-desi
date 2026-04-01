<?php

namespace App\Http\Controllers;

use App\Imports\LeadsImport;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LeadImportController extends Controller
{
    public function show()
    {
        $agents = User::role('sales_agent')->get();

        return view('leads.import', compact('agents'));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240',
        ]);

        $file = $request->file('file');
        $data = Excel::toCollection(null, $file)->first();

        if ($data->isEmpty()) {
            return back()->with('error', 'The file is empty.');
        }

        $headers = $data->first()->keys()->toArray();
        $preview = $data->take(10)->toArray();
        $totalRows = $data->count();

        // Store file temporarily
        $path = $file->store('imports', 'local');

        $agents = User::role('sales_agent')->get();

        return view('leads.import-preview', [
            'headers' => $headers,
            'preview' => $preview,
            'totalRows' => $totalRows,
            'filePath' => $path,
            'agents' => $agents,
            'assignedAgentId' => $request->assigned_agent_id,
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_path' => 'required|string',
            'assigned_agent_id' => 'nullable|exists:users,id',
        ]);

        $filePath = storage_path('app/private/' . $request->file_path);

        if (!file_exists($filePath)) {
            return redirect()->route('leads.import')
                ->with('error', 'Import file expired. Please upload again.');
        }

        $import = new LeadsImport($request->assigned_agent_id);
        Excel::import($import, $filePath);

        // Clean up temp file
        @unlink($filePath);

        $message = "{$import->imported} leads imported successfully.";
        if ($import->duplicates > 0) {
            $message .= " {$import->duplicates} duplicates skipped.";
        }
        if ($import->skipped > 0) {
            $message .= " {$import->skipped} rows skipped total.";
        }

        return redirect()->route('leads.index')
            ->with('success', $message)
            ->with('import_errors', $import->errors);
    }

    public function downloadTemplate()
    {
        $headers = ['name', 'phone', 'email', 'source', 'status', 'budget_min', 'budget_max', 'property_type', 'location', 'urgency'];

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            // Sample row
            fputcsv($file, ['Vikram Singh', '9988776655', 'vikram@email.com', 'call', 'new', '3000000', '5000000', 'plot', 'North Goa', 'high']);
            fputcsv($file, ['Meera Joshi', '9977665544', '', 'whatsapp', 'interested', '20000000', '30000000', 'villa', 'Vagator', 'medium']);
            fclose($file);
        };

        return response()->streamDownload($callback, 'leads_import_template.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
