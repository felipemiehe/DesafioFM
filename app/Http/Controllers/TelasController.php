<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Http\Rules\Rules;
use App\Models\GerenciarPermissoe;
use App\Models\PermissaoTela;
use App\Models\Tela;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class TelasController extends Controller
{
    public function criar(Request $request)
    {
        try {

            $validatedData = $request->validate(Tela::$rules);
            $newTela = new Tela();
            $newTela->fill($validatedData);
            $newTela->save();

            return ApiResponse::success($newTela, 'Tela criado com sucesso', Response::HTTP_CREATED);

        } catch (ValidationException $e) {
            return ApiResponse::error("",$e->validator->errors());
        } catch (Exception $e) {
            logger("TelasController/criar" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $tela = Tela::find( $id );
            if($tela){
                $tela->delete();
                return ApiResponse::success($tela, 'Tela deletado com sucesso');
            } else{
                return ApiResponse::error("",'Sem Tela com esse id = ' . $id, Response::HTTP_NOT_FOUND);
            }

        }catch (Exception $e){
            logger("TelasController/deleteTela" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function listarAll(): JsonResponse
    {
        try {

            $AllTelas = Tela::orderBy('created_at', 'DESC')->get();
            return ApiResponse::success($AllTelas, 'Telas achados com sucesso');

        }catch (Exception $e){
            logger("TelasController/listarAll" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function Atualiza(Request $request, $id)
    {
        try {

            $existeTela = Tela::find($id);
            if(!$existeTela) return ApiResponse::error("","Sem Tela com esse " . $request->nome);

            $validatedData = $request->validate(Tela::$rules);
            $existeTela->update($validatedData);
            $telatulizado = Tela::find($id);

            return ApiResponse::success($telatulizado, 'Tela Atualizado com sucesso');
        }
        catch (ValidationException $e) {
            return ApiResponse::error("",$e->validator->errors());
        } catch (Exception $e) {
            logger("TelasController/Atualiza" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function AdicionarPermisaoTela(Request $request)
    {
        try {
            $validatedData = $request->validate(Rules::$AdicionaPermisaoxTelas);

            $registro = PermissaoTela::create([
                'tela_id' => $validatedData['tela_id'],
                'permission_id' => $validatedData['permission_id'],
            ]);

            return ApiResponse::success($registro, 'Permissao concedido com sucesso');

        } catch (ValidationException $e) {
            return ApiResponse::error("",$e->validator->errors());
        } catch (Exception $e) {
            logger("TelasController/AdicionarPermisaoTela" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    public function ListarPermissoesTelas()
    {
        try {
            $AllTelasPermissao = PermissaoTela::orderBy('created_at', 'DESC')->get();
            return ApiResponse::success($AllTelasPermissao, 'Permissao Das telas listadas');

        } catch (ValidationException $e) {
            return ApiResponse::error("",$e->validator->errors());
        } catch (Exception $e) {
            logger("AuthController/RemoverPermisaoxUser" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }





}
