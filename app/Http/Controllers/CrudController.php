<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
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
}
