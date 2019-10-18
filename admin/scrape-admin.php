<h1>Analizar productos</h1>
<div class="wrap">
  <form name="options" method="post" action="">
    <input type="hidden" name="parser_hidden" value="Y">
    <label for="name-parser">Nombre</label>
    <input type="text" name="name-parser" id="name-parser">
    <hr>
    <label for="url-parser">URL a parsear</label>
    <input type="text" name="url-parser" id="url-parser">
    <hr>
    <label for="regex-parser">Expresión Regular</label>
    <input type="text" name="regex-parser" id="regex-parser">
    <hr>
    <input type="submit" name="submit" class="button-primary" value="<?= __("Save", "epp") ?>" />
  </form>
  <hr>
  <div class="log-products-scrape">
    <?php
      foreach ($prices as $price) {
        echo '<a href="'.$price['url'].'" target="_blank">'.$price['name'].'</a>';
        echo '<p>Precio: '.$price['price'].' - <strong>Fecha: '.$price['date'].'</strong></p>';
      }
    ?>
  </div>
</div>