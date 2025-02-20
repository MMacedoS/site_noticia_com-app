<h3>Notícias</h3>

<?php
    foreach($noticias as $noticia){

        
?>

    <div style="border: 1px solid black">
        <p><?=$noticia->id ?></p>
        <p><?=$noticia->uuid ?></p>
        <p><?=$noticia->titulo ?></p>
        <p><?=$noticia->resumo ?></p>
        <p><?=$noticia->noticia ?></p>
        <p><?=$noticia->autor ?></p>
        <p><?=$noticia->fonte ?></p>
        <p><?=$noticia->tag ?></p>
        <p><?=$noticia->ativo ?></p>
        <p><?=$noticia->link ?></p>
        <p><?=$noticia->created_at ?></p>
        <p><?=$noticia->updated_at ?></p>
    </div>

<?php
    }
?>