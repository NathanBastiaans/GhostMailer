<?php

class GhostMailer {

    /**
     * The end of line constant for the header.
     * @type string
     */
    const EOL = "\n";

	/**
	 * The array that holds all the recipients e-mail addresses.
	 * @type array
	 */
	private $recipients	= array();
	
	/**
	 * The array that holds all the header information.
	 * @type array
	 */
	private $header		= array();
	
	/**
	 * The array that holds all the attached files.
	 * @type array
	 */
	private $attachments	= array();

	/**
	 * Trying to send a HTML message?.
	 * @type string
	 */
	private $isHTML	= false;

	/**
	 * The sender name and e-mail address.
	 * @type string
	 */
	private $sender	= '';

	/**
	 * The return e-mail if the mail fails to deliver.
	 * @type string
	 */
	private $returnAddress	= '';
	
	/**
	 * The subject of the e-mail.
	 * @type string
	 */
	private $subject	= '';
	
	/**
	 * The message that will be sent to all the recipients.
	 * @type array
	 */
	private $message	= '';
	
    /**
	 * Sets default values for sending mail.
	 */	
	public function __construct () {
		
		$this->setHeaders ( 'MIME-Version',	'1.0' ); 
		$this->setHeaders ( 'Content-Type',	'text/html; charset=iso-8859-1' ); 

		$this->setHeaders ( 'X-Mailer',		'PHP/' . phpversion() ); 
		$this->setHeaders ( 'X-Priority',	'Normal' ); 

		$this->setSender ( 'Example <info@example.com>' );
		$this->setReturnAddress ( 'Example <info@example.com>' );
		
	}
	
    /**
	 * Returns the isHTML value.
	 * @return bool
	 */	
	public function getHTML () {
	
		return $this->isHTML;
	
	}
	
	/**
	 * Sets the isHTML value
	 * @param bool
	 */
	public function setHTML ( $bool ) {

		$this->isHTML = $bool;
	
	}
	
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

		array_push( $this->recipients, $recipient );
	
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
		$this->setHeaders( 'From', $sender );
		$this->setHeaders( 'Reply-To', $sender );
		
	}

	/**
	 * Returns the return e-mail address.
	 * @return string
	 */
	public function getReturnAddress () {
	
		return $this->returnAddress;
	
	}
	
	/**
	 * Sets the sender e-mail.
	 * @param string
	 */
	public function setReturnAddress ( $address ) {
		
		$this->returnAddress = $address; 
		$this->setHeaders( 'Return-Path', $address );
		
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
	 * @return bool
	 */
	public function addAttachment ( $attachment ) {
		
		if( is_file ( $attachment ) ) {
		
			array_push( $this->attachments, $attachment );
			return true;
			
		} 
		
		return false;
	
	}
	
	/**
	 * Sends an email from just one function 
	 * @param string $to
	 * @param string $from
	 * @param string $subject
	 * @param string $message
	 * @param array $headers
	 * @param array $attachments
	 * @return bool
	 */
	public function quickSend ( $to, $from, $subject, $message, $headers = array(), $attachments = array() ) {
	
		if( is_array( $to ) ) {
		
			foreach( $to as $recipient ) {
			
				$this->addRecipient( $recipient );
			
			}
			
		} else {
		
			$this->addRecipient( $to );
		
		}
		
		$this->setSender( $from );
		
		$this->setReturnAddress( $from );
		
		$this->setSubject( $subject );
		
		$this->setMessage( $message );
		
		if( $message != strip_tags( $message ) ) {
		
			$this->setHTML( true );
		
		}
		
		if( count ( $headers ) > 0 ) {

			foreach( $headers as $key => $value ) {
			
				$this->setHeaders ( $key, $value );
			
			}
			
		}

		if( count ( $attachments ) > 0 ) {

			foreach( $attachments as $file ) {
			
				$this->addAttachment ( $file ); 
			
			}
			
		}

		return $this->send();
		
	}
	
	/**
	 * Sends the e-mail to all the recipients.
	 * @return bool
	 */
	public function send () {

		$message	= $this->message;
		$head		= ""; 
		foreach ( $this->header as $key => $value) { $head.= $key . ': ' . $value . self::EOL; }

		if( count( $this->attachments ) > 0 ) {
			
			$separator = md5( time() );
			$this->setHeaders( 'Content-Type', 'multipart/mixed; boundary="' . $separator . '"' );

			$head		= ""; 
			foreach( $this->header as $key => $value ) { $head.= $key . ': ' . $value . self::EOL; }
			$head.= "Content-Transfer-Encoding: 7bit" . self::EOL;
			$head.= "This is a MIME encoded message." . self::EOL . self::EOL;

			// message
			$head .= "--" . $separator . self::EOL;
			$head .= "Content-Type: text/" . ( $this->isHTML ? 'html' : 'plain' ) . "; charset=\"iso-8859-1\"" . self::EOL;
			$head .= "Content-Transfer-Encoding: 8bit" . self::EOL . self::EOL;
			$head .= $message . self::EOL . self::EOL;
			$head .= "--" . $separator . self::EOL;
			
			$message = "";
			
			foreach( $this->attachments as $attached ) {
			
				$tmp		= explode("/", $attached);
				$filename	= end( $tmp );

				$file_size = filesize( $attached );
				$handle = fopen( $attached, "r" );
				$content = fread( $handle, $file_size );
				fclose( $handle );
				$content = chunk_split( base64_encode( $content ) );

				// attachment
				$head .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . self::EOL;
				$head .= "Content-Transfer-Encoding: base64" . self::EOL;
				$head .= "Content-Disposition: attachment" . self::EOL . self::EOL;
				$head .= $content . self::EOL . self::EOL;
				$head .= "--" . $separator . self::EOL;

			}
			
		}

		foreach( $this->recipients as $recipient ) {
		
			if( ! mail(
					$recipient,
					$this->subject ,
					$message ,
					$head
				)
			) {
				return false;
			}
			
		}
		
		return true;
	
	}

}