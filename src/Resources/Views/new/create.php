<h1>formulario</h1>

<form action="/noticia/criar" method="POST">
    <input type="text" name="title" id="">
    <input type="text" name="summary" id="">
    <input type="text" name="new" id="">
    <input type="text" name="author" id="">
    <input type="text" name="font" id="">
    <input type="text" name="tag" id="">
    <select name="active" id="">
        <option value="0">disable</option>
        <option value="1">ativo</option>
    </select>
    <input type="text" name="link" id="">

    <button type="submit">Mandar</button>
</form>

<?php
    if(isset($error)){
?>

        <p><?= $error ?></p>
<?php
    }
?>