<?php
require_once 'common.php';
require_once 'url.php';

/*
 helper_email
 Email helpers
 mReschke 2012-10-29
*/
class helper_email
{
    public $smtp_server;
    public $smtp_port;
    public $smtp_user;
    public $smtp_pass;
    public $from;
    public $to;
    public $cc;
    public $bcc;
    public $subject;
    public $body;
    public $files;
    public $as_html;
    public $url;

    public function __construct($to=null, $subject=null, $body=null)
    {
        #$this->smtp_server = \Config::get('mail.host');
        #$this->smtp_port = \Config::get('mail.port');
        #$this->smtp_user = \Config::get('mail.username');
        #$this->smtp_pass = \Config::get('mail.password');
        #$this->from = \Config::get('mail.from.address');
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->files = null;
        $this->tmpfiles = null;
        $this->as_html = true;
        $this->tmpdir = '/tmp/wiki_helper_email/';
        $this->url = new helper_url;
    }

    public function send()
    {
        $args = [];
        if (!$this->body) {
            $this->body = 'Empty Body';
        }
        if ($this->as_html) {
            $this->body = preg_replace('"\r\n"', '<br />', $this->body);
            $args[] = "--html='$this->body'";
        } else {
            $args[] = "--text='$this->body'";
        }

        $args[] = "--to='$this->to'";
        $args[] = "--subject='$this->subject'";
        if (isset($this->from)) {
            $args[] = "--from='$this->from'";
        }
        if (isset($this->cc)) {
            $args[] = "--cc='$this->cc'";
        }
        if (isset($this->bcc)) {
            $args[] = "--bcc='$this->bcc'";
        }
        if (isset($this->files)) {
            foreach ($this->files as $f) {
                if (file_exists($f)) {
                    $args[] = "--file='$f'";
                }
            }
        }
        if (isset($this->tmpfiles)) {
            foreach ($this->tmpfiles as $f) {
                if (file_exists($f)) {
                    $args[] = "--file='$f'";
                }
            }
        }
        exec("/usr/local/bin/email ".implode(" ", $args), $result);
        echo implode("<br />", $result);

        //Remove any TMP files
        if (isset($this->tmpfiles)) {
            foreach ($this->tmpfiles as $f) {
                if (file_exists($f)) {
                    unlink($f);
                }
            }
        }
    }

    public function send_OBSOLETE_sendEmail()
    {
        if ($this->as_html) {
            $args[] = "-o message-content-type=html";
            $this->body = preg_replace('"\r\n"', '<br />', $this->body);
        } else {
            $args[] = "-o message-content-type=text";
        }
        if (!$this->body) {
            $this->body = 'Empty Body';
        }
        $args[] = "-f ".$this->from;
        $args[] = "-t ".$this->to;
        $args[] = "-u ".$this->subject;
        $args[] = "-m ".escapeshellarg($this->body);
        $args[] = "-s ".$this->smtp_server.":".$this->smtp_port;

        if (isset($this->smtp_user)) {
            $args[] = "-xu ".$this->smtp_user;
        }
        if (isset($this->smtp_pass)) {
            $args[] = "-xp ".$this->smtp_pass;
        }
        if (isset($this->cc)) {
            $args[] = "-cc ".$this->cc;
        }
        if (isset($this->bcc)) {
            $args[] = "-bcc ".$this->bcc;
        }
        if (isset($this->files)) {
            foreach ($this->files as $f) {
                if (file_exists($f)) {
                    $args[] = "-a ".$f;
                }
            }
        }
        if (isset($this->tmpfiles)) {
            foreach ($this->tmpfiles as $f) {
                if (file_exists($f)) {
                    $args[] = "-a ".$f;
                }
            }
        }
        exec("/usr/local/bin/sendEmail ".implode(" ", $args), $result);
        echo implode("<br />", $result);

        //Remove any TMP files
        if (isset($this->tmpfiles)) {
            foreach ($this->tmpfiles as $f) {
                if (file_exists($f)) {
                    unlink($f);
                }
            }
        }
    }

    public function get_table_email_style()
    {
        //Sometimes I want to email a table output, so I need to include some styles along with that email HTML
        return "
        <style type='text/css'>
            .table_table {
                font-size: 11px;
                font-family:  arial, sans-serif, sans, FreeSans;
                border: 1px solid #6B90DA;
                margin-bottom: 5px;
                border: 1px solid #ccc;
            }
            .table_tr, .table_table tr {
                font-size: 11px;
                font-family:  arial, sans-serif, sans, FreeSans;
                border: 0px;
            }
            .table_tr_odd {
                background-color: #ffffff;
            }
            .table_tr_even {
                background-color: #f1f1f1;
            }
            .table_tr_even:hover .table_tr_odd:hover {
                background-color: #eef0f9;
            }
            .table_th, .table_table th {
                font-size: 11px;
                font-family:  arial, sans-serif, sans, FreeSans;
                padding: 2px;
                border-spacing: 2px;
                color: #000000;
                text-align: left;
                border-bottom: 1px solid #C9D7F1;
            }
            .table_td, .table_table td {
                font-size: 11px;
                font-family:  arial, sans-serif, sans, FreeSans;
                padding: 2px;
                border-spacing: 2px;
                color: #333333;
            }
        </style>
        ";
    }
}
