<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Core\Mailer;

use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Renderer\RendererInterface;

/**
 * The MailMessage class.
 *
 * @since  3.0
 */
class MailMessage
{
	/**
	 * Property subject.
	 *
	 * @var  string
	 */
	protected $subject;

	/**
	 * Property to.
	 *
	 * @var  array
	 */
	protected $to = [];

	/**
	 * Property from.
	 *
	 * @var  array
	 */
	protected $from = [];

	/**
	 * Property cc.
	 *
	 * @var  array
	 */
	protected $cc = [];

	/**
	 * Property bcc.
	 *
	 * @var  array
	 */
	protected $bcc = [];

	/**
	 * Property content.
	 *
	 * @var  string
	 */
	protected $body;

	/**
	 * Property html.
	 *
	 * @var  bool
	 */
	protected $html = true;

	/**
	 * Property files.
	 *
	 * @var  MailAttachment[]
	 */
	protected $files = [];

	/**
	 * create
	 *
	 * @return  MailMessage
	 */
	public static function create()
	{
		return new static;
	}

	/**
	 * MailMessage constructor.
	 *
	 * @param string $subject
	 * @param array  $content
	 * @param bool   $html
	 */
	public function __construct($subject = null, $content = null, $html = true)
	{
		$this->subject = $subject;
		$this->body    = $content;
		$this->html    = $html;
	}

	/**
	 * subject
	 *
	 * @param   string  $subject
	 *
	 * @return  static
	 */
	public function subject($subject)
	{
		$this->subject = $subject;

		return $this;
	}

	/**
	 * to
	 *
	 * @param string $email
	 * @param string $name
	 *
	 * @return  static
	 */
	public function to($email, $name = null)
	{
		$this->addEmail('to', $email, $name);

		return $this;
	}

	/**
	 * from
	 *
	 * @param string $email
	 * @param string $name
	 *
	 * @return  static
	 */
	public function from($email, $name = null)
	{
		$this->addEmail('from', $email, $name);

		return $this;
	}

	/**
	 * cc
	 *
	 * @param string $email
	 * @param string $name
	 *
	 * @return  static
	 */
	public function cc($email, $name = null)
	{
		$this->addEmail('cc', $email, $name);

		return $this;
	}

	/**
	 * bcc
	 *
	 * @param string $email
	 * @param string $name
	 *
	 * @return  static
	 */
	public function bcc($email, $name = null)
	{
		$this->addEmail('bcc', $email, $name);

		return $this;
	}

	/**
	 * content
	 *
	 * @param string $body
	 * @param bool   $html
	 *
	 * @return  static
	 */
	public function body($body, $html = null)
	{
		if ($html !== null)
		{
			$this->html($html);
		}

		$this->body = $body;
		
		return $this;
	}

	/**
	 * html
	 *
	 * @param   boolean  $bool
	 *
	 * @return  static
	 */
	public function html($bool)
	{
		$this->html = (bool) $bool;

		return $this;
	}

	/**
	 * from
	 *
	 * @param string $file
	 * @param string $name
	 * @param string $type
	 *
	 * @return static
	 */
	public function attach($file, $name = null, $type = null)
	{
		if (!$file instanceof MailAttachment)
		{
			$file = new MailAttachment($file);
		}

		if ($name)
		{
			$file->setFilename($name);
		}

		if ($type)
		{
			$file->setContentType($type);
		}

		$this->files[] = $file;

		return $this;
	}

	/**
	 * renderBody
	 *
	 * @param string                   $layout
	 * @param array                    $data
	 * @param string|RendererInterface $engine
	 * @param string|AbstractPackage   $package
	 * @param string                   $prefix
	 *
	 * @return static
	 */
	public function renderBody($layout, $data = [], $engine = null, $package = null, $prefix = 'mail')
	{
		$widget = WidgetHelper::createWidget($layout, $engine, $package);
		$widget->setPathPrefix($prefix);

		$this->body($widget->render($data), true);

		return $this;
	}

	/**
	 * Method to get property Subject
	 *
	 * @return  string
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * Method to get property To
	 *
	 * @return  array
	 */
	public function getTo()
	{
		return $this->to;
	}

	/**
	 * Method to get property From
	 *
	 * @return  array
	 */
	public function getFrom()
	{
		return $this->from;
	}

	/**
	 * Method to get property Cc
	 *
	 * @return  array
	 */
	public function getCc()
	{
		return $this->cc;
	}

	/**
	 * Method to get property Bcc
	 *
	 * @return  array
	 */
	public function getBcc()
	{
		return $this->bcc;
	}

	/**
	 * Method to get property Content
	 *
	 * @return  string
	 */
	public function getBody()
	{
		return $this->body;
	}

	/**
	 * Method to get property Html
	 *
	 * @return  boolean
	 */
	public function getHtml()
	{
		return $this->html;
	}

	/**
	 * Method to get property Files
	 *
	 * @return  MailAttachment[]
	 */
	public function getFiles()
	{
		return $this->files;
	}

	/**
	 * add
	 *
	 * @param string $field
	 * @param string $email
	 * @param string $name
	 *
	 * @return  void
	 */
	protected function addEmail($field, $email, $name = null)
	{
		if (is_array($email))
		{
			foreach ($email as $mail => $name)
			{
				if (is_numeric($mail))
				{
					$mail = $name;
					$name = null;
				}

				$this->$field($mail, $name);
			}
		}

		$this->{$field}[$email] = $name;
	}
}
