<?php

class SearchController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Optional: Authentifizierung, wenn du nur eingeloggten Nutzern die Suche erlauben willst
        // Auth::checkAuthentication();
    }

    public function index()
    {
        // GET-Parameter 'q' abgreifen, z.B. /search/index?q=Testuser
        $searchQuery = Request::get('q');

        if ($searchQuery) {
            // Datenbank-Verbindung holen
            $database = DatabaseFactory::getFactory()->getConnection();

            // Sicheres Prepared Statement
            $stmt = $database->prepare("SELECT user_name, user_email FROM users WHERE user_name LIKE :search");
            $stmt->bindValue(':search', '%' . $searchQuery . '%', PDO::PARAM_STR);
            $stmt->execute();

            // Alle Treffer abholen
            $results = $stmt->fetchAll();
        } else {
            $results = [];
        }

        // View anzeigen und Ergebnisse übergeben
        $this->View->render('search/index', [
            'results' => $results
        ]);
    }
}
