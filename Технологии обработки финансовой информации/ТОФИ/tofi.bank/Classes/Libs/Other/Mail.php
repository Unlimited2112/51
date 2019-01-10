<?php
class Mail // use as namespace
{
	static public function sendEmail($from, $to, $subject, $message, $attachments = null, $bcc=array(), $noFrom=false)
	{
		define('EMAIL_CRLF', "\n");

		Loader::loadLib('PHPMailer', 'Vendors');

		$mail = new PHPMailer();

		$mail->CharSet = "UTF-8";

        if($noFrom) {
            $mail->FromName = $from;
        }
        else {
            if (is_array($from) )
            {
                $mail->From     	= arr_val($from,'0');
                $mail->FromName 	= arr_val($from,'1');
            }
            else
            {
                $mail->From     	= $from;
                $mail->FromName 	= $from;
            }
        }

		$mail->Body 	= $message;
		$mail->AltBody	= $message;
		$mail->Sender	= $from;

		$mail->AddAddress($to);

		if ( is_object($attachments) )
		{
			foreach ( $attachments->attachments as $idx => $obj )
			{
				$mail->AddAttachment ( $obj->file_path, $obj->file_name, "base64", $obj->type );
			}
		}

		$mail->Mailer = "mail";
		$mail->Subject	= $subject;
		foreach ( $bcc as $bcc_email )
		{
			if ( $bcc_email )
				$mail->AddBCC($bcc_email);
		}
		$sent = true;

		try
		{
			!$mail->Send();
		}
		catch (Exception $e)
		{
			$sent = false;
		}

		if ( is_object($attachments) ) {
			foreach ( $attachments->attachments as $idx => $obj ) {
				unlink($obj->file_path);
			}
		}

		return $sent;
	} // end of send_email
}

class Attachments
{
	public $attachments;

	function Attachments()
	{
		$this->attachments = array();
	}
	
	function AddAttachment(&$attach)
	{
		$this->attachments[] = $attach;
	}
}

class Attachment
{
	public $file_name;

	public $content;

	public $type;

	function Attachment()
	{
		$this->content = '';
		$this->type = '';
		$this->file_path = '';
	}

	function LoadFile($file_name, $type)
	{
		$fh = @fopen($file_name, 'rb');
		if ($fh)
		{
			$this->file_path = $file_name;
			$pos = strrpos($file_name, '/');
			if ($pos === false)
				$pos = strrpos($file_name, '\\');
			$this->file_name = substr($file_name, $pos+1);
			//$this->content = fread($fh, filesize($file_name));
			$this->type = $type;
			fclose($fh);
			return true;
		}
		return false;
	}

	function LoadString($file_name, $file_content, $type)
	{
		$this->file_name = $file_name;
		$this->content = $file_content;
		$this->type = $type;
	}
}