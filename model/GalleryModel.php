<?php

class GalleryModel
{
    public static function uploadImage($user_id, $file)
    {
        $upload_dir = '../userpictures/' . $user_id . '/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_hash = md5(uniqid($user_id, true));
        $target_file = $upload_dir . $file_hash . '.' . $file_ext;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            $db = DatabaseFactory::getFactory()->getConnection();
            $sql = "INSERT INTO user_images (user_id, file_name, file_hash) VALUES (:user_id, :file_name, :file_hash)";
            $query = $db->prepare($sql);
            $query->execute([
                ':user_id' => $user_id,
                ':file_name' => $file['name'],
                ':file_hash' => $file_hash
            ]);
            return true;
        }
        return false;
    }

    public static function getUserImages($user_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT * FROM user_images WHERE user_id = :user_id";
        $query = $db->prepare($sql);
        $query->execute([':user_id' => $user_id]);
        return $query->fetchAll();
    }

    public static function getImageByHash($hash)
    {
        $db = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT * FROM user_images WHERE file_hash = :hash AND is_shared = 1";
        $query = $db->prepare($sql);
        $query->execute([':hash' => $hash]);
        $image = $query->fetch();

        if ($image) {
            return '../userpictures/' . $image->user_id . '/' . $image->file_hash . '.' . pathinfo($image->file_name, PATHINFO_EXTENSION);
        }
        return false;
    }

    //löschen von bilder
    public static function deleteImage($image_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT * FROM user_images WHERE id = :image_id";
        $query = $db->prepare($sql);
        $query->execute([':image_id' => $image_id]);
        $image = $query->fetch();

        if ($image) {
            $filePath = '../userpictures/' . $image->user_id . '/' . $image->file_hash . '.' . pathinfo($image->file_name, PATHINFO_EXTENSION);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $sql = "DELETE FROM user_images WHERE id = :image_id";
            $query = $db->prepare($sql);
            return $query->execute([':image_id' => $image_id]);
        }
        return false;
    }



}
