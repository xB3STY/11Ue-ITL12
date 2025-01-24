<?php

class RegisterController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Zeigt das Registrierungsformular an.
     */
    public function index()
    {
        if (LoginModel::isUserLoggedIn()) {
            Redirect::home();
        } else {
            $this->View->render('register/index');
        }
    }

    /**
     * Registrierung mit reCAPTCHA-Validierung
     */
    public function register_action()
    {
        // Google reCAPTCHA Secret Key (Backend)
        $recaptcha_secret = '6LcAUrkqAAAAAHZ-vqhE3niSVr3IGpQq9I9oB737';
        $recaptcha_response = $_POST['g-recaptcha-response'];

        // Validierung des reCAPTCHA über die Google API
        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
        $captcha_success = json_decode($verify);

        if ($captcha_success->success) {
            //CAPTCHA erfolgreich -> Registrierung fortsetzen
            $registration_successful = RegistrationModel::registerNewUser();

            if ($registration_successful) {
                Session::add('feedback_positive', 'Registrierung erfolgreich!');
                Redirect::to('login/index');
            } else {
                Session::add('feedback_negative', 'Registrierung fehlgeschlagen. Bitte versuchen Sie es erneut.');
                Redirect::to('register/index');
            }
        } else {
            //CAPTCHA nicht bestanden -> Fehlermeldung anzeigen
            Session::add('feedback_negative', 'Bitte bestätigen Sie, dass Sie kein Roboter sind.');
            Redirect::to('register/index');
        }
    }

    /**
     * Bestätigt die Registrierung nach Klick auf den Verifizierungslink
     */
    public function verify($user_id, $user_activation_verification_code)
    {
        if (isset($user_id) && isset($user_activation_verification_code)) {
            RegistrationModel::verifyNewUser($user_id, $user_activation_verification_code);
            $this->View->render('register/verify');
        } else {
            Redirect::to('login/index');
        }
    }
}
