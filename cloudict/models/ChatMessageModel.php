<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Goran
 * Date: 6/14/2015
 * Time: 5:09 PM
 */




class ChatMessageModel extends CI_Model {


    /** get all messages for 2 users
     *
     * @param int $MainId  - logged in user
     * @param int $SecondId - 2nd user for chat
     * @return mixed
     */
    public function getMessages($MainId,$SecondId)
    {
        $query= "

            SELECT	`IdChatMessage` AS `Id`,
				`IdSender`,
				`IdReceiver`,
				`ChatMessageText` AS `Message`,
				`ChatMessageTime` AS `Time`,
				`ChatMessageSenderName` AS `Sender`,
				`ChatMessageReceiverName` AS `Reciever`

			FROM 	`Chatmessage`

			WHERE	`IdSender` = ? AND `IdReceiver` = ?
			OR   `IdSender` = ? AND `IdReceiver` = ?

        ";
        $result = $this->db->query($query, [$MainId,$SecondId,$SecondId,$MainId])->result_array();
        return $result;

    }


/**
 * Get all messages for Group
 * @param  int $IdGroup 
 * @return array          group messages
 */
    public function getGroupMessages($IdGroup)
    {
        $query= "

            SELECT  `IdChatMessage` AS `Id`,
                `IdGroup`,
                `IdSender`,
                `IdReceiver`,
                `ChatMessageText` AS `Message`,
                `ChatMessageTime` AS `Time`,
                `ChatMessageSenderName` AS `Sender`,
                `ChatMessageReceiverName` AS `Reciever`

            FROM    `Chatmessage`

            WHERE   `IdGroup` = ? 

        ";
        $result = $this->db->query($query, [$IdGroup])->result_array();
        return $result;

    }


    /**insert new message
     *
     * @param $IdSender
     * @param $IdReceiver
     * @param $TextMessage
     * @param $SenderName
     * @param $ReceiverName
     */
    public function insertMessage($IdGroup,$IdSender, $IdReceiver, $TextMessage, $SenderName,$ReceiverName)
    {

        $query = "
			INSERT INTO `chatmessage`(`IdGroup`,`IdSender`,`IdReceiver`,`ChatMessageText`,`ChatMessageTime`,`ChatMessageSenderName`,`ChatMessageReceiverName`)

			VALUES (?,?,?,?,?,?,?);
		";

        $result = $this->db->query($query, [$IdGroup,$IdSender,$IdReceiver,$TextMessage,time(),$SenderName,$ReceiverName]);


    }


}