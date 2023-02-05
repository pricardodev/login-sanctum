<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Helpers\Helper;

class UserController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request) : Object
    {       
        $validacao = Validator::make($request->all(), [
                'email' => 'email|required',
                'password' => 'required'
        ]);

        if($validacao->fails()) {
            return response()->json(['menssagem' => 'Usuário e Senha são obrigatórios!'], 422);               
        }

        if(Auth::attempt($request->all())){

            $request->session()->regenerate();
            return response()->json(auth()->user(), 200);

        }else{

            return response()->json(['menssagem' => 'Verifique usuário e senha!'], 401);
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() : Object
    {
        $usuarios = User::select('name', 'email', 'birth_date', 'main_contact', 'secondary_contact', 'user_image')->get();

        return response()->json($usuarios, 200);
    }

    private function UserDuplicated(Array $dados_usuario) : bool
    {
        // verifica primeiro nome e data nascimento, se forem diferentes verifica contato
        $user = User::select('name', 'birth_date', 'email', 'main_contact')
        ->where([
            'name' => $dados_usuario['name'],
            'birth_date' => $dados_usuario['birth_date']
            
        ])
        ->orWhere('main_contact', $dados_usuario['main_contact'])
        ->exists();
        
        return $user;
     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request) : object
    {
       
        $duplicado = $this->UserDuplicated($request->only('name', 'birth_date', 'main_contact'));

        if($duplicado){
            return response()->json(['menssagem' => Helper::mensagens('registro_duplicado')], 422);
        }else{

            $usuario = new User();
            $usuario->name = $request->name;
            $usuario->birth_date = $request->birth_date;
            $usuario->main_contact = $request->main_contact;
            $usuario->secondary_contact = $request->secondary_contact;
            $usuario->user_image = $request->user_image;
            $usuario->email = $request->email;
            $usuario->password = Hash::make($request->password);
            $usuario->status = $request->status;

            $usuario->save();
        }

        return response()->json(['menssagem' => Helper::mensagens('cadastrar')], 201);
        //DB::beginTransaction();
        //DB::rollBack();
        //DB::commit();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) : object
    {
        $usuario = User::select('name', 'email', 'birth_date', 'main_contact', 'secondary_contact', 'user_image')
        ->where('id', $id)
        ->first();

        return response()->json($usuario, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request) : object
    {
        $usuario = User::select('*')->where('id', $request->id)->first();

        if($request->password != null){
            // mescla substituindo a chave existente
            $request->merge(['password' => Hash::make($request->password)]);
            $usuario->update($request->all());
        }

        $usuario->update($request->except('password'));

        return response()->json(['menssagem' => Helper::mensagens('atualizar')], 200);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) : object // status code 204
    {
        $usuario = User::find($id);

        if(is_null($usuario)){

            return response()->json(['menssagem' => Helper::mensagens('erro_deletar')], 422);

        }else{

            $usuario->delete();

            return response()->json(['menssagem' => Helper::mensagens('deletar')], 200);
               
        }            
        
    }
}