<?php

namespace App\Libraries;

class Autenticacao
{
    private $usuario;
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new \App\Models\UsuarioModel();
    }

    /**  
     * Metodo que realiza o login na aplicação
     * @param string $email
     * @param string $password
     * @return boolean
     */

    public function login(string $email, string $password): bool
    {
        // Buscamos o usuário
        $usuario = $this->usuarioModel->buscaUsuarioPorEmail($email);

        if ($usuario === null) {
            return false;
        }

        //Verificamos se a senha é válida
        if ($usuario->verificaPassword($password) == false) {
            return false;
        }

        // Verificamos se o usuário pode logar na aplicação
        if ($usuario->ativo == false) {
            return false;
        }

        // Logamos o usuario na aplicação
        $this->logaUsuario($usuario);

        //Retonamos  true, ou seja, o usuário pode logar tranquilamente
        return true;
    }


    /**  
     * Metodo que insere na sessão o ID do usuário
     * @param object $usuario
     * @return void
     */
    private function logaUsuario(object $usuario): void
    {

        // Recuperamos a instancia da sessão
        $session = session();

        // Antes de inserirmos o ID do usuário na sessão,
        // devemos gerar um novo ID da sessão
        $session->regenerate();

        // Setamos na sessão o ID do usuário
        $session->set('usuario_id', $usuario->id);
    }
}
