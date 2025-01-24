<link rel="stylesheet" type="text/css" href="<?php echo Config::get('URL'); ?>public/css/style.css">

<h2 class="gallery-title">Meine Galerie</h2>

<!-- Upload-Formular -->
<form action="<?php echo Config::get('URL'); ?>gallery/upload" method="post" enctype="multipart/form-data" class="gallery-upload-form">
    <input type="file" name="image" required class="gallery-input">
    <button type="submit" class="gallery-upload-button">Bild hochladen</button>
</form>

<div class="gallery-container">
    <?php if (!empty($this->images)): ?>
        <?php foreach ($this->images as $image): ?>
            <div class="gallery-item">
                <img src="<?php echo Config::get('URL'); ?>gallery/showImage/<?php echo $image->file_hash; ?>" class="gallery-image">

                <form action="<?php echo Config::get('URL'); ?>gallery/share/<?php echo $image->id; ?>" method="post">
                    <button type="submit" class="gallery-button share-button">Freigeben</button>
                </form>

                <form action="<?php echo Config::get('URL'); ?>gallery/delete/<?php echo $image->id; ?>" method="post" onsubmit="return confirm('Willst du dieses Bild wirklich löschen?');">
                    <button type="submit" class="gallery-button delete-button">Löschen</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="gallery-empty">Du hast noch keine Bilder hochgeladen.</p>
    <?php endif; ?>
</div>
