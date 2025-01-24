<?php

class MessagesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();
    }

    public function index()
    {
        $current_user_id = Session::get('user_id');
        $contacts = MessageModel::getAllContacts($current_user_id);
        $unread_messages = MessageModel::getUnreadMessagesWithSenders($current_user_id);

        $this->View->render('messages/index', [
            'contacts' => $contacts,
            'unread_messages' => $unread_messages // Hier wird die Variable korrekt an die View übergeben
        ]);
    }

    public function chat($contact_id)
    {
        $user_id = Session::get('user_id');

        // Lade die Nachrichten zwischen dem aktuellen Benutzer und dem Kontakt
        $messages = MessageModel::getMessagesBetween($user_id, $contact_id);

        // Markiere die Nachrichten als gelesen
        MessageModel::markMessagesAsRead($user_id, $contact_id);

        // Lade die Kontaktdaten
        $contact = UserModel::getUserById($contact_id);

        $this->View->render('messages/chat', [
            'messages' => $messages,
            'current_contact' => $contact
        ]);
    }

    public function sendMessage()
    {
        $sender_id = Session::get('user_id');
        $receiver_id = Request::post('receiver_id');
        $message_content = Request::post('message_content');

        if (MessageModel::sendMessage($sender_id, $receiver_id, $message_content)) {
            Session::add('feedback_positive', 'Message sent successfully.');
        } else {
            Session::add('feedback_negative', 'Failed to send message.');
        }

        Redirect::to('messages/chat/' . $receiver_id);
    }
}
