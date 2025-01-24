<?php

class MessageModel
{
    /**
     * Holt alle Kontakte außer dem aktuellen Benutzer.
     */
    public static function getAllContacts($current_user_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        // Aufruf der Stored Procedure 'getAllContacts'
        $sql = "CALL getAllContacts(:current_user_id)";

        $query = $db->prepare($sql);
        $query->execute([':current_user_id' => $current_user_id]);

        return $query->fetchAll();
    }

    /**
     * Holt alle Nachrichten zwischen zwei Benutzern.
     */
    public static function getMessagesBetween($user1_id, $user2_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        // Aufruf der Stored Procedure 'getMessagesBetween'
        $sql = "CALL getMessagesBetween(:user1_id, :user2_id)";

        $query = $db->prepare($sql);
        $query->execute([
            ':user1_id' => $user1_id,
            ':user2_id' => $user2_id
        ]);

        return $query->fetchAll();
    }

    /**
     * Sendet eine Nachricht von einem Benutzer zu einem anderen.
     */
    public static function sendMessage($sender_id, $receiver_id, $message_content)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        // Aufruf der Stored Procedure 'sendMessage'
        $sql = "CALL sendMessage(:sender_id, :receiver_id, :message_content)";

        $query = $db->prepare($sql);
        return $query->execute([
            ':sender_id' => $sender_id,
            ':receiver_id' => $receiver_id,
            ':message_content' => $message_content
        ]);
    }

    /**
     * Zählt alle ungelesenen Nachrichten für einen Benutzer.
     */
    public static function getUnreadMessagesCount($user_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        // Aufruf der Stored Procedure 'getUnreadMessagesCount'
        $sql = "CALL getUnreadMessagesCount(:user_id)";

        $query = $db->prepare($sql);
        $query->execute([':user_id' => $user_id]);

        return $query->fetch()->unread_count;
    }

    /**
     * Markiert alle Nachrichten eines bestimmten Absenders als gelesen.
     */
    public static function markMessagesAsRead($user_id, $sender_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        // Aufruf der Stored Procedure 'markMessagesAsRead'
        $sql = "CALL markMessagesAsRead(:user_id, :sender_id)";

        $query = $db->prepare($sql);
        $query->execute([
            ':user_id' => $user_id,
            ':sender_id' => $sender_id
        ]);
    }

    /**
     * Holt alle ungelesenen Nachrichten mit den Absenderinformationen.
     */
    public static function getUnreadMessagesWithSenders($user_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        // Aufruf der Stored Procedure 'getUnreadMessagesWithSenders'
        $sql = "CALL getUnreadMessagesWithSenders(:user_id)";

        $query = $db->prepare($sql);
        $query->execute([':user_id' => $user_id]);

        return $query->fetchAll();
    }
}
