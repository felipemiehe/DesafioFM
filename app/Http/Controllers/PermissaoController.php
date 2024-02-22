<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Http\Rules\Rules;
use App\Models\Permission;
use App\Models\Usuario;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class PermissaoController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function criar(Request $request): JsonResponse
    {
        try {

            $validatedData = $request->validate(Permission::$rules);
            $newPermissao = new Permission();
            $newPermissao->fill($validatedData);
            $newPermissao->save();

            return ApiResponse::success($newPermissao, 'Permissao criado com sucesso', Response::HTTP_CREATED);

        } catch (ValidationException $e) {
            return ApiResponse::error("",$e->validator->errors());
        } catch (Exception $e) {
            logger("PermissaoController/criar" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($nome): JsonResponse
    {
        try {
            $existePermissao = Permission::where('roles', $nome)->first();
            if($existePermissao){
                $existePermissao->delete();
                return ApiResponse::success($existePermissao, 'Permisao removida com sucesso');
            } else{
                return ApiResponse::error("",'Sem permissao com esse nome = ' . $nome, Response::HTTP_NOT_FOUND);
            }

        }catch (Exception $e){
            logger("PermissaoController/deletePermisao" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function Atualiza(Request $request, $nome)
    {
        try {
            $existePermissao = Permission::where('roles', $nome)->first();

            if(!$existePermissao) return ApiResponse::error("","Sem Permissao com esse " . $nome);

            $validatedData = $request->validate(Rules::$AtualizarPermissao);
            $existePermissao->update($validatedData);
            $usuarioAtulizado = Permission::where('roles', $validatedData['roles'])->first();

            return ApiResponse::success($usuarioAtulizado, 'Permissao Atualizado com sucesso');
        }
        catch (ValidationException $e) {
            return ApiResponse::error("",$e->validator->errors());
        } catch (Exception $e) {
            logger("PermissaoController/Atualiza" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    public function listarUM($nome)
    {
        try {
            $existePermissao = Permission::where('roles', $nome)->first();

            if(!$existePermissao) return ApiResponse::error("","Sem Permissao com esse " . $nome);
            return ApiResponse::success($existePermissao, 'Permissao Atualizado com sucesso');
        }
        catch (ValidationException $e) {
            return ApiResponse::error("",$e->validator->errors());
        } catch (Exception $e) {
            logger("PermissaoController/listarUM" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function listarTodos()
    {
        try {
            $existePermissao = Permission::orderBy('created_at', 'DESC')->get();

            if(!$existePermissao) return ApiResponse::error("","Sem Permissaos cadastradas");
            return ApiResponse::success($existePermissao, 'Permissao Listadas com sucesso');
        }
        catch (ValidationException $e) {
            return ApiResponse::error("",$e->validator->errors());
        } catch (Exception $e) {
            logger("PermissaoController/listarTodos" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


}
