<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\CampaignField;

class CampaignFieldsController extends Controller
{
    public function store(Request $request)
    {
        $body = json_decode($request->getContent(), true);

        $now = now()->toDateTimeString();

        $timestamps = [
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $data = array_map(function ($item) use ($timestamps) {
            return array_merge($item, $timestamps);
        }, $body);

        $created = DB::table('campaign_fields')->insert($data);

        if (false === $created) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao inserir dados.',
                'data'    => $data,
            ]);
        }

        $fields = CampaignField::with('type:id,name')
                    ->where('campaign_id', $data[0]['campaign_id'])
                    ->get();

        return response()->json([
            'success' => true,
            'message' => 'Dados inseridos com sucesso!',
            'data'    => $fields->toJson(),
        ]);
    }

    public function update(Request $request)
    {
        $action = $request->input('action');
        $id     = $request->input('id');
        $label  = $request->input('label');
        $type   = $request->input('type');

        $field = CampaignField::find($id);

        if (empty($field)) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao atualizar dados: ID não encontrado.',
                'data'    => [],
            ]);
        }

        $field->label = urldecode($label);
        $field->campaign_field_types_id = $type;

        $field->save();

        return response()->json([
            'success' => true,
            'message' => 'Dados atualizados com sucesso!',
            'data'    => $field->toJson(),
        ]);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');

        if (empty($id)) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao excluir dados: ID deve ser informado.',
                'data'    => [],
            ]);
        }

        $destroyed = CampaignField::destroy($id);

        if (empty($destroyed)) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao excluir dados: ID não encontrado.',
                'data'    => [],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Registro excluído com sucesso!',
            'data'    => [],
        ]);
    }
}
