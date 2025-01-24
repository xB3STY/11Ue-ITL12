<h2>Bild hochladen</h2>
<form action="<?php echo Config::get('URL'); ?>gallery/upload" method="post" enctype="multipart/form-data">
    <input type="file" name="image" required>
    <button type="submit">Bild hochladen</button>
</form>
