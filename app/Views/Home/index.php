<?php echo $this->extend('Layout/principal') ?>

<?php echo $this->section('titulo') ?>
  <?php echo $titulo; ?>
<?php echo $this->endsection() ?>

<?php echo $this->section('estilos') ?>
  <!--Aqui coloco os estilos da View -->
<?php echo $this->endsection() ?>

<?php echo $this->section('conteudo') ?>
  <!--Aqui coloco o conteudo da View -->
<?php echo $this->endsection() ?>

<?php echo $this->section('scripts') ?>
  <!--Aqui coloco os scripts da View -->
<?php echo $this->endsection() ?>