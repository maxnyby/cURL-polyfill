if(!function_exists("curl_init"))
{
	function curl_init()
	{
		return new Curl();
	}
	
	function curl_setopt(&$ch, $option, $value)
	{
		$ch->setopt($option, $value);
	}

	function curl_getinfo(&$ch, $opt)
	{
		return $ch->getinfo($opt);
	}
	
	function curl_exec(&$ch)
	{
		return $ch->execute();
	}
	function curl_close(&$ch)
	{
		$ch->close();
	}


	class Curl
	{
		function setopt($option, $value)
		{
			$this->$option = $value;
		}
		
		function getinfo($opt)
		{
			return $this->{$opt};
		}
		
		function close()
		{
			if($this->fp)
			{
				fclose($this->fp);
				unset($this->fp);
			}
		}
		
		function execute()
		{

			if(gettype($this->{CURLOPT_POSTFIELDS}) == 'array')
			{
				$data = http_build_query($this->{CURLOPT_POSTFIELDS});
			}
			else
			{
				$data = $this->{CURLOPT_POSTFIELDS};
			}
		
			$params = array('http' => 
			  	array(
					'content' => $data
				)
			);
			
			$method = "GET";
	  		$method = $this->{CURLOPT_POST} ? "POST" : $method;
	  		$method = $this->{CURLOPT_CUSTOMREQUEST} ? $this->{CURLOPT_CUSTOMREQUEST} : $method;
	  		if($method != "GET")
	  		{
		  		$params['http']['method'] = $method;
	  		}

		
			$params['http']['header'] = implode("\r\n", $this->{CURLOPT_HTTPHEADER});
		
			$ctx = stream_context_create($params);
		
			$fp = @fopen($this->{CURLOPT_URL}, 'rb', false, $ctx);
			$this->fb = $fp;

			try
			{
				if (!$fp) {
					throw new Exception("Problem with {$this->{CURLOPT_URL}}, $php_errormsg");
				}
				$response = @stream_get_contents($fp);
				if ($response === false) {
					throw new Exception("Problem reading data from $url, $php_errormsg");
				}
	
				$meta = @stream_get_meta_data($fp);

				$status = explode(' ',$meta['wrapper_data'][0]);
				$this->{CURLINFO_HTTP_CODE} = $status[1];
	
				if($this->{CURLOPT_RETURNTRANSFER})
				{
					return $response;
				}
				else
				{
					echo $response;
				}
			}
			catch (Exception $e)
			{
				pre($params);
				$debug = debug_backtrace();
				pre($debug);
				pre($e->getMessage());
			}
			return;
		}
	}

}
