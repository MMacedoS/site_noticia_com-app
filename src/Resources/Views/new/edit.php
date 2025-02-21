<h1>formulario</h1>

<form action="/noticia/<?= $noticia->uuid ?>/editar" method="POST">
    <input type="text" name="title" id="" value="<?= $noticia->titulo ?? null ?>">
    <input type="text" name="summary" id="" value="<?= $noticia->resumo ?? null ?>">
    <input type="text" name="new" id="" value="<?= $noticia->noticia ?? null ?>">
    <input type="text" name="author" id="" value="<?= $noticia->autor ?? null ?>">
    <input type="text" name="font" id="" value="<?= $noticia->fonte ?? null ?>">
    <input type="text" name="tag" id="" value="<?= $noticia->tag ?? null ?>">
    <select name="active" id="" value="<?= $noticia->ativo ?? null ?>">
        <option value="0">disable</option>
        <option value="1">ativo</option>
    </select>
    <input type="text" name="link" id="" value="<?= $noticia->link ?? null ?>">

    <button type="submit">Mandar</button>
</form>

<?php
    if(isset($error)){
?>

        <p><?= $error ?></p>
<?php
    }
?>