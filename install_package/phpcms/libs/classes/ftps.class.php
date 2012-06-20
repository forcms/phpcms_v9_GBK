<?php
/**
 * FTP������
 * @author chenzhouyu
 *
 *ʹ��$ftps = pc_base::load_sys_class('ftps');���г�ʼ����
 *����ͨ����$ftps->connect($host,$username,$password,$post,$pasv,$ssl,$timeout);����FTP���������ӡ�
 *ͨ������ĺ�������FTP�Ĳ�����
 *$ftps->mkdir() ����Ŀ¼�����Դ����༶Ŀ¼�ԡ�/abc/def/higk������ʽ���ж༶Ŀ¼�Ĵ�����
 *$ftps->put()�ϴ��ļ�
 *$ftps->rmdir()ɾ��Ŀ¼
 *$ftps->f_delete()ɾ���ļ�
 *$ftps->nlist()�г�ָ��Ŀ¼���ļ�
 *$ftps->chdir()�����ǰ�ļ���
 *$ftps->get_error()��ȡ������Ϣ
 */
class ftps {
	//FTP ������Դ
	private $link;
	//FTP����ʱ��
	public $link_time;
	//�������
	private $err_code = 0;
	//����ģʽ{�ı�ģʽ:FTP_ASCII, ������ģʽ:FTP_BINARY}
	public $mode = FTP_BINARY;
	
	/**
	 * ����FTP������
	 * @param string $host    ���� ��������ַ
	 * @param string $username�������û���
	 * @param string $password����������
	 * @param integer $port��������   �������˿ڣ�Ĭ��ֵΪ21
	 * @param boolean $pasv        �Ƿ�������ģʽ
	 * @param boolean $ssl�������� ���Ƿ�ʹ��SSL����
	 * @param integer $timeout     ��ʱʱ�䡡
	 */
	public function connect($host, $username = '', $password = '', $port = '21', $pasv = false, $ssl = false, $timeout = 30) {
		$start = time();
		if ($ssl) {
			if (!$this->link = @ftp_ssl_connect($host, $port, $timeout)) {
				$this->err_code = 1;
				return false;
			}
		} else {
			if (!$this->link = @ftp_connect($host, $port, $timeout)) {
				$this->err_code = 1;
				return false;
			}
		}
		
		if (@ftp_login($this->link, $username, $password)) {
			if ($pasv) ftp_pasv($this->link, true);
			$this->link_time = time()-$start;
		   return true;
		} else {
			$this->err_code = 1;
		   return false;
		}
		register_shutdown_function(array(&$this,'close'));
	}
	
	/**
	 * �����ļ���
	 * @param string $dirname Ŀ¼����
	 */
	public function mkdir($dirname) {
		if (!$this->link) {
			$this->err_code = 2;
			return false;
		} 
		$dirname = $this->ck_dirname($dirname);
		$nowdir = '/';
		foreach ($dirname as $v) {
			if ($v && !$this->chdir($nowdir.$v)) {
				if ($nowdir) $this->chdir($nowdir);
				@ftp_mkdir($this->link, $v);
			}
			if($v) $nowdir .= $v.'/';
		}
		return true;
	}
	
	/**
	 * �ϴ��ļ�
	 * @param string $remote Զ�̴�ŵ�ַ
	 * @param string $local ���ش�ŵ�ַ
	 */
	public function put($remote, $local) {
		if (!$this->link) {
			$this->err_code = 2;
			return false;
		} 
		$dirname = pathinfo($remote,PATHINFO_DIRNAME);
		if (!$this->chdir($dirname)) {
			$this->mkdir($dirname);
		}
		if (@ftp_put($this->link, $remote, $local, $this->mode)) {
			return true;
		} else {
			$this->err_code = 7;
			return false;
		}
	}
	
	/**
	 * ɾ���ļ���
	 * @param string $dirname  Ŀ¼��ַ
	 * @param boolean $enforce ǿ��ɾ��
	 */
	public function rmdir($dirname, $enforce = false) {
		if (!$this->link) {
			$this->err_code = 2;
			return false;
		} 
		$list = $this->nlist($dirname);
		if ($list && $enforce) {
			$this->chdir($dirname);
			foreach ($list as $v) {
				$this->f_delete($v);
			}
		} elseif ($list && !$enforce) {
			$this->err_code = 3;
			return false;
		}
		@ftp_rmdir($this->link, $dirname);
		return true;
	}
	
	/**
	 * ɾ��ָ���ļ�
	 * @param string $filename �ļ���
	 */
	public function f_delete($filename) {
		if (!$this->link) {
			$this->err_code = 2;
			return false;
		} 
		if (@ftp_delete($this->link, $filename)) {
			return true;
		} else {
			$this->err_code = 4;
			return false;
		}
	}
	
	/**
	 * ���ظ���Ŀ¼���ļ��б�
	 * @param string $dirname  Ŀ¼��ַ
	 * @return array �ļ��б�����
	 */
	public function nlist($dirname) {
		if (!$this->link) {
			$this->err_code = 2;
			return false;
		} 
		if ($list = @ftp_nlist($this->link, $dirname)) {
			return $list;
		} else {
			$this->err_code = 5;
			return false;
		}
	}
	
	/**
	 * �� FTP �������ϸı䵱ǰĿ¼
	 * @param string $dirname �޸ķ������ϵ�ǰĿ¼
	 */
	public function chdir($dirname) {
		if (!$this->link) {
			$this->err_code = 2;
			return false;
		} 
		if (@ftp_chdir($this->link, $dirname)) {
			return true;
		} else {
			$this->err_code = 6;
			return false;
		}
	}
	
	/**
	 * ��ȡ������Ϣ
	 */
	public function get_error() {
		if (!$this->err_code) return false;
		$err_msg = array(
			'1'=>'Server can not connect',
			'2'=>'Not connect to server',
			'3'=>'Can not delete non-empty folder',
			'4'=>'Can not delete file',
			'5'=>'Can not get file list',
			'6'=>'Can not change the current directory on the server',
			'7'=>'Can not upload files'
		);
		return $err_msg[$this->err_code];
	}
	
	/**
	 * ���Ŀ¼��
	 * @param string $url Ŀ¼
	 * @return �� / �ֿ��ķ�������
	 */
	private function ck_dirname($url) {
		$url = str_replace('\\', '/', $url);
		$urls = explode('/', $url);
		return $urls;
	}
	
	/**
	 * �ر�FTP����
	 */
	public function close() {
		return @ftp_close($this->link);
	}
}