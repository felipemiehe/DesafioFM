<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Http\Rules\Rules;
use App\Models\GerenciarPermissoe;
use App\Models\Permission;
use App\Models\Usuario;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function Login(Request $request)
    {
        try {

            $validatedData = $request->validate(Rules::$Login);

            $credentials = $request->only('username', 'password');
            $user = Usuario::where('username', $credentials['username'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return ApiResponse::error(null, 'Credenciais inválidas', Response::HTTP_UNAUTHORIZED);
            }
            $expirationTime = time() + (60 * 60);

            $payload = [
                'id' => $user->id,
                'username' => $user->username,
                'exp' => $expirationTime,
                'roles' => $user->permissoes
            ];


            $algorithm = 'HS256';
            $secret = env('JWT_SECRET');

            $token = JWT::encode($payload, $secret, $algorithm);
            $cookie = cookie('jwt', $token, 60); // 1h

            return response([
                'message' => 'success',
                'Token' => $token
            ],Response::HTTP_OK)->withCookie($cookie);

        } catch (ValidationException $e) {
            return ApiResponse::error("",$e->validator->errors());
        } catch (Exception $e) {
            logger("AuthController/Login" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    public function AdicionarPermisaoxUser(Request $request)
    {
        try {
            $validatedData = $request->validate(Rules::$AdicionaPermisaoxUser);

            $registro = GerenciarPermissoe::create([
                'user_id' => $validatedData['user_id'],
                'permission_id' => $validatedData['permission_id'],
            ]);

            return ApiResponse::success($registro, 'Permissao concedido com sucesso');

        } catch (ValidationException $e) {
            return ApiResponse::error("",$e->validator->errors());
        } catch (Exception $e) {
            logger("AuthController/AdicionarPermisaoxUser" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function RemoverPermisaoxUser(Request $request)
    {
        try {
            $validatedData = $request->validate(Rules::$AdicionaPermisaoxUser);

            $existePermissaoGerenciar = GerenciarPermissoe::
                                        where('user_id', $validatedData["user_id"])
                                        ->where('permission_id', $validatedData["permission_id"] )
                                        ->first();
            if($existePermissaoGerenciar) $existePermissaoGerenciar->delete();
            else{
                return ApiResponse::error($validatedData, 'Permissao nao encontrado com USER ID = ' . $validatedData["user_id"]);
            }
            return ApiResponse::success($validatedData, 'Permissao deletada com sucesso');

        } catch (ValidationException $e) {
            return ApiResponse::error("",$e->validator->errors());
        } catch (Exception $e) {
            logger("AuthController/RemoverPermisaoxUser" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function show(){
        return "ROTA AUTORIZADA";
    }

    public function AcharRoles($id)
    {
        try {

            $usuario = Usuario::find($id);
            if(!$usuario) return ApiResponse::error("","Sem usuario com esse" . $id);
            $Roles = $usuario->permissoes;
            return ApiResponse::success($Roles, 'Roles desse Usuario');
        }
        catch (ValidationException $e) {
            return ApiResponse::error("",$e->validator->errors());
        } catch (Exception $e) {
            logger("AuthController/AcharRoles" . $e);
            return ApiResponse::error("",'Ocorreu um erro ao processar a requisição', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }



}
