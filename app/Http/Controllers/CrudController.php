<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Exports\CompaniesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Company;
use GuzzleHttp\Client;
use Yajra\Datatables\Facades\Datatables;

class CrudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $response = Http::get('http://127.0.0.1:8000/api/crud');
        $list = json_decode($response->getBody(), true);
        return view('companies', ['data' => $list['data']]);
    }

    public function list()
    {
        $response = Http::get('http://127.0.0.1:8000/api/crud');
        $list = json_decode($response->getBody(), true);
        $list = $list['data'];
        return datatables()->of($list)
            ->addColumn('action', 'company-action')
            ->rawColumns(['action'])
            ->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        Http::asForm()->post(
            'http://127.0.0.1:8000/api/store-company',
            [
                'id' => $request->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address
            ]
        );
    }

    public function edit(Request $request)
    {
        $payload = Http::asForm()->post(
            'http://127.0.0.1:8000/api/edit-company',
            [
                'id' => $request->id
            ]
        );

        return $payload;
    }

    public function destroy(Request $request)
    {
        Http::asForm()->post(
            'http://127.0.0.1:8000/api/delete-company',
            [
                'id' => $request->id
            ]
        );

        $ListCompanies = Http::get('http://127.0.0.1:8000/api/crud');

        return json_decode($ListCompanies->getBody(), true);
    }

    public function export()
    {
        return Excel::download(new CompaniesExport, 'Companies.xlsx');
    }

    public function import(Request $request)
    {
        $file = $request->file('file');

        $rows = Excel::toArray(new \App\Imports\CompaniesImport, $file);

        $cleanData = array_map(function ($tag) {
            return array(
                'id' => $tag['no'],
                'name' => $tag['nama'],
                'email' => $tag['email'],
                'phone' => $tag['phone'],
                'address' => $tag['address'],
                'created_at' => $tag['created'],
                'updated_at' => $tag['update']
            );
        }, $rows[0]);

        Http::asForm()->post('http://127.0.0.1:8000/api/import-companies',
            [
                'data' => $cleanData
            ]
        );
    }

    public function fetch()
    {
        $files = \File::allFiles(public_path('uploads'));
        $output = '<div class="row">';

        foreach ($files as $file) {
            $output .= '
            <div class="col-md-2" style="margin-bottom:16px;" align="center">
                        <img src="' . asset('uploads/' . $file->getFilename()) . '" class="img-thumbnail" width="175" height="175" style="height:175px;" />
                        <button type="button" class="btn btn-link remove_image" id="' . $file->getFilename() . '">Remove</button>
                    </div>
            ';
        }
        $output .= '</div>';
        echo $output;
    }

    public function delete(Request $request)
    {
        if ($request->get('name'))
            \File::delete(public_path('uploads/' . $request->get('name')));
    }
}