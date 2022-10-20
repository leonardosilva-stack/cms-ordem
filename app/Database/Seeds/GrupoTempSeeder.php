<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GrupoTempSeeder extends Seeder
{
    public function run()
    {
        $grupoModel = new \App\Models\GrupoModel();

        $grupos = [
            [
                //ID do grupo administrador
                'nome'      => 'Administrador',
                'descricao' => 'Grupo com acesso total ao sistema',
                'exibir'    => false,
            ],
            [
                //ID do grupo clientes
                'nome'      => 'Clientes',
                'descricao' => 'Esse grupo é destinado para atribuição de clientes, pois os mesmos poderão logar no sistema para acessar suas ordens de serviços',
                'exibir'    => false,
            ],
            [
                'nome'      => 'Atendentes',
                'descricao' => 'Esse grupo acessa o sistema para realizar atendimento aos clientes.',
                'exibir'    => false,
            ],
        ];

        foreach ($grupos as $grupo) {
            $grupoModel->insert($grupo);
        }

        echo "Grupos criado com sucesso!";
    }
}
