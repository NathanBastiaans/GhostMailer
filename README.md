Ghost Mailer
=======
Ghost Mailer is a light weight PHP mailer class designed for easy use. It's still a work in progress.


QuickSend
--------------
The quicksend function allows you to send an e-mail with minimal effort. 
All headers and variables needed are set for you. The only thing you need to worry about is entering the following variables.

- $to
- $from
- $subject
- $message
- $headers ( optional )
- $attachments ( optional )


Attachments
--------------
One of the greatest things about GhostMailer is the ability to send attachments. 
If you're using the quicksend function all you need is an array with the location of the files you want to send.

```
$mailer = new GhostMailer ();
$mailer->quickSend (  $to, $from, $subject, $message, $headers = array (), $attachments = array ( 'path/to/file.pdf', 'path/to/document.doc' ) );
```

If you're using the send function you can add attachments by calling the addAttachment function.

```
$mailer = new GhostMailer ();
$mailer->addAttachment ( 'path/to/file.pdf' );
$mailer->addAttachment ( 'path/to/document.doc' );
```
