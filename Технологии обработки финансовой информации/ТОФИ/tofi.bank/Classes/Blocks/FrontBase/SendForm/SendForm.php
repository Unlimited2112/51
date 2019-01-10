<?php
Loader::loadBlock('ActiveForm', 'FrontBase');

/**
 * 
 * @name	CSendForm
 * @author	EvGo
 * @version	1.0.0.0 created (EvGo)
 */
abstract class SendForm extends ActiveForm
{
	/**
	 * Send to Email
	 *
	 * @var string
	 */
	public $EmailTo = '';

	/**
	 * Send from Email Field in Field Array
	 *
	 * @var string
	 */
	public $EmailFrom = '';

	/**
	 * Email Subject
	 *
	 * @var string
	 */
	public $Subject = '';

	/**
	 * Email Message Template
	 *
	 * @var string
	 */
	public $MessageTemplate = '%EmailMessage%';

	/**
	 * Email Message Field Template
	 *
	 * @var string
	 */
	public $MessageFieldTemplate = '<b> %EmailMessageField% :</b> %EmailMessageFieldValue%<br />';

	/**
	 * Create and Send Message from $this->Fields array and input_vars
	 *
	 */
	protected function OnSubmit()
	{
		$attachments = null;
		$message = '';
		
		Loader::loadLib('Mail', 'Other');
		
		foreach ($this->Fields as $k => $v)
		{
			if (!empty($this->tplVars['txt_' . $k]))
			{
				if ($v['type'] != 'file')
				{
					$temp = $this->MessageFieldTemplate;
					$temp = str_replace('%EmailMessageField%', $this->Localizer->getString('txt_' . $k), $temp);
					
					if ($v['type'] == 'select')
					{
						$temp = str_replace('%EmailMessageFieldValue%', htmlspecialchars($v['select_arr'][$this->tplVars['txt_' . $k]]), $temp); 
					}
					elseif ($v['type'] == 'textarea')
					{
						$temp = str_replace('%EmailMessageFieldValue%', rn2br(htmlspecialchars($this->tplVars['txt_' . $k])), $temp); 
					}
					else
					{
						$temp = str_replace('%EmailMessageFieldValue%', htmlspecialchars($this->tplVars['txt_' . $k]), $temp);
					}
					
					$message .= $temp;
				}
				else
				{
					if ((sizeof($_FILES)) && (!empty($_FILES['txt_' . $k]['name'])) && ($_FILES['txt_' . $k]['error'] === 0))
					{
						if (!is_object($attachments))
						{
							$attachments = &new Attachments();
						}
						
						$attachment = &new Attachment();
						
						$new_file_name = $GLOBALS['FilePath'] . 'temp/' . $_FILES['txt_' . $k]['name'];
						$i = 1;
						while (is_file($new_file_name))
						{
							$i++;
							$new_file_name = $GLOBALS['FilePath'] . 'temp/' . $i . '_' . $_FILES['txt_' . $k]['name'];
						}
						copy($_FILES['txt_' . $k]['tmp_name'], $new_file_name);
						
						$attachment->LoadFile($new_file_name, $_FILES['txt_' . $k]['type']);
						$attachments->AddAttachment($attachment);
					}
				}
			}
		}
		$this->EmailTo = preg_split('/\s*;\s*/si', $this->EmailTo);
		$email_to = array_shift($this->EmailTo);
		$email_from = $this->EmailFrom;
		
		$this->MessageTemplate = str_replace('%EmailMessage%', $message, $this->MessageTemplate);
		
		try
		{
			Mail::sendEmail($email_from, $email_to, $this->Subject, $this->MessageTemplate, $attachments, $this->EmailTo);
		}
		catch (Exception $e)
		{
			$this->tplVars['error'][] = $this->Localizer->getString('txt_error_email_send');
			$this->InitDefaults();
			return false;
		}
		return true;
	}
}