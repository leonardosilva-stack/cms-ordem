<?php echo $this->extend('Layout/principal') ?>

<?php echo $this->section('titulo') ?>
<?php echo $titulo; ?>
<?php echo $this->endsection() ?>

<?php echo $this->section('estilos') ?>
<!--Aqui coloco os estilos da View -->
<link rel="stylesheet" type="text/css" href="<?php echo site_url('recursos/vendor/datatable/css/datatables.min.css') ?>" />
<?php echo $this->endsection() ?>

<?php echo $this->section('conteudo') ?>
<!--Aqui coloco o conteudo da View -->

<div class="row">
    <div class="col-lg-12">
        <div class="block">
            <a href="<?php echo site_url('grupos/criar'); ?>" class="btn btn-danger mb-5">Criar grupo de acesso</a>
            <div class="table-responsive">
                <table id="ajaxTable" class="table table-striped table-sm" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Situação</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endsection() ?>

<?php echo $this->section('scripts') ?>
<!--Aqui coloco os scripts da View -->
<script type="text/javascript" src="<?php echo site_url('recursos/vendor/datatable/js/datatables.min.js') ?>"></script>



<script>
    $(document).ready(function() {

        const DATATABLE_PTBR = {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            },
            "select": {
                "rows": {
                    "_": "Selecionado %d linhas",
                    "0": "Nenhuma linha selecionada",
                    "1": "Selecionado 1 linha"
                }
            }
        }


        $('#ajaxTable').DataTable({

            "oLanguage": DATATABLE_PTBR,

            "ajax": "<?php echo site_url('grupos/recuperagrupos'); ?>",
            "columns": [{
                    "data": "nome"
                },
                {
                    "data": "descricao"
                },
                {
                    "data": "exibir"
                },

            ],
            "deferRender": true,
            "processing": true,
            "responsive": true,
            "pagingType": $(window).width() < 768 ? "simple" : "simple_numbers",
        });
    });
</script>
<?php echo $this->endsection() ?>