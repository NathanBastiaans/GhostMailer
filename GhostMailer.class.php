<?php

class GhostMailer {

	/**
	 * The array that holds all the recipients e-mail addresses.
	 * @type array
	 */
	public $recipients	= array();
	
	/**
	 * The array that holds all the header information.
	 * @type array
	 */
	public $header		= array();
	
	/**
	 * The array that holds all the attached files.
	 * @type array
	 */
	public $attachments	= array();

	/**
	 * The sender name and e-mail address.
	 * @type string
	 */
	public $sender	= '';
	
	/**
	 * The subject of the e-mail.
	 * @type string
	 */
	public $subject	= '';
	
	/**
	 * The message that will be sent to all the recipients.
	 * @type array
	 */
	public $message	= '';
	
    /**
     * Returns the recipients.
     * @return array
     */
	public function getRecipients () {
	
		return $this->recipients;
	
	}
	
	/**
     * Resets the recipients to none.
     */
	public function clearRecipients () {
	
		$this->recipient = array();
	
	}

	/**
     * Adds a recipient.
     * @param string
     */
	public function addRecipient ( $recipient ) {

		$this->recipients[] = $recipient;
	
	}

	/**
     * Returns the senders e-mail address.
     * @return string
     */
	public function getSender () {
	
		return $this->sender;
	
	}
	
	/**
     * Sets the sender e-mail.
     * @param string
     */
	public function setSender ( $sender ) {
		
		$this->sender = $sender; 
		
	}
	
	/**
     * Returns the subject.
     * @return string
     */
	public function getSubject () {
	
		return $this->subject;
	
	}
	
	/**
     * Sets the subject of the e-mail.
     * @param string
     */	
	public function setSubject ( $subject ) {

		$this->subject = $subject;
	
	}
	
	/**
     * Returns the message.
     * @return string
     */
	public function getMessage () {
	
		return $this->message;
	
	}
	
	/**
     * Sets the message/body of the e-mail.
     * @param string
     */
	public function setMessage ( $message ) {

		$this->message = $message;
	
	}
	
	/**
     * Returns the headers.
     * @return array
     */
	public function getHeaders () {
	
		return $this->header;
	
	}
	
	/**
     * Sets header value
     * @param string $key
     * @param string $value
     */
	public function setHeaders ( $key, $value ) {
	
		$this->header[ $key ] = $value;
	
	}
	
	/**
     * Returns the attached files.
     * @return array
     */
	public function getAttachements () {
	
		return $this->attachments;
	
	}
	
	/**
     * Adds an attachment to the e-mail.
     * @param string
     */
	public function setAttachment ( $attachment ) {
	
		$this->attachments[] = $attachment;
	
	}
	
	/**
     * Sends the e-mail to all the recipients.
     * @return bool
     */
	public function send () {

		foreach($this->recipients as $recepient) {
		
			if( ! mail(
					$recepient,
					$this->subject ,
					$this->message ,
					$this->headers
				)
			) {
				return false;
			}
			
		}
		
		return true;
	
	}

}