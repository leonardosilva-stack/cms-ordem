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

                <?php echo form_open("grupos/excluir/$grupo->id") ?>

                <div class="alert alert-warning" role="alert">
                    Tem certeza da exclusão do registro?
                </div>

                <div class="form-group mt-5 mb-2">
                    <input id="btn-salvar" type="submit" value="Sim, pode excluir" class="btn btn-danger mr-2">
                    <a href="<?php echo site_url("grupos/exibir/$grupo->id") ?>" class="btn btn-secondary ml-2">Cancelar</a>
                </div>

                <?php echo form_close(); ?>
            </div>

        </div>
    </div>
</div>

<?php echo $this->endsection() ?>

<?php echo $this->section('scripts') ?>
<!--Aqui coloco os scripts da View -->

<?php echo $this->endsection() ?>