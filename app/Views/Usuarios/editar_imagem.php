<?php echo $this->extend('Layout/principal') ?>

<?php echo $this->section('titulo') ?>
<?php echo $titulo; ?>
<?php echo $this->endsection() ?>

<?php echo $this->section('estilos') ?>
<!--Aqui coloco os estilos da View -->
<?php echo $this->endsection() ?>

<?php echo $this->section('conteudo') ?>
<!--Aqui coloco o conteudo da View -->

<div class="row">
    <div class="col-lg-6">
        <div class="block">

            <div class="block-body">

                <div id="response">
                </div>

                <?php echo form_open_multipart('/', ['id' => 'form'], ['id' => "$usuario->id"]) ?>

                <div class="form-group">
                    <label class="form-control-label">Escolha uma imagem</label>
                    <input type="file" name="imagem" class="form-control">
                </div>


                <div class="form-group mt-5 mb-2">
                    <input id="btn-salvar" type="submit" value="Salvar" class="btn btn-danger mr-2">
                    <a href="<?php echo site_url("usuarios/exibir/$usuario->id") ?>" class="btn btn-secondary ml-2">Voltar</a>
                </div>

                <?php echo form_close(); ?>
            </div>

        </div>
    </div>
</div>

<?php echo $this->endsection() ?>

<?php echo $this->section('scripts') ?>
<!--Aqui coloco os scripts da View -->

<script>
    $(document).ready(function() {
        $("#form").on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '<?php echo site_url('usuarios/upload'); ?>',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {

                    $("#response").html('');
                    $("#btn-salvar").val('Por favor aguarde...');

                },
                success: function(response) {

                    $("#btn-salvar").val('Salvar');
                    $("#btn-salvar").removeAttr("disabled");

                    $('[name=csrf_ordem]').val(response.token);

                    if (!response.erro) {

                        // Tudo certo com a atualiza????o do usuario
                        // Podemos redireciona-lo tranquilamente

                        window.location.href = "<?php echo site_url("usuarios/exibir/$usuario->id"); ?>"

                    }

                    if (response.erro) {
                        // Existem erros de valida????o.

                        $("#response").html('<div class="alert alert-danger">' + response.erro + '</div>');

                        if (response.erros_model) {

                            $.each(response.erros_model, function(key, value) {
                                $("#response").append('<ul class="list-unstyled"><li class="text-danger">' + value + '</li></ul>')
                            });
                        }
                    }

                },
                error: function() {
                    alert('N??o foi possivel processar a solicita????o. Por favor entre em contato com o suporte t??cnico.');
                    $("#btn-salvar").val('Salvar');
                    $("#btn-salvar").removeAttr("disabled");
                },
            })
        });

        $("form").submit(function() {
            $(this).find(":submit").attr('disabled', 'disabled');
        });
    });
</script>
<?php echo $this->endsection() ?>