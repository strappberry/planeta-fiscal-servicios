<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClientesController extends Controller
{

    public function index()
    {
        return view('dashboard.clientes.index');
    }
    
    public function crear()
    {
        return view('dashboard.clientes.crear');
    }

    public function configuracion(Cliente $cliente)
    {
        return view('dashboard.clientes.configuracion', compact('cliente'));
    }

}
