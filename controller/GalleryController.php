<?php

class GalleryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();
    }

    public function index()
    {
        $user_id = Session::get('user_id');
        $images = GalleryModel::getUserImages($user_id);

        $this->View->render('gallery/index', [
            'images' => $images
        ]);
    }

    public function upload()
    {
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $user_id = Session::get('user_id');
            $result = GalleryModel::uploadImage($user_id, $_FILES['image']);

            if ($result) {
                Session::add('feedback_positive', 'Bild erfolgreich hochgeladen.');
            } else {
                Session::add('feedback_negative', 'Fehler beim Hochladen.');
            }
        }
        Redirect::to('gallery/index');
    }

    public function showImage($hash)
    {
        $filePath = GalleryModel::getImageByHash($hash);
        if ($filePath) {
            header('Content-Type: image/jpeg');
            readfile($filePath);
            exit;
        } else {
            echo "Bild nicht gefunden oder nicht freigegeben.";
        }
    }

    public function share($image_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();
        $sql = "UPDATE user_images SET is_shared = 1 WHERE id = :id";
        $query = $db->prepare($sql);
        $query->execute([':id' => $image_id]);

        Session::add('feedback_positive', 'Bild freigegeben!');
        Redirect::to('gallery/index');
    }


    //löschen von Bilder
    public function delete($image_id)
    {
        if (GalleryModel::deleteImage($image_id)) {
            Session::add('feedback_positive', 'Bild erfolgreich gelöscht.');
        } else {
            Session::add('feedback_negative', 'Fehler beim Löschen.');
        }
        Redirect::to('gallery/index');
    }



}
