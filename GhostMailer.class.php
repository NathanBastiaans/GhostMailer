<?php
/**
 * GhostMailer is a simple class to help send mails more easily
 *
 * @author Nathan Bastiaans
 * @website www.nathanbastiaans.nl
 * @copyright 2014 Nathan Bastiaans
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

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
    public static $recipients = array ();
    
    /**
     * The array that holds all the header information.
     * @type array
     */
    public static $header = array ();
    
    /**
     * The array that holds all the attached files.
     * @type array
     */
    public static $attachments = array ();

    /**
     * Trying to send a HTML message?.
     * @type string
     */
    public static $isHTML = false;

    /**
     * The sender name and e-mail address.
     * @type string
     */
    public static $sender = '';

    /**
     * The return e-mail if the mail fails to deliver.
     * @type string
     */
    public static $returnAddress = '';
    
    /**
     * The subject of the e-mail.
     * @type string
     */
    public static $subject = '';
    
    /**
     * The message that will be sent to all the recipients.
     * @type array
     */
    public static $message = '';
    
    /**
     * Sets default values for sending mail.
     */
    public function __construct ()
    {
        
        self::setHeaders ( 'MIME-Version', '1.0' ); 
        self::setHeaders ( 'Content-Type', 'text/html; charset=iso-8859-1' ); 

        self::setHeaders ( 'X-Mailer',   'PHP/' . phpversion () ); 
        self::setHeaders ( 'X-Priority', 'Normal' ); 
        
    }

    /*
     * Getters & Setters
     */
    
    /**
     * Returns the isHTML value.
     * @return bool
     */
    public static function getHTML ()
	{

		return self::$isHTML;

	}
    
    /**
     * Sets the isHTML value
     * @param bool
     */
    public static function setHTML ( $bool )
	{

		self::$isHTML = $bool;

	}
    
    /**
     * Returns the recipients.
     * @return array
     */
    public static function getRecipients () {

		return self::$recipients;

	}
    
    /**
     * Resets the recipients to none.
     */
    public static function clearRecipients ()
	{
		
		self::$recipient = array ();

	}

    /**
     * Adds a recipient.
     * @param string
     */
    public static function addRecipient ( $recipient )
	{

		array_push ( self::$recipients, $recipient );

	}

    /**
     * Returns the senders e-mail address.
     * @return string
     */
    public static function getSender ()
	{
		
		return self::$sender;

	}
    
    /**
     * Sets the sender e-mail.
     * @param string
     */
    public static function setSender ( $sender )
    {

        self::$sender = $sender; 
        self::setHeaders ( 'From', $sender );
        self::setHeaders ( 'Reply-To', $sender );
        
    }

    /**
     * Returns the return e-mail address.
     * @return string
     */
    public static function getReturnAddress ()
	{

		return self::$returnAddress;

	}
    
    /**
     * Sets the sender e-mail.
     * @param string
     */
    public static function setReturnAddress ( $address )
    {
        
        self::$returnAddress = $address; 
        self::setHeaders ( 'Return-Path', $address );
        
    }
    
    /**
     * Returns the subject.
     * @return string
     */
    public static function getSubject ()
	{
		
		return self::$subject;

	}
    
    /**
     * Sets the subject of the e-mail.
     * @param string
     */
    public static function setSubject ( $subject )
	{
		
		self::$subject = $subject;

	}
    
    /**
     * Returns the message.
     * @return string
     */
    public static function getMessage ()
	{
		
		return self::$message;

	}
    
    /**
     * Sets the message/body of the e-mail.
     * @param string
     */
    public static function setMessage ( $message )
	{
		
		self::$message = $message;

	}
    
    /**
     * Returns the headers.
     * @return array
     */
    public static function getHeaders ()
	{

		return self::$header;

	}
    
    /**
     * Sets header value
     * @param string $key
     * @param string $value
     */
    public static function setHeaders ( $key, $value )
	{ 
		
		self::$header[ $key ] = $value;

	}
    
    /**
     * Returns the attached files.
     * @return array
     */
    public static function getAttachements ()
	{

		return self::$attachments;

	}
    
    /**
     * Adds an attachment to the e-mail.
     * @param string
     * @return bool
     */
    public static function addAttachment ( $attachment )
    {

        // File sanity checking
        if ( is_file ( $attachment ) ) 
        {
        
            array_push ( self::$attachments, $attachment );
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
    public static function quickSend ( $to, $from, $subject, $message, $headers = array (), $attachments = array () ) 
    {

        // If the mail has multiple recipients
        if ( is_array ( $to ) )
        {
        
            foreach ( $to as $recipient )
            {
            
                self::addRecipient ( $recipient );
            
            }
            
        }
        else
        {
        
            self::addRecipient ( $to );
        
        }
        
        self::setSender ( $from );
        
        self::setReturnAddress ( $from );
        
        self::setSubject ( $subject );
        
        self::setMessage ( $message );

        // If message contains HTML
        if ( $message != strip_tags ( $message ) ) 
        {
        
            self::setHTML ( true );
        
        }

        // If header given
        if ( count ( $headers ) > 0 )
        {

            foreach ( $headers as $key => $value )
            {
            
                self::setHeaders ( $key, $value );
            
            }
            
        }

        // If attachments given
        if ( count ( $attachments ) > 0 )
        {

            foreach ( $attachments as $file )
            {
            
                self::addAttachment ( $file ); 
            
            }
            
        }

        return self::send ();
        
    }
    
    /**
     * Sends the e-mail to all the recipients.
     * @return bool
     */
    public static function send ()
    {

        $message = self::$message;
        $head    = ""; 
        foreach ( self::$header as $key => $value ) { $head.= $key . ': ' . $value . self::EOL; }

        // If attachments given
        if ( count ( self::$attachments ) > 0 ) 
        {
            
            $separator = md5 ( time() );
            self::setHeaders ( 'Content-Type', 'multipart/mixed; boundary="' . $separator . '"' );

            $head = ""; 
            foreach ( self::$header as $key => $value ) { $head.= $key . ': ' . $value . self::EOL; }
            $head.= "Content-Transfer-Encoding: 7bit" . self::EOL;
            $head.= "This is a MIME encoded message." . self::EOL . self::EOL;

            // Preparing the message with proper formatting, charsets, content-types and encoding.
            $head .= "--" . $separator . self::EOL;
            $head .= "Content-Type: text/" . ( self::$isHTML ? 'html' : 'plain' ) . "; charset=\"iso-8859-1\"" . self::EOL;
            $head .= "Content-Transfer-Encoding: 8bit" . self::EOL . self::EOL;
            $head .= $message . self::EOL . self::EOL;
            $head .= "--" . $separator . self::EOL;
            
            $message = "";

            // Attach all given attachments to the mail
            foreach ( self::$attachments as $attached )
            {
            
                $tmp      = explode ( "/", $attached );
                $filename = end ( $tmp );

                $file_size = filesize ( $attached );


                try // Try to open the file
                {

                    $handle  = fopen ( $attached, "r" );
                    $content = fread ( $handle, $file_size );
                    fclose ( $handle );


                }
                catch ( Exception $e )
                {

                    die ( "[GhostMailer] FATAL ERROR IN ATTACHMENTS: Could not open file; Stacktrace: " . $e->getMessage () );

                }

                $content = chunk_split ( base64_encode ( $content ) );

                // attachment
                $head .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . self::EOL;
                $head .= "Content-Transfer-Encoding: base64" . self::EOL;
                $head .= "Content-Disposition: attachment" . self::EOL . self::EOL;
                $head .= $content . self::EOL . self::EOL;
                $head .= "--" . $separator . self::EOL;

            }
            
        }
        
$head = preg_replace("/[\r\n]+/", "\n", $head );

        foreach ( self::$recipients as $recipient ) 
        {
        
            if ( ! mail (
                    $recipient,
                    self::$subject ,
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
