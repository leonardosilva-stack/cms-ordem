<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Entities\Usuario;

class Usuarios extends BaseController
{

    private $usuarioModel;
    private $grupoUsuarioModel;
    private $grupoModel;

    public function __construct()
    {
        $this->usuarioModel = new \App\Models\UsuarioModel();
        $this->grupoUsuarioModel = new \App\Models\GrupoUsuarioModel();
        $this->grupoModel = new \App\Models\GrupoModel();
    }

    public function index()
    {
        $data = [
            'titulo' => 'Listando os usuários do sistema',
        ];

        return view('Usuarios/index', $data);
    }

    public function recuperaUsuarios()
    {
        if (!$this->request->isAjax()) {
            return redirect()->back();
        }

        $atributos = [
            'id',
            'nome',
            'email',
            'ativo',
            'imagem',
            'deletado_em'
        ];

        $usuarios = $this->usuarioModel
            ->select($atributos)
            ->withDeleted(true)
            ->orderBy('id', 'DESC')
            ->findAll();

        // Recebera o array de objetos de usuarios
        $data = [];

        foreach ($usuarios as $usuario) {


            //Definimos o caminho da imagem do usuário
            if ($usuario->imagem != null) {
                // Tem Imagem

                $imagem = [
                    'src'   => site_url("usuarios/imagem/$usuario->imagem"),
                    'class' => 'rounded-circle img-fluid',
                    'alt'   => esc($usuario->nome),
                    'width' => '50',
                ];
            } else {
                // Não tem imagem
                $imagem = [
                    'src'   => site_url("recursos/img/usuario_sem_imagem.png"),
                    'class' => 'rounded-circle img-fluid',
                    'alt'   => 'Usuário sem imagem',
                    'width' => '50',
                ];
            }

            $data[] = [
                'imagem'            => $usuario->imagem = img($imagem),
                'nome'              => anchor("usuarios/exibir/$usuario->id", esc($usuario->nome), 'title="Exibir usuário ' . esc($usuario->nome) . '"'),
                'email'             => esc($usuario->email),
                'ativo'             => $usuario->exibeSituacao(),
            ];
        }

        $retorno = [
            'data' => $data,
        ];

        return $this->response->setJSON($retorno);
    }

    public function criar()
    {
        $usuario = new Usuario();

        $data = [
            'titulo'  => "Criando novo usuário ",
            'usuario' => $usuario,
        ];

        return view('Usuarios/criar', $data);
    }

    public function cadastrar()
    {

        if (!$this->request->isAjax()) {
            return redirect()->back();
        }

        // Envio o hash do token do form
        $retorno['token'] = csrf_hash();

        // Recupero o Post da requisição
        $post = $this->request->getPost();

        // Crio novo objeto da Entidade Usuário
        $usuario = new Usuario($post);

        if ($this->usuarioModel->protect(false)->save($usuario)) {

            $btnCriar = anchor("usuarios/criar", 'Cadastrar novo usuário', ['class' => 'btn btn-danger mt-2']);

            session()->setFlashdata('sucesso', "Usuário criado com sucesso! <br> $btnCriar");

            // Retornamos o último ID inserido na tabela de usuarios
            // Ou seja o ID do usuário recem criado
            $retorno['id'] = $this->usuarioModel->getInsertID();

            return $this->response->setJSON($retorno);
        }

        // Retornamos os erros de validação
        $retorno['erro'] = 'Por favor verifique os erros abaixo e tente novamente';
        $retorno['erros_model'] = $this->usuarioModel->errors();

        // Retorno para o Ajax Request
        return $this->response->setJSON($retorno);
    }


    public function exibir(int $id = null)
    {

        $usuario = $this->buscaUsuarioOu404($id);

        $data = [
            'titulo'  => "Detalhando o usuário " . esc($usuario->nome),
            'usuario' => $usuario,
        ];


        return view('Usuarios/exibir', $data);
    }

    public function editar(int $id = null)
    {

        $usuario = $this->buscaUsuarioOu404($id);

        $data = [
            'titulo'  => "Editando o usuário " . esc($usuario->nome),
            'usuario' => $usuario,
        ];


        return view('Usuarios/editar', $data);
    }

    public function atualizar()
    {

        if (!$this->request->isAjax()) {
            return redirect()->back();
        }

        // Envio o hash do token do form
        $retorno['token'] = csrf_hash();

        // Recupero o Post da requisição
        $post = $this->request->getPost();

        // Validamos a existencia do usuario
        $usuario = $this->buscaUsuarioOu404($post['id']);

        // Se não foi informado a senha, removemos do $post
        // Se não fizermos dessa forma, o hashPassword fará  o hash de uma string vazia
        if (empty($post['password'])) {
            unset($post['password']);
            unset($post['password_confirmation']);
        }

        // Preenchemos os atributos do usuario com os valores do POST
        $usuario->fill($post);

        if ($usuario->hasChanged() == false) {
            $retorno['info'] = 'Não há dados para serem atualizados';
            return $this->response->setJSON($retorno);
        }

        if ($this->usuarioModel->protect(false)->save($usuario)) {


            session()->setFlashdata('sucesso', 'Dados salvos com sucesso!');

            return $this->response->setJSON($retorno);
        }

        // Retornamos os erros de validação
        $retorno['erro'] = 'Por favor verifique os erros abaixo e tente novamente';
        $retorno['erros_model'] = $this->usuarioModel->errors();

        // Retorno para o Ajax Request
        return $this->response->setJSON($retorno);
    }

    public function editarImagem(int $id = null)
    {

        $usuario = $this->buscaUsuarioOu404($id);

        $data = [
            'titulo'  => "Alterando a imagem do usuário " . esc($usuario->nome),
            'usuario' => $usuario,
        ];


        return view('Usuarios/editar_imagem', $data);
    }

    public function upload()
    {

        if (!$this->request->isAjax()) {
            return redirect()->back();
        }

        // Envio o hash do token do form
        $retorno['token'] = csrf_hash();

        $validacao = service('validation');

        $regras = [
            'imagem' => 'uploaded[imagem]|max_size[imagem,1024]|ext_in[imagem,png,jpg,jpeg,webp]',
        ];

        $mensagens = [
            'imagem' => [
                'uploaded'  => 'Por favor escolha uma imagem',
                'max_size'  => 'Por favor escolha uma imagem de no máximo 1024',
                'ext_in'    => 'Por favor escolha uma imagem png, jpg, jpeg ou webp',
            ],
        ];

        $validacao->setRules($regras, $mensagens);

        if ($validacao->withRequest($this->request)->run() == false) {
            // Retornamos os erros de validação
            $retorno['erro'] = 'Por favor verifique os erros abaixo e tente novamente';
            $retorno['erros_model'] = $validacao->getErrors();

            // Retorno para o Ajax Request
            return $this->response->setJSON($retorno);
        }


        // Recupero o Post da requisição
        $post = $this->request->getPost();

        // Validamos a existencia do usuario
        $usuario = $this->buscaUsuarioOu404($post['id']);

        // Recuperamos a imagem que veio no post
        $imagem = $this->request->getFile('imagem');

        list($largura, $altura) = getimagesize($imagem->getPathName());

        if ($largura < "300" || $altura < "300") {
            // Retornamos os erros de validação
            $retorno['erro'] = 'Por favor verifique os erros abaixo e tente novamente';
            $retorno['erros_model'] = ['dimensão' => 'A imagem não pode ser menor do que 300 x 300 pixels'];

            // Retorno para o Ajax Request
            return $this->response->setJSON($retorno);
        }

        $caminhoImagem = $imagem->store('usuarios');

        $caminhoImagem = WRITEPATH .  "uploads/$caminhoImagem";

        // Podemos manipular a imagem que está salva no diretório

        // Redimensionamos a imagem para 300x300 e para ficar no centro
        $this->manipulaImagem($caminhoImagem, $usuario->id);


        // A partir daqui podemos atualizar a tabela de usuários

        // Recupero a possivel imagem antiga
        $imagemAntiga = $usuario->imagem;

        $usuario->imagem = $imagem->getName();

        $this->usuarioModel->save($usuario);

        if ($imagemAntiga != null) {
            $this->removeImagemDoFileSystem($imagemAntiga);
        }

        session()->setFlashdata('sucesso', 'Imagem atualizada com sucesso!');

        // Retorno para o Ajax Request
        return $this->response->setJSON($retorno);
    }

    public function imagem(string $imagem = null)
    {
        if ($imagem != null) {
            $this->exibeArquivo('usuarios', $imagem);
        }
    }

    public function excluir(int $id = null)
    {
        $usuario = $this->buscaUsuarioOu404($id);

        if ($usuario->deletado_em != null) {
            return redirect()->back()->with('info', "Esse usuário já encontra-se excluido");
        }


        if ($this->request->getMethod() === 'post') {
            // Exclui o Usuário
            $this->usuarioModel->delete($usuario->id);

            // Detetamos a imagem do filesystem
            if ($usuario->imagem != null) {
                $this->removeImagemDoFileSystem($usuario->imagem);
            }

            $usuario->imagem = null;
            $usuario->ativo = false;

            $this->usuarioModel->protect(false)->save($usuario);


            return redirect()->to(site_url("usuarios"))->with('sucesso', 'Usuário ' . esc($usuario->nome) . ' excluido com sucesso!');
        }

        $data = [
            'titulo'  => "Excluindo o usuário " . esc($usuario->nome),
            'usuario' => $usuario,
        ];


        return view('Usuarios/excluir', $data);
    }

    public function desfazerExclusao(int $id = null)
    {
        $usuario = $this->buscaUsuarioOu404($id);

        if ($usuario->deletado_em == null) {
            return redirect()->back()->with('info', "Apenas usuários excluidos podem ser recuperados");
        }

        $usuario->deletado_em = null;
        $this->usuarioModel->protect(false)->save($usuario);

        return redirect()->back()->with('sucesso', 'Usuário ' . esc($usuario->nome) . ' recuperado com sucesso!');
    }

    public function grupos(int $id = null)
    {

        $usuario = $this->buscaUsuarioOu404($id);

        $usuario->grupos = $this->grupoUsuarioModel->recuperaGruposDoUsuario($usuario->id, 5);
        $usuario->pager = $this->grupoUsuarioModel->pager;

        $data = [
            'titulo'  => "Gerenciando os grupos de acesso do usuário " . esc($usuario->nome),
            'usuario' => $usuario,
        ];

        // Quando o usuario for um cliente podemos retorna para a view de exibiçao do usuario informando 
        // que ele é um cliente que não é possivel adiciona-lo aos outros grupos ou removê-lo de um grupo existente (clientes)

        if (in_array(2, array_column($usuario->grupos, 'grupo_id'))) {
            return redirect()->to(site_url("usuarios/exibir/$usuario->id"))
                ->with('info', "Esse usuário é um Cliente, portanto, não é necessário atribuí-lo ou removê-lo de outros grupos de acesso");
        }

        if (in_array(1, array_column($usuario->grupos, 'grupo_id'))) {
            $usuario->full_control = true; // Esta no grupo de Admin. Portanto, já podemos retornar a views
            return view('Usuarios/grupos', $data);
        }

        $usuario->full_control = false; // Não esta no grupo de admin. Podemos seguir com o processamento.

        if (!empty($usuario->grupos)) {
            // Recuperamos os grupos que o usuário ainda não faz parte

            $gruposExistentes = array_column($usuario->grupos, 'grupo_id');

            $data['gruposDisponiveis'] = $this->grupoModel
                ->where('id !=', 2) // Não recuperamos o grupo de clientes
                ->whereNotIn('id', $gruposExistentes)
                ->findAll();
        } else {
            // Recuperamos todos os grupos, com exceção do grupo ID 2 que é o cliente


            $data['gruposDisponiveis'] = $this->grupoModel
                ->where('id !=', 2) // Não recuperamos o grupo de clientes
                ->findAll();
        }


        return view('Usuarios/grupos', $data);
    }

    public function salvarGrupos()
    {
        if (!$this->request->isAjax()) {
            return redirect()->back();
        }

        // Envio o hash do token do form
        $retorno['token'] = csrf_hash();

        // Recupero o Post da requisição
        $post = $this->request->getPost();

        // Validamos a existencia do usuario
        $usuario = $this->buscaUsuarioOu404($post['id']);


        if (empty($post['grupo_id'])) {
            // Retornamos os erros de validação
            $retorno['erro'] = 'Por favor verifique os erros abaixo e tente novamente';
            $retorno['erros_model'] = ['grupo_id' => 'Escolha um ou mais grupos para salvar'];

            // Retorno para o Ajax Request
            return $this->response->setJSON($retorno);
        }

        if (in_array(2, $post['grupo_id'])) {
            // Retornamos os erros de validação
            $retorno['erro'] = 'Por favor verifique os erros abaixo e tente novamente';
            $retorno['erros_model'] = ['grupo_id' => 'O grupo de Clientes não podem ser atribuído de forma manual'];

            // Retorno para o Ajax Request
            return $this->response->setJSON($retorno);
        }

        // Verificamos se no POST esta vindo o grupo admin (ID 1)
        if (in_array(1, $post['grupo_id'])) {

            $grupoAdmin = [
                'grupo_id'      => 1,
                'usuario_id'    => $usuario->id
            ];

            // Associamos o usuário em questão apenas ao grupo Admin
            $this->grupoUsuarioModel->insert($grupoAdmin);

            // Remove todos os demais grupos que estão associados ao usuário em questão
            $this->grupoUsuarioModel->where('grupo_id !=', 1)
                ->where('usuario_id', $usuario->id)
                ->delete();

            session()->setFlashdata('sucesso', 'Dados salvos com sucesso!');
            session()->setFlashdata('info', 'Notamos que o Grupo Administrador foi informado, portanto não há necessidade de informar outros grupos, pois apenas o Administrador será associado ao usuário!');

            return $this->response->setJSON($retorno);
        }



        // Receberá as permissões do POST
        $grupoPush = [];

        foreach ($post['grupo_id'] as $grupo) {
            array_push($grupoPush, [
                'grupo_id'      => $grupo,
                'usuario_id'    => $usuario->id
            ]);
        }

        $this->grupoUsuarioModel->insertBatch($grupoPush);

        session()->setFlashdata('sucesso', 'Dados salvos com sucesso!');

        return $this->response->setJSON($retorno);
    }

    public function removeGrupo(int $principal_id = null)
    {
        if ($this->request->getMethod() === 'post') {
            $grupoUsuario = $this->buscaGrupoUsuarioOu404($principal_id);

            if ($grupoUsuario->grupo_id == 2) {
                return redirect()->to(site_url("usuarios/exibir/$grupoUsuario->usuario_id"))->with("info", "Não é permitida a exclusão do usuário do grupo de Clientes");
            }

            $this->grupoUsuarioModel->delete($principal_id);
            return redirect()->back()->with("sucesso", "Usuário removido do grupo de acesso com sucesso!");
        }

        // Não é POST
        return redirect()->back();
    }

    /**
     * Metodo que recupera o usuário
     * 
     * @param integer $id
     * @return Exceptions|object
     */
    private function buscaUsuarioOu404(int $id = null)
    {
        if (!$id || !$usuario = $this->usuarioModel->withDeleted(true)->find($id)) {

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o  usuário $id");
        }

        return $usuario;
    }

    /**
     * Metodo que recupera o registro do grupo associado ao usuário
     * 
     * @param integer $principal_id
     * @return Exception|object
     */
    private function buscaGrupoUsuarioOu404(int $principal_id = null)
    {
        if (!$principal_id || !$grupoUsuario = $this->grupoUsuarioModel->find($principal_id)) {

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o  registro de associação ao grupo de acesso $principal_id");
        }

        return $grupoUsuario;
    }


    private function manipulaImagem(string $caminhoImagem, int $usuario_id)
    {
        service('image')
            ->withFile($caminhoImagem)
            ->fit(300, 300, 'center')
            ->save($caminhoImagem);


        $anoAtual = date('Y');

        // Adicionar uma marca d'agua de texto
        \Config\Services::image('imagick')
            ->withFile($caminhoImagem)
            ->text("Ordem $anoAtual - User-ID $usuario_id", [
                'color'         => '#fff',
                'opacity'       => 0.5,
                'withShadow'    => false,
                'hAligh'        => 'center',
                'vAlign'        => 'bottom',
                'fontSize'      => 10
            ])
            ->save($caminhoImagem);
    }

    private function removeImagemDoFileSystem(string $imagem)
    {

        $caminhoImagem = WRITEPATH .  "uploads/usuarios/$imagem";

        if (is_file($caminhoImagem)) {
            unlink($caminhoImagem);
        }
    }
}
