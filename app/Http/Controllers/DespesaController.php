<?php

namespace App\Http\Controllers;

use App\Http\Requests\DespesaRequest;
use App\Models\Despesa;
use App\Notifications\DespesaRegistrada;
use Illuminate\Support\Carbon;
use Notification;

class DespesaController extends Controller
{
    public function index()
    {
        $despesas = Despesa::where('user_id', auth()->id())->get();

        return response()->json(['data' => $despesas], 200);
    }
    public function store(DespesaRequest $request)
    {
        $formattedDate = Carbon::createFromFormat('d/m/Y', $request->input('data'))->format('Y/m/d');

        $despesa = new Despesa([
            'descricao' => $request->input('descricao'),
            'data' => $formattedDate,
            'valor' => $request->input('valor'),
        ]);
        $despesa->user_id = auth()->id();
        $despesa->save();

        Notification::send($despesa->user, new DespesaRegistrada());

        return response()->json($despesa, 201);
    }

    public function update(DespesaRequest $request, Despesa $despesa)
    {
        $this->authorize('update', $despesa);

        $data = Carbon::createFromFormat('d/m/Y', $request->input('data'))->format('Y-m-d');
        $request->merge(['data' => $data]);

        $despesa->update($request->all());

        return response()->json($despesa, 200);
    }

    public function destroy(Despesa $despesa)
    {
        $this->authorize('delete', $despesa);

        $despesa->delete();

        return response()->json(null, 204);
    }

    public function show(Despesa $despesa)
    {
        $this->authorize('view', $despesa);

        return response()->json(['data' => $despesa], 200);
    }

}