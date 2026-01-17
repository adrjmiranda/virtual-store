<!DOCTYPE html>
<html lang="pt-BR">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->e($title ?? env('APP_NAME')) ?></title>

    <link rel="stylesheet" href="<?= $this->e($this->baseurl()) ?>/css/style.css">

  </head>

  <body>
    <?= $this->section('content') ?>
  </body>

</html>

