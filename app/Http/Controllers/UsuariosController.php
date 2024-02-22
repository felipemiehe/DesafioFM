<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Http\Rules\Rules;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;


class UsuariosController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function criar(Request $request)
    {
        try {

            $validatedData = $request->validate(Usuario::$rules);
            $newUsuario = $this->salvarNovoUsuario($validatedData);

            return ApiResponse::success($newUsuario, 'Usuário criado com sucesso', Response::HTTP_CREATED);

        } catch (ValidationException $e) {
            return ApiResponse::error("",$e->validator->errors());
        } catch (Exception $e) {
            logger("UsuariosController/criar" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     */
    public function listarAll(): JsonResponse
    {
        try {

            $AllUsuarios = Usuario::orderBy('created_at', 'DESC')->get();
            return ApiResponse::success($AllUsuarios, 'Usuários achados com sucesso');

        }catch (Exception $e){
            logger("UsuariosController/listarAll" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function AtualizaUm(Request $request, $id)
    {
        try {
            $usuario = Usuario::find($id);

            if(!$usuario) return ApiResponse::error("","Sem usuario com esse" . $id);

            $validatedData = $request->validate(Rules::$AtulizarUsuario);
            $usuario->update($validatedData);
            $usuarioAtulizado = Usuario::find($id);

            return ApiResponse::success($usuarioAtulizado, 'Usuário Atualizado com sucesso');
        }
        catch (ValidationException $e) {
            return ApiResponse::error("",$e->validator->errors());
        } catch (Exception $e) {
            logger("UsuariosController/AtualizaUm" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteUsuario($id): JsonResponse
    {
        try {
            $temUsuario = Usuario::find( $id );
            if($temUsuario){
                $temUsuario->delete();
                return ApiResponse::success($temUsuario, 'Usuário deletado com sucesso');
            } else{
                return ApiResponse::error("",'Sem usuarios com esse id = ' . $id, Response::HTTP_NOT_FOUND);
            }

        }catch (Exception $e){
            logger("UsuariosController/destroy" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    // ################################# PRIVATES ################################# //

    /**
     * @param $dadosValidados
     * @return array|Usuario
     */
    private function salvarNovoUsuario($dadosValidados): Usuario|array
    {
        $usuario = new Usuario();
        $usuario->fill($dadosValidados);
        $usuario->save();
        return $usuario;
    }
}
