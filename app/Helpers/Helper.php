<?php

namespace App\Helpers;

class Helper
{
    public static function mensagens(String $tipo) : string
    {
        $mensagem = match ($tipo) {
            'cadastrar' => 'Registro cadastrado com sucesso!',
            'atualizar' => 'Registro atualizado com sucesso!',
            'deletar' => 'Registro deletado com sucesso!',
            'erro_cadastrar' => 'Erro ao cadastrar registro!',
            'erro_atualizar' => 'Erro ao atualizar registro!',
            'erro_deletar' => 'Erro ao deletar registro!',
            'registro_duplicado' => 'Registro já cadastrado!'
        };

        return $mensagem;
    }

    public static function limpaValidacao(mixed $campo) : mixed
    {
        return strtoupper($campo);
    }
}